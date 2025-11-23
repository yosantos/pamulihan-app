<?php

namespace App\Filament\Pages;

use App\Models\WhatsAppCampaign;
use App\Models\WhatsAppMessage;
use App\Services\TemplateParserService;
use App\Services\WhatsAppService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendCampaignMessage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Send Campaign Message';

    protected static ?string $navigationGroup = 'WhatsApp Management';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.send-campaign-message';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Select Campaign')
                    ->description('Choose a campaign template to send')
                    ->schema([
                        Forms\Components\Select::make('campaign_id')
                            ->label('Campaign')
                            ->options(WhatsAppCampaign::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if (!$state) {
                                    return;
                                }

                                $campaign = WhatsAppCampaign::find($state);
                                if (!$campaign) {
                                    return;
                                }

                                // Set company name (read-only)
                                $set('company_name', $campaign->company_name);

                                // Clear dynamic variable fields
                                $dynamicVars = $campaign->getDynamicVariables();
                                foreach ($dynamicVars as $var) {
                                    $set('var_' . $var['name'], null);
                                }
                            })
                            ->helperText('Only active campaigns are shown'),
                    ]),

                Forms\Components\Section::make('Message Details')
                    ->description('Enter recipient and dynamic variables')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->required()
                            ->tel()
                            ->placeholder('6283821348593 or 083821348593')
                            ->helperText('Enter Indonesian phone number with or without country code')
                            ->validationAttribute('phone number')
                            ->rules(['required', 'string', 'min:10', 'max:15']),

                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (Get $get): bool => filled($get('campaign_id')))
                            ->helperText('From campaign settings'),

                        Forms\Components\Placeholder::make('dynamic_variables_section')
                            ->label('Dynamic Variables')
                            ->content('Fill in the required variables below')
                            ->visible(fn (Get $get): bool => filled($get('campaign_id')) && $this->hasDynamicVariables($get('campaign_id'))),

                        Forms\Components\Group::make()
                            ->schema(function (Get $get): array {
                                $campaignId = $get('campaign_id');
                                if (!$campaignId) {
                                    return [];
                                }

                                $campaign = WhatsAppCampaign::find($campaignId);
                                if (!$campaign) {
                                    return [];
                                }

                                $dynamicVars = $campaign->getDynamicVariables();
                                $fields = [];

                                foreach ($dynamicVars as $var) {
                                    $fields[] = Forms\Components\TextInput::make('var_' . $var['name'])
                                        ->label($var['label'] ?? $var['name'])
                                        ->required($var['required'] ?? false)
                                        ->default($var['default_value'] ?? null)
                                        ->reactive()
                                        ->placeholder('Enter ' . ($var['label'] ?? $var['name']));
                                }

                                return $fields;
                            })
                            ->columns(2)
                            ->visible(fn (Get $get): bool => filled($get('campaign_id'))),
                    ]),

                Forms\Components\Section::make('Preview')
                    ->description('Live preview of your message')
                    ->schema([
                        Forms\Components\Placeholder::make('message_preview')
                            ->label('Message Preview')
                            ->content(function (Get $get): string {
                                $campaignId = $get('campaign_id');
                                if (!$campaignId) {
                                    return 'Select a campaign to see preview';
                                }

                                $campaign = WhatsAppCampaign::find($campaignId);
                                if (!$campaign) {
                                    return 'Campaign not found';
                                }

                                // Collect dynamic variables
                                $dynamicValues = [];
                                foreach ($campaign->getDynamicVariables() as $var) {
                                    $value = $get('var_' . $var['name']);
                                    $dynamicValues[$var['name']] = $value ?: '[' . $var['name'] . ']';
                                }

                                $parser = new TemplateParserService();
                                try {
                                    $allVars = $parser->mergeVariables($campaign, $dynamicValues);
                                    return $parser->parseTemplate($campaign->template, $allVars);
                                } catch (\Exception $e) {
                                    return 'Error: ' . $e->getMessage();
                                }
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    protected function hasDynamicVariables(?string $campaignId): bool
    {
        if (!$campaignId) {
            return false;
        }

        $campaign = WhatsAppCampaign::find($campaignId);
        return $campaign && !empty($campaign->getDynamicVariables());
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $campaign = WhatsAppCampaign::find($data['campaign_id']);
        if (!$campaign) {
            Notification::make()
                ->title('Campaign not found')
                ->danger()
                ->send();
            return;
        }

        // Collect dynamic variables
        $dynamicValues = [];
        foreach ($campaign->getDynamicVariables() as $var) {
            $value = $data['var_' . $var['name']] ?? null;
            if ($var['required'] && empty($value)) {
                Notification::make()
                    ->title('Missing required variable: ' . $var['label'])
                    ->danger()
                    ->send();
                return;
            }
            $dynamicValues[$var['name']] = $value;
        }

        $parser = new TemplateParserService();
        $whatsappService = new WhatsAppService();

        try {
            // Parse the message
            $message = $parser->parseCampaignTemplate($campaign, $dynamicValues);

            // Send the message
            DB::beginTransaction();

            $result = $whatsappService->send($data['phone_number'], $message);

            // Save to database
            $whatsappMessage = WhatsAppMessage::create([
                'phone_number' => $data['phone_number'],
                'message' => $message,
                'campaign_id' => $campaign->id,
                'variables_used' => array_merge($campaign->getStaticVariables(), $dynamicValues),
                'status' => 'sent',
                'sent_at' => now(),
                'created_by' => Auth::id(),
                'sent_by' => Auth::id(),
            ]);

            // Increment campaign usage
            $campaign->incrementUsage();

            DB::commit();

            Notification::make()
                ->title('Message sent successfully!')
                ->body('WhatsApp message has been sent to ' . $data['phone_number'])
                ->success()
                ->send();

            // Reset form
            $this->form->fill();

        } catch (\Exception $e) {
            DB::rollBack();

            // Save failed message
            WhatsAppMessage::create([
                'phone_number' => $data['phone_number'],
                'message' => $message ?? '',
                'campaign_id' => $campaign->id,
                'variables_used' => array_merge($campaign->getStaticVariables(), $dynamicValues),
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_by' => Auth::id(),
            ]);

            Notification::make()
                ->title('Failed to send message')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('send')
                ->label('Send Message')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->submit('send'),
        ];
    }
}
