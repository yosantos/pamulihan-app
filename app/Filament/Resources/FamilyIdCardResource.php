<?php

namespace App\Filament\Resources;

use App\Enums\CertificateStatus;
use App\Filament\Resources\FamilyIdCardResource\Pages;
use App\Models\FamilyIdCard;
use App\Models\WhatsAppCampaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FamilyIdCardResource extends Resource
{
    protected static ?string $model = FamilyIdCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    protected static ?int $navigationSort = 5;

    /**
     * Get the navigation group.
     */
    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.civil_registration');
    }

    /**
     * Get the navigation label.
     */
    public static function getNavigationLabel(): string
    {
        return __('family_id_card.navigation');
    }

    /**
     * Get the model label.
     */
    public static function getModelLabel(): string
    {
        return __('family_id_card.model_label');
    }

    /**
     * Get the plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        return __('family_id_card.plural_model_label');
    }

    /**
     * Get the navigation badge (showing total count).
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Get the navigation badge color.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('family_id_card.sections.registration_information.title'))
                    ->description(__('family_id_card.sections.registration_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('no_registration')
                                    ->label(__('family_id_card.fields.no_registration'))
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(__('family_id_card.placeholders.no_registration'))
                                    ->helperText(__('family_id_card.helpers.no_registration'))
                                    ->visible(fn ($record) => $record !== null)
                                    ->default(fn ($record) => $record?->no_registration),

                                Forms\Components\DatePicker::make('date')
                                    ->label(__('family_id_card.fields.date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->default(now())
                                    ->maxDate(now())
                                    ->helperText(__('family_id_card.helpers.date'))
                                    ->live()
                                    ->columnSpan(fn ($record) => $record === null ? 2 : 1),

                                Forms\Components\DatePicker::make('due_date')
                                    ->label(__('family_id_card.fields.due_date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText(__('family_id_card.helpers.due_date'))
                                    ->visible(fn ($record) => $record !== null)
                                    ->default(fn ($record) => $record?->due_date)
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('family_id_card.fields.status'))
                                    ->options([
                                        CertificateStatus::ON_PROGRESS->value => CertificateStatus::ON_PROGRESS->getLabel(),
                                        CertificateStatus::COMPLETED->value => CertificateStatus::COMPLETED->getLabel(),
                                        CertificateStatus::REJECTED->value => CertificateStatus::REJECTED->getLabel(),
                                    ])
                                    ->default(CertificateStatus::ON_PROGRESS->value)
                                    ->required()
                                    ->native(false)
                                    ->helperText(__('family_id_card.helpers.status'))
                                    ->disabled(fn ($record) => $record === null)
                                    ->columnSpan(1),

                                Forms\Components\Select::make('person_in_charge_id')
                                    ->label(__('family_id_card.fields.person_in_charge'))
                                    ->relationship('personInCharge', 'name')
                                    ->searchable(['name', 'email'])
                                    ->preload()
                                    ->placeholder(__('family_id_card.placeholders.person_in_charge'))
                                    ->helperText(__('family_id_card.helpers.person_in_charge'))
                                    ->native(false)
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make(__('family_id_card.sections.applicant_information.title'))
                    ->description(__('family_id_card.sections.applicant_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('family_id_card.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('family_id_card.placeholders.name'))
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('national_id_number')
                                    ->label(__('family_id_card.fields.national_id_number'))
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder(__('family_id_card.placeholders.national_id_number'))
                                    ->helperText(__('family_id_card.helpers.national_id_number'))
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('phone_number')
                                    ->label(__('family_id_card.fields.phone_number'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder(__('family_id_card.placeholders.phone_number'))
                                    ->helperText(__('family_id_card.helpers.phone_number'))
                                    ->rule('regex:/^(08|62)\d{8,13}$/')
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                Forms\Components\Select::make('village_id')
                                    ->label(__('family_id_card.fields.village'))
                                    ->relationship('village', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder(__('family_id_card.placeholders.village'))
                                    ->native(false)
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label(__('family_id_card.fields.address'))
                            ->required()
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder(__('family_id_card.placeholders.address')),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make(__('family_id_card.sections.admin_section.title'))
                    ->description(__('family_id_card.sections.admin_section.description'))
                    ->schema([
                        Forms\Components\Textarea::make('note')
                            ->label(__('family_id_card.fields.note'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder(__('family_id_card.placeholders.note')),

                        Forms\Components\Textarea::make('admin_memo')
                            ->label(__('family_id_card.fields.admin_memo'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder(__('family_id_card.placeholders.admin_memo'))
                            ->helperText(__('family_id_card.helpers.admin_memo')),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label(__('family_id_card.fields.rejection_reason'))
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder(__('family_id_card.placeholders.rejection_reason'))
                            ->visible(fn ($record) => $record && $record->status === CertificateStatus::REJECTED),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make(__('family_id_card.sections.whatsapp_preview.title'))
                    ->description(__('family_id_card.sections.whatsapp_preview.description'))
                    ->schema([
                        Forms\Components\Placeholder::make('whatsapp_preview')
                            ->label(__('family_id_card.sections.whatsapp_preview.label'))
                            ->content(function (Forms\Get $get) {
                                $settings = \App\Models\FamilyIdCardSettings::get();

                                if (!$settings->registration_campaign_id) {
                                    return __('family_id_card.sections.whatsapp_preview.no_campaign');
                                }

                                $campaign = WhatsAppCampaign::find($settings->registration_campaign_id);

                                if (!$campaign) {
                                    return __('family_id_card.sections.whatsapp_preview.campaign_not_found');
                                }

                                $message = $campaign->template;
                                $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                // Calculate due date (14 days from date)
                                $date = $get('date') ? \Carbon\Carbon::parse($get('date')) : now();
                                $dueDate = $date->copy()->addDays(14);

                                if (!empty($campaign->variables)) {
                                    foreach ($campaign->variables as $variable) {
                                        $value = match($variable) {
                                            'name' => $get('name') ?: '[' . __('family_id_card.fields.name') . ']',
                                            'phone_number' => $get('phone_number') ?: '[' . __('family_id_card.fields.phone_number') . ']',
                                            'no_registration' => '[' . __('family_id_card.placeholders.no_registration') . ']',
                                            'date' => $date->format('d M Y'),
                                            'due_date' => $dueDate->format('d M Y'),
                                            default => null,
                                        };

                                        if ($value !== null) {
                                            $message = str_replace('[' . $variable . ']', $value, $message);
                                        }
                                    }
                                }

                                return new \Illuminate\Support\HtmlString(
                                    '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                    '<pre class="whitespace-pre-wrap break-words text-sm overflow-auto">' . e($message) . '</pre>' .
                                    '</div>'
                                );
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->visible(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_registration')
                    ->label(__('family_id_card.columns.no_registration'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-hashtag'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('family_id_card.columns.status'))
                    ->badge()
                    ->color(fn (CertificateStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (CertificateStatus $state): string => $state->getLabel())
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('family_id_card.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 30) {
                            return $state;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('family_id_card.columns.date'))
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('family_id_card.columns.due_date'))
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn (FamilyIdCard $record): string =>
                        $record->due_date->isPast() && $record->status === CertificateStatus::ON_PROGRESS
                            ? 'danger'
                            : 'gray'
                    )
                    ->tooltip(fn (FamilyIdCard $record): ?string =>
                        $record->due_date->isPast() && $record->status === CertificateStatus::ON_PROGRESS
                            ? __('family_id_card.table.overdue')
                            : null
                    ),

                Tables\Columns\TextColumn::make('national_id_number')
                    ->label(__('family_id_card.columns.national_id_number'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('family_id_card.columns.phone_number'))
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage(__('family_id_card.table.phone_copied'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('family_id_card.columns.village'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('personInCharge.name')
                    ->label(__('family_id_card.columns.person_in_charge'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-user-circle')
                    ->placeholder(__('family_id_card.table.not_assigned')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('family_id_card.columns.created_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('family_id_card.columns.updated_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('family_id_card.filters.status'))
                    ->options([
                        CertificateStatus::ON_PROGRESS->value => CertificateStatus::ON_PROGRESS->getLabel(),
                        CertificateStatus::COMPLETED->value => CertificateStatus::COMPLETED->getLabel(),
                        CertificateStatus::REJECTED->value => CertificateStatus::REJECTED->getLabel(),
                    ])
                    ->native(false)
                    ->placeholder(__('family_id_card.filters.all_statuses')),

                Tables\Filters\SelectFilter::make('person_in_charge_id')
                    ->label(__('family_id_card.filters.person_in_charge'))
                    ->relationship('personInCharge', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder(__('family_id_card.filters.all_users')),

                Tables\Filters\SelectFilter::make('village_id')
                    ->label(__('family_id_card.filters.village'))
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder(__('family_id_card.filters.all_villages')),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('family_id_card.filters.date_from'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('family_id_card.filters.date_until'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('family_id_card.filter_indicators.date_from', [
                                    'date' => \Carbon\Carbon::parse($data['date_from'])->format('d M Y')
                                ])
                            )->removeField('date_from');
                        }

                        if ($data['date_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('family_id_card.filter_indicators.date_until', [
                                    'date' => \Carbon\Carbon::parse($data['date_until'])->format('d M Y')
                                ])
                            )->removeField('date_until');
                        }

                        return $indicators;
                    }),

                Tables\Filters\Filter::make('overdue')
                    ->label(__('family_id_card.filters.overdue'))
                    ->query(fn (Builder $query): Builder =>
                        $query->where('due_date', '<', now())
                            ->where('status', CertificateStatus::ON_PROGRESS)
                    )
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('family_id_card.actions.view'))
                        ->color('primary'),

                    Tables\Actions\EditAction::make()
                        ->label(__('family_id_card.actions.edit'))
                        ->color('warning'),

                    Tables\Actions\Action::make('reject')
                        ->label(__('family_id_card.actions.reject'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Placeholder::make('applicant_info')
                                ->label(__('family_id_card.reject.applicant_info'))
                                ->content(fn (FamilyIdCard $record) =>
                                    __('family_id_card.reject.applicant_details', [
                                        'name' => $record->name,
                                        'phone' => $record->phone_number,
                                    ])
                                )
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('rejection_reason')
                                ->label(__('family_id_card.reject.reason'))
                                ->required()
                                ->rows(3)
                                ->maxLength(65535)
                                ->placeholder(__('family_id_card.reject.reason_placeholder'))
                                ->live(debounce: 500)
                                ->columnSpanFull(),

                            Forms\Components\Placeholder::make('message_preview')
                                ->label(__('family_id_card.reject.message_preview'))
                                ->content(function (Forms\Get $get, FamilyIdCard $record) {
                                    $settings = \App\Models\FamilyIdCardSettings::get();

                                    if (!$settings->rejection_campaign_id) {
                                        return __('family_id_card.reject.no_campaign_configured');
                                    }

                                    $campaign = WhatsAppCampaign::find($settings->rejection_campaign_id);

                                    if (!$campaign) {
                                        return __('family_id_card.reject.campaign_not_found');
                                    }

                                    $message = $campaign->template;
                                    $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                    if (!empty($campaign->variables)) {
                                        foreach ($campaign->variables as $variable) {
                                            $value = match($variable) {
                                                'name' => $record->name,
                                                'phone_number' => $record->phone_number,
                                                'no_registration' => $record->no_registration,
                                                'rejection_reason' => $get('rejection_reason') ?: '[' . __('family_id_card.reject.reason') . ']',
                                                default => null,
                                            };

                                            if ($value !== null) {
                                                $message = str_replace('[' . $variable . ']', $value, $message);
                                            }
                                        }
                                    }

                                    return new \Illuminate\Support\HtmlString(
                                        '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                        '<pre class="whitespace-pre-wrap break-words text-sm overflow-auto">' . e($message) . '</pre>' .
                                        '</div>'
                                    );
                                })
                                ->columnSpanFull(),
                        ])
                        ->action(function (array $data, FamilyIdCard $record) {
                            // Update status and rejection reason
                            $record->update([
                                'status' => CertificateStatus::REJECTED,
                                'rejection_reason' => $data['rejection_reason'],
                            ]);

                            // Get settings and send WhatsApp notification
                            $settings = \App\Models\FamilyIdCardSettings::get();
                            $campaign = $settings->rejection_campaign_id ? WhatsAppCampaign::find($settings->rejection_campaign_id) : null;

                            if ($campaign) {
                                $variables = [];
                                if (!empty($campaign->variables)) {
                                    foreach ($campaign->variables as $variable) {
                                        $value = match($variable) {
                                            'name' => $record->name,
                                            'phone_number' => $record->phone_number,
                                            'no_registration' => $record->no_registration,
                                            'rejection_reason' => $data['rejection_reason'],
                                            default => null,
                                        };

                                        if ($value !== null) {
                                            $variables[$variable] = $value;
                                        }
                                    }
                                }

                                $result = \App\Facades\Campaign::send(
                                    $campaign->name,
                                    $record->phone_number,
                                    $variables
                                );

                                if ($result['success']) {
                                    Notification::make()
                                        ->success()
                                        ->title(__('family_id_card.notifications.rejected_and_notified'))
                                        ->body(__('family_id_card.notifications.rejection_sent', [
                                            'registration' => $record->no_registration
                                        ]))
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->warning()
                                        ->title(__('family_id_card.notifications.rejected_notification_failed'))
                                        ->body(__('family_id_card.notifications.rejection_status_updated'))
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->success()
                                    ->title(__('family_id_card.notifications.status_updated'))
                                    ->body(__('family_id_card.notifications.marked_rejected'))
                                    ->send();
                            }
                        })
                        ->modalHeading(__('family_id_card.reject.modal_heading'))
                        ->modalDescription(__('family_id_card.reject.modal_description'))
                        ->modalSubmitActionLabel(__('family_id_card.reject.modal_submit'))
                        ->modalWidth('lg')
                        ->visible(fn (FamilyIdCard $record): bool =>
                            $record->status === CertificateStatus::ON_PROGRESS
                        ),

                    Tables\Actions\Action::make('mark_completed')
                        ->label(__('family_id_card.actions.mark_completed'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\Placeholder::make('applicant_info')
                                ->label(__('family_id_card.complete.applicant_info'))
                                ->content(fn (FamilyIdCard $record) =>
                                    __('family_id_card.complete.applicant_details', [
                                        'name' => $record->name,
                                        'phone' => $record->phone_number,
                                    ])
                                )
                                ->columnSpanFull(),

                            Forms\Components\Placeholder::make('message_preview')
                                ->label(__('family_id_card.complete.message_preview'))
                                ->content(function (FamilyIdCard $record) {
                                    $settings = \App\Models\FamilyIdCardSettings::get();

                                    if (!$settings->completion_campaign_id) {
                                        return __('family_id_card.complete.no_campaign_configured');
                                    }

                                    $campaign = WhatsAppCampaign::find($settings->completion_campaign_id);

                                    if (!$campaign) {
                                        return __('family_id_card.complete.campaign_not_found');
                                    }

                                    $message = $campaign->template;
                                    $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                    if (!empty($campaign->variables)) {
                                        foreach ($campaign->variables as $variable) {
                                            $value = match($variable) {
                                                'name' => $record->name,
                                                'phone_number' => $record->phone_number,
                                                'no_registration' => $record->no_registration,
                                                'date' => $record->date->format('d M Y'),
                                                'due_date' => $record->due_date->format('d M Y'),
                                                default => null,
                                            };

                                            if ($value !== null) {
                                                $message = str_replace('[' . $variable . ']', $value, $message);
                                            }
                                        }
                                    }

                                    return new \Illuminate\Support\HtmlString(
                                        '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                        '<pre class="whitespace-pre-wrap break-words text-sm overflow-auto">' . e($message) . '</pre>' .
                                        '</div>'
                                    );
                                })
                                ->columnSpanFull(),
                        ])
                        ->action(function (array $data, FamilyIdCard $record) {
                            // Update status to completed
                            $record->update([
                                'status' => CertificateStatus::COMPLETED,
                            ]);

                            // Get settings and send WhatsApp notification
                            $settings = \App\Models\FamilyIdCardSettings::get();
                            $campaign = $settings->completion_campaign_id ? WhatsAppCampaign::find($settings->completion_campaign_id) : null;

                            if ($campaign) {
                                $variables = [];
                                if (!empty($campaign->variables)) {
                                    foreach ($campaign->variables as $variable) {
                                        $value = match($variable) {
                                            'name' => $record->name,
                                            'phone_number' => $record->phone_number,
                                            'no_registration' => $record->no_registration,
                                            'date' => $record->date->format('d M Y'),
                                            'due_date' => $record->due_date->format('d M Y'),
                                            default => null,
                                        };

                                        if ($value !== null) {
                                            $variables[$variable] = $value;
                                        }
                                    }
                                }

                                $result = \App\Facades\Campaign::send(
                                    $campaign->name,
                                    $record->phone_number,
                                    $variables
                                );

                                if ($result['success']) {
                                    Notification::make()
                                        ->success()
                                        ->title(__('family_id_card.notifications.completed_and_notified'))
                                        ->body(__('family_id_card.notifications.completion_sent', [
                                            'registration' => $record->no_registration
                                        ]))
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->warning()
                                        ->title(__('family_id_card.notifications.completed_notification_failed'))
                                        ->body(__('family_id_card.notifications.completion_status_updated'))
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->success()
                                    ->title(__('family_id_card.notifications.status_updated'))
                                    ->body(__('family_id_card.notifications.marked_completed'))
                                    ->send();
                            }
                        })
                        ->modalHeading(__('family_id_card.complete.modal_heading'))
                        ->modalDescription(__('family_id_card.complete.modal_description'))
                        ->modalSubmitActionLabel(__('family_id_card.complete.modal_submit'))
                        ->modalWidth('lg')
                        ->visible(fn (FamilyIdCard $record): bool =>
                            $record->status === CertificateStatus::ON_PROGRESS
                        ),

                    Tables\Actions\Action::make('mark_on_progress')
                        ->label(__('family_id_card.actions.mark_on_progress'))
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading(__('family_id_card.modals.mark_on_progress.heading'))
                        ->modalDescription(__('family_id_card.modals.mark_on_progress.description'))
                        ->modalSubmitActionLabel(__('family_id_card.modals.mark_on_progress.submit'))
                        ->action(function (FamilyIdCard $record) {
                            $record->update(['status' => CertificateStatus::ON_PROGRESS]);
                            Notification::make()
                                ->success()
                                ->title(__('family_id_card.notifications.status_updated'))
                                ->body(__('family_id_card.notifications.marked_on_progress', [
                                    'registration' => $record->no_registration
                                ]))
                                ->send();
                        })
                        ->visible(fn (FamilyIdCard $record): bool =>
                            $record->status !== CertificateStatus::ON_PROGRESS
                        ),

                    Tables\Actions\DeleteAction::make()
                        ->label(__('family_id_card.actions.delete'))
                        ->requiresConfirmation(),
                ])
                    ->label(__('family_id_card.actions.actions'))
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('family_id_card.actions.delete'))
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('family_id_card.actions.create'))
                    ->icon('heroicon-o-plus'),
            ])
            ->emptyStateHeading(__('family_id_card.table.empty_state_heading'))
            ->emptyStateDescription(__('family_id_card.table.empty_state_description'))
            ->emptyStateIcon('heroicon-o-identification');
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
            'index' => Pages\ListFamilyIdCards::route('/'),
            'create' => Pages\CreateFamilyIdCard::route('/create'),
            'view' => Pages\ViewFamilyIdCard::route('/{record}'),
            'edit' => Pages\EditFamilyIdCard::route('/{record}/edit'),
        ];
    }

    /**
     * Get the global search result title.
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name . ' - ' . $record->no_registration;
    }

    /**
     * Get the globally searchable attributes.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'no_registration', 'national_id_number', 'phone_number'];
    }

    /**
     * Get the global search result details.
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('family_id_card.global_search.registration_date') => $record->date->format('d M Y'),
            __('family_id_card.global_search.due_date') => $record->due_date->format('d M Y'),
            __('family_id_card.global_search.status') => $record->status->getLabel(),
        ];
    }
}
