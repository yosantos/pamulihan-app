<?php

namespace App\Filament\Pages;

use App\Models\FamilyIdCardSettings;
use App\Models\WhatsAppCampaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions;

class FamilyIdCardSettingsPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.family-id-card-settings-page';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FamilyIdCardSettings::get();

        $this->form->fill([
            'registration_campaign_id' => $settings->registration_campaign_id,
            'rejection_campaign_id' => $settings->rejection_campaign_id,
            'completion_campaign_id' => $settings->completion_campaign_id,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('family_id_card_settings.section.title'))
                    ->description(__('family_id_card_settings.section.description'))
                    ->schema([
                        Forms\Components\Select::make('registration_campaign_id')
                            ->label(__('family_id_card_settings.fields.registration_campaign'))
                            ->options(fn () => WhatsAppCampaign::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder(__('family_id_card_settings.placeholders.select_campaign'))
                            ->helperText(__('family_id_card_settings.helpers.registration_campaign'))
                            ->columnSpanFull(),

                        Forms\Components\Select::make('rejection_campaign_id')
                            ->label(__('family_id_card_settings.fields.rejection_campaign'))
                            ->options(fn () => WhatsAppCampaign::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder(__('family_id_card_settings.placeholders.select_campaign'))
                            ->helperText(__('family_id_card_settings.helpers.rejection_campaign'))
                            ->columnSpanFull(),

                        Forms\Components\Select::make('completion_campaign_id')
                            ->label(__('family_id_card_settings.fields.completion_campaign'))
                            ->options(fn () => WhatsAppCampaign::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder(__('family_id_card_settings.placeholders.select_campaign'))
                            ->helperText(__('family_id_card_settings.helpers.completion_campaign'))
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('family_id_card_settings.actions.save'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = FamilyIdCardSettings::get();
        $settings->update($data);

        Notification::make()
            ->success()
            ->title(__('family_id_card_settings.notifications.saved'))
            ->body(__('family_id_card_settings.notifications.settings_updated'))
            ->send();
    }

    public static function getNavigationLabel(): string
    {
        return __('family_id_card_settings.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.settings');
    }

    public function getTitle(): string
    {
        return __('family_id_card_settings.title');
    }

    public function getHeading(): string
    {
        return __('family_id_card_settings.heading');
    }
}
