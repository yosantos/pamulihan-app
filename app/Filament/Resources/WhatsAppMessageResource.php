<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppMessageResource\Pages;
use App\Models\WhatsAppMessage;
use App\Services\WhatsAppService;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class WhatsAppMessageResource extends Resource
{
    protected static ?string $model = WhatsAppMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('whatsapp_message.navigation');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('whatsapp_message.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('whatsapp_message.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('whatsapp_message.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('whatsapp_message.sections.message_details.title'))
                    ->schema([
                        Forms\Components\Toggle::make('use_campaign')
                            ->label(__('whatsapp_message.fields.use_campaign'))
                            ->helperText(__('whatsapp_message.helpers.use_campaign'))
                            ->live()
                            ->default(fn () => request()->has('campaign'))
                            ->visible(fn (string $operation): bool => $operation === 'create'),

                        Forms\Components\Select::make('campaign_id')
                            ->label(__('whatsapp_message.fields.campaign'))
                            ->relationship('campaign', 'name', fn ($query) => $query->where('is_active', true))
                            ->searchable()
                            ->preload()
                            ->required(fn (Forms\Get $get) => $get('use_campaign'))
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $campaign = \App\Models\WhatsAppCampaign::find($state);
                                    if ($campaign) {
                                        // Get dynamic variables and set up the form
                                        $variables = $campaign->getDynamicVariables();
                                        $set('_campaign_variables', $variables);

                                        // Preview the message with placeholders
                                        $preview = $campaign->template;
                                        $preview = str_replace('[Name_Company]', $campaign->company_name, $preview);
                                        $set('message', $preview);
                                    }
                                }
                            })
                            ->visible(fn (Forms\Get $get, string $operation): bool =>
                                $operation === 'create' && $get('use_campaign')
                            ),

                        Forms\Components\Placeholder::make('campaign_info')
                            ->label(__('whatsapp_message.campaign_info.company'))
                            ->content(function (Forms\Get $get) {
                                if (!$get('campaign_id')) {
                                    return __('whatsapp_message.campaign_info.select_to_see_details');
                                }

                                $campaign = \App\Models\WhatsAppCampaign::find($get('campaign_id'));
                                if (!$campaign) {
                                    return __('whatsapp_message.campaign_info.not_found');
                                }

                                $html = '<div class="space-y-2">';
                                $html .= '<div><span class="font-semibold">' . __('whatsapp_message.campaign_info.company') . ':</span> ' . $campaign->company_name . '</div>';

                                if (!empty($campaign->description)) {
                                    $html .= '<div><span class="font-semibold">' . __('whatsapp_message.campaign_info.description') . ':</span> ' . $campaign->description . '</div>';
                                }

                                if (!empty($campaign->variables)) {
                                    $html .= '<div><span class="font-semibold">' . __('whatsapp_message.campaign_info.required_variables') . ':</span> ';
                                    $html .= '<span class="inline-flex gap-1 flex-wrap">';
                                    foreach ($campaign->variables as $var) {
                                        $html .= '<span class="inline-flex px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">[' . $var . ']</span>';
                                    }
                                    $html .= '</span></div>';
                                }

                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->visible(fn (Forms\Get $get, string $operation): bool =>
                                $operation === 'create' && $get('use_campaign') && $get('campaign_id')
                            ),

                        Forms\Components\TextInput::make('phone_number')
                            ->label(__('whatsapp_message.fields.phone_number'))
                            ->required()
                            ->tel()
                            ->maxLength(20)
                            ->placeholder(__('whatsapp_message.placeholders.phone_number'))
                            ->helperText(__('whatsapp_message.helpers.phone_number'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state) {
                                    // Auto-format to 62xxx format
                                    $cleaned = preg_replace('/[\s\-\+]/', '', $state);
                                    if (str_starts_with($cleaned, '0')) {
                                        $formatted = '62' . ltrim($cleaned, '0');
                                        $set('phone_number', $formatted);
                                    }
                                }
                            })
                            ->rules([
                                'required',
                                'string',
                                'max:20',
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        $cleaned = preg_replace('/[\s\-\+]/', '', $value);
                                        if (!preg_match('/^(62|0)\d{8,12}$/', $cleaned)) {
                                            $fail('The phone number must be a valid Indonesian phone number.');
                                        }
                                    };
                                },
                            ])
                            ->disabled(fn (string $operation): bool => $operation !== 'create'),

                        Forms\Components\Textarea::make('message')
                            ->label(__('whatsapp_message.fields.message'))
                            ->required(fn (Forms\Get $get): bool => !$get('use_campaign'))
                            ->rows(5)
                            ->minLength(1)
                            ->maxLength(1000)
                            ->placeholder(__('whatsapp_message.placeholders.message'))
                            ->helperText(fn (?string $state): string =>
                                __('whatsapp_message.helpers.character_count', ['count' => strlen($state ?? '')])
                            )
                            ->live(onBlur: true)
                            ->disabled(fn (string $operation, Forms\Get $get): bool =>
                                $operation !== 'create' || ($operation === 'create' && $get('use_campaign'))
                            )
                            ->dehydrated(false)
                            ->visible(fn (Forms\Get $get, string $operation): bool =>
                                $operation !== 'create' || !$get('use_campaign')
                            ),

                        Forms\Components\Section::make(__('whatsapp_message.sections.campaign_variables.title'))
                            ->schema(function (Forms\Get $get) {
                                $campaignId = $get('campaign_id');

                                if (!$campaignId) {
                                    return [];
                                }

                                $campaign = \App\Models\WhatsAppCampaign::find($campaignId);

                                if (!$campaign || empty($campaign->variables)) {
                                    return [];
                                }

                                $fields = [];

                                foreach ($campaign->variables as $variable) {
                                    $fields[] = Forms\Components\TextInput::make('variable_' . $variable)
                                        ->label(ucwords(str_replace('_', ' ', $variable)))
                                        ->placeholder(__('whatsapp_message.placeholders.variable_value', ['variable' => $variable]))
                                        ->required()
                                        ->live();
                                }

                                return $fields;
                            })
                            ->visible(fn (Forms\Get $get, string $operation): bool =>
                                $operation === 'create' && $get('use_campaign') && $get('campaign_id')
                            ),

                        Forms\Components\Placeholder::make('message_preview')
                            ->label(__('whatsapp_message.preview.label'))
                            ->content(function (Forms\Get $get) {
                                if (!$get('campaign_id')) {
                                    return __('whatsapp_message.preview.no_preview');
                                }

                                $campaign = \App\Models\WhatsAppCampaign::find($get('campaign_id'));

                                if (!$campaign) {
                                    return __('whatsapp_message.campaign_info.not_found');
                                }

                                $message = $campaign->template;

                                // Replace company name
                                $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                // Replace variables
                                if (!empty($campaign->variables)) {
                                    foreach ($campaign->variables as $variable) {
                                        $value = $get('variable_' . $variable);
                                        if ($value) {
                                            $message = str_replace('[' . $variable . ']', $value, $message);
                                        }
                                    }
                                }

                                return new \Illuminate\Support\HtmlString(
                                    '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                    '<pre class="whitespace-pre-wrap text-sm">' . e($message) . '</pre>' .
                                    '</div>'
                                );
                            })
                            ->visible(fn (Forms\Get $get, string $operation): bool =>
                                $operation === 'create' && $get('use_campaign') && $get('campaign_id')
                            ),
                    ])
                    ->columns(1),

                Forms\Components\Section::make(__('whatsapp_message.sections.campaign_information.title'))
                    ->schema([
                        Forms\Components\TextInput::make('campaign.name')
                            ->label(__('whatsapp_message.fields.campaign_name'))
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder(__('whatsapp_message.placeholders.manual_message'))
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('view_campaign')
                                    ->icon('heroicon-o-eye')
                                    ->url(fn (WhatsAppMessage $record) =>
                                        $record->campaign_id
                                            ? WhatsAppCampaignResource::getUrl('view', ['record' => $record->campaign_id])
                                            : null
                                    )
                                    ->openUrlInNewTab()
                                    ->visible(fn (WhatsAppMessage $record = null): bool => $record?->campaign_id !== null)
                            ),

                        Forms\Components\Placeholder::make('variables_display')
                            ->label(__('whatsapp_message.fields.variables_used'))
                            ->content(function (WhatsAppMessage $record = null) {
                                if (!$record || !$record->variables_used) {
                                    return __('whatsapp_message.campaign_info.no_variables');
                                }

                                $html = '<div class="space-y-1">';
                                foreach ($record->variables_used as $key => $value) {
                                    $html .= '<div class="flex gap-2">';
                                    $html .= '<span class="font-semibold text-gray-700 dark:text-gray-300">[' . e($key) . ']:</span>';
                                    $html .= '<span class="text-gray-600 dark:text-gray-400">' . e($value) . '</span>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->visible(fn (WhatsAppMessage $record = null): bool =>
                                $record?->variables_used !== null && !empty($record->variables_used)
                            ),

                        Forms\Components\Textarea::make('campaign.template')
                            ->label(__('whatsapp_message.fields.original_template'))
                            ->disabled()
                            ->rows(3)
                            ->dehydrated(false)
                            ->visible(fn (WhatsAppMessage $record = null): bool => $record?->campaign_id !== null),
                    ])
                    ->columns(1)
                    ->visible(fn (string $operation, WhatsAppMessage $record = null): bool =>
                        $operation !== 'create' && $record?->campaign_id !== null
                    ),

                Forms\Components\Section::make(__('whatsapp_message.sections.status_information.title'))
                    ->schema([
                        Forms\Components\TextInput::make('status')
                            ->label(__('whatsapp_message.fields.status'))
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('retry_count')
                            ->label(__('whatsapp_message.fields.retry_count'))
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText(__('whatsapp_message.helpers.retry_count')),

                        Forms\Components\Textarea::make('error_message')
                            ->label(__('whatsapp_message.fields.error_message'))
                            ->disabled()
                            ->rows(3)
                            ->visible(fn (WhatsAppMessage $record = null): bool =>
                                $record?->isFailed() ?? false
                            ),

                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label(__('whatsapp_message.fields.sent_at'))
                            ->disabled()
                            ->displayFormat('d/m/Y H:i:s'),
                    ])
                    ->columns(1)
                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                Forms\Components\Section::make(__('whatsapp_message.sections.user_tracking.title'))
                    ->schema([
                        Forms\Components\TextInput::make('creator.name')
                            ->label(__('whatsapp_message.fields.created_by'))
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder(__('whatsapp_message.placeholders.not_available'))
                            ->helperText(__('whatsapp_message.helpers.created_by_helper')),

                        Forms\Components\TextInput::make('sender.name')
                            ->label(__('whatsapp_message.fields.sent_by'))
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder(__('whatsapp_message.placeholders.not_available'))
                            ->helperText(__('whatsapp_message.helpers.sent_by_helper')),
                    ])
                    ->columns(2)
                    ->visible(fn (string $operation): bool => $operation !== 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('whatsapp_message.columns.phone_number'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip(__('whatsapp_message.tooltips.click_to_copy')),

                Tables\Columns\TextColumn::make('message')
                    ->label(__('whatsapp_message.columns.message_preview'))
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn (WhatsAppMessage $record): string => $record->message)
                    ->wrap(),

                Tables\Columns\TextColumn::make('campaign.name')
                    ->label(__('whatsapp_message.columns.campaign'))
                    ->badge()
                    ->color('blue')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default(__('whatsapp_message.columns.manual'))
                    ->placeholder(__('whatsapp_message.columns.manual')),

                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('whatsapp_message.columns.status'))
                    ->colors([
                        'success' => 'sent',
                        'danger' => 'failed',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'sent',
                        'heroicon-o-x-circle' => 'failed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('retry_count')
                    ->label(__('whatsapp_message.columns.retries'))
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'warning' : 'gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('whatsapp_message.columns.created_by'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default(__('whatsapp_message.placeholders.not_available'))
                    ->placeholder(__('whatsapp_message.placeholders.not_available')),

                Tables\Columns\TextColumn::make('sender.name')
                    ->label(__('whatsapp_message.columns.sent_by'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default(__('whatsapp_message.placeholders.not_available'))
                    ->placeholder(__('whatsapp_message.placeholders.not_available')),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label(__('whatsapp_message.columns.sent_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('whatsapp_message.columns.created_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('whatsapp_message.filters.status'))
                    ->options([
                        'sent' => __('whatsapp_message.status.sent'),
                        'failed' => __('whatsapp_message.status.failed'),
                    ]),

                Tables\Filters\TernaryFilter::make('campaign_id')
                    ->label(__('whatsapp_message.filters.message_type'))
                    ->placeholder(__('whatsapp_message.filters.all_messages'))
                    ->trueLabel(__('whatsapp_message.filters.campaign_messages'))
                    ->falseLabel(__('whatsapp_message.filters.manual_messages'))
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('campaign_id'),
                        false: fn (Builder $query) => $query->whereNull('campaign_id'),
                    ),

                Tables\Filters\Filter::make('sent_at')
                    ->form([
                        Forms\Components\DatePicker::make('sent_from')
                            ->label(__('whatsapp_message.filters.sent_from')),
                        Forms\Components\DatePicker::make('sent_until')
                            ->label(__('whatsapp_message.filters.sent_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['sent_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('sent_at', '>=', $date),
                            )
                            ->when(
                                $data['sent_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('sent_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('resend')
                    ->label(__('whatsapp_message.actions.resend'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(__('whatsapp_message.resend.heading'))
                    ->modalDescription(fn (WhatsAppMessage $record): string =>
                        __('whatsapp_message.resend.description', ['phone' => $record->phone_number])
                    )
                    ->modalSubmitActionLabel(__('whatsapp_message.resend.submit'))
                    ->action(function (WhatsAppMessage $record) {
                        $whatsappService = app(WhatsAppService::class);

                        try {
                            // Increment retry count
                            $record->incrementRetryCount();

                            // Attempt to send the message
                            $whatsappService->send($record->phone_number, $record->message);

                            // Mark as sent with current user
                            $record->markAsSent(Filament::auth()->id());

                            Notification::make()
                                ->success()
                                ->title(__('whatsapp_message.notifications.resend_success.title'))
                                ->body(__('whatsapp_message.notifications.resend_success.body', ['phone' => $record->phone_number]))
                                ->send();

                        } catch (\Exception $e) {
                            // Mark as failed with error
                            $record->markAsFailed($e->getMessage());

                            Notification::make()
                                ->danger()
                                ->title(__('whatsapp_message.notifications.resend_failed.title'))
                                ->body(__('whatsapp_message.notifications.resend_failed.body', ['message' => $e->getMessage()]))
                                ->persistent()
                                ->send();
                        }
                    })
                    ->visible(fn (WhatsAppMessage $record): bool => $record->isFailed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('resendBulk')
                        ->label(__('whatsapp_message.actions.resend_selected'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading(__('whatsapp_message.resend.bulk_heading'))
                        ->modalDescription(__('whatsapp_message.resend.bulk_description'))
                        ->modalSubmitActionLabel(__('whatsapp_message.resend.bulk_submit'))
                        ->action(function (Collection $records) {
                            $whatsappService = app(WhatsAppService::class);
                            $successCount = 0;
                            $failCount = 0;
                            $totalRecords = $records->count();

                            foreach ($records as $record) {
                                // Only process failed messages
                                if (!$record->isFailed()) {
                                    continue;
                                }

                                try {
                                    // Increment retry count
                                    $record->incrementRetryCount();

                                    // Attempt to send the message
                                    $whatsappService->send($record->phone_number, $record->message);

                                    // Mark as sent with current user
                                    $record->markAsSent(Filament::auth()->id());

                                    $successCount++;
                                } catch (\Exception $e) {
                                    // Mark as failed with error
                                    $record->markAsFailed($e->getMessage());

                                    $failCount++;
                                }
                            }

                            // Show summary notification
                            if ($successCount > 0 && $failCount === 0) {
                                Notification::make()
                                    ->success()
                                    ->title(__('whatsapp_message.notifications.bulk_resend_all_success.title'))
                                    ->body(__('whatsapp_message.notifications.bulk_resend_all_success.body', ['count' => $successCount]))
                                    ->send();
                            } elseif ($successCount > 0 && $failCount > 0) {
                                Notification::make()
                                    ->warning()
                                    ->title(__('whatsapp_message.notifications.bulk_resend_partial.title'))
                                    ->body(__('whatsapp_message.notifications.bulk_resend_partial.body', [
                                        'success' => $successCount,
                                        'failed' => $failCount
                                    ]))
                                    ->persistent()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->danger()
                                    ->title(__('whatsapp_message.notifications.bulk_resend_all_failed.title'))
                                    ->body(__('whatsapp_message.notifications.bulk_resend_all_failed.body', ['count' => $failCount]))
                                    ->persistent()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWhatsAppMessages::route('/'),
            'create' => Pages\CreateWhatsAppMessage::route('/create'),
            'view' => Pages\ViewWhatsAppMessage::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::sent()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
