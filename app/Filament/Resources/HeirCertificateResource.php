<?php

namespace App\Filament\Resources;

use App\Enums\CertificateStatus;
use App\Filament\Resources\HeirCertificateResource\Pages;
use App\Models\HeirCertificate;
use App\Models\User;
use App\Models\WhatsAppCampaign;
use App\Services\TemplateVariableParser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class HeirCertificateResource extends Resource
{
    protected static ?string $model = HeirCertificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Certificates';

    protected static ?string $navigationLabel = null;

    protected static ?int $navigationSort = 1;

    /**
     * Get the navigation label.
     */
    public static function getNavigationLabel(): string
    {
        return __('heir_certificate.navigation');
    }

    /**
     * Get the model label.
     */
    public static function getModelLabel(): string
    {
        return __('heir_certificate.model_label');
    }

    /**
     * Get the plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        return __('heir_certificate.plural_model_label');
    }

    /**
     * Get the navigation badge (showing total certificates count).
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
                Forms\Components\Section::make(__('heir_certificate.sections.certificate_information.title'))
                    ->description(__('heir_certificate.sections.certificate_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('formatted_certificate_number')
                                    ->label(__('heir_certificate.fields.certificate_number'))
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(__('heir_certificate.placeholders.certificate_number'))
                                    ->helperText(__('heir_certificate.helpers.certificate_number'))
                                    ->visible(fn ($record) => $record !== null)
                                    ->default(fn ($record) => $record?->formatted_certificate_number),

                                Forms\Components\DatePicker::make('certificate_date')
                                    ->label(__('heir_certificate.fields.certificate_date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->default(now())
                                    ->maxDate(now())
                                    ->helperText(__('heir_certificate.helpers.certificate_date'))
                                    ->columnSpan(fn ($record) => $record === null ? 2 : 1),

                                Forms\Components\Select::make('status')
                                    ->label(__('heir_certificate.fields.status'))
                                    ->options([
                                        CertificateStatus::ON_PROGRESS->value => CertificateStatus::ON_PROGRESS->getLabel(),
                                        CertificateStatus::COMPLETED->value => CertificateStatus::COMPLETED->getLabel(),
                                    ])
                                    ->default(CertificateStatus::ON_PROGRESS->value)
                                    ->required()
                                    ->native(false)
                                    ->helperText(__('heir_certificate.helpers.status'))
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Select::make('person_in_charge_id')
                            ->label(__('heir_certificate.fields.person_in_charge'))
                            ->relationship('personInCharge', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->placeholder(__('heir_certificate.placeholders.person_in_charge'))
                            ->helperText(__('heir_certificate.helpers.person_in_charge'))
                            ->native(false),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('applicant_name')
                                    ->label(__('heir_certificate.fields.applicant_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('heir_certificate.placeholders.applicant_name')),

                                Forms\Components\TextInput::make('phone_number')
                                    ->label(__('heir_certificate.fields.phone_number'))
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder(__('heir_certificate.placeholders.phone_number'))
                                    ->helperText(__('heir_certificate.helpers.phone_number'))
                                    ->rule('regex:/^(08|62)\d{8,13}$/'),
                            ]),

                        Forms\Components\Textarea::make('applicant_address')
                            ->label(__('heir_certificate.fields.applicant_address'))
                            ->required()
                            ->rows(3)
                            ->maxLength(65535)
                            ->placeholder(__('heir_certificate.placeholders.applicant_address')),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make(__('heir_certificate.sections.deceased_information.title'))
                    ->description(__('heir_certificate.sections.deceased_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('deceased_name')
                                    ->label(__('heir_certificate.fields.deceased_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('heir_certificate.placeholders.deceased_name')),

                                Forms\Components\TextInput::make('place_of_death')
                                    ->label(__('heir_certificate.fields.place_of_death'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('heir_certificate.placeholders.place_of_death')),

                                Forms\Components\DatePicker::make('date_of_death')
                                    ->label(__('heir_certificate.fields.date_of_death'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->maxDate(now())
                                    ->helperText(__('heir_certificate.helpers.date_of_death')),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make(__('heir_certificate.sections.heirs_information.title'))
                    ->description(__('heir_certificate.sections.heirs_information.description'))
                    ->schema([
                        Forms\Components\Repeater::make('heirs')
                            ->relationship('heirs')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('heir_name')
                                            ->label(__('heir_certificate.heirs.name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder(__('heir_certificate.placeholders.heir_name'))
                                            ->columnSpan(2),

                                        Forms\Components\Textarea::make('heir_address')
                                            ->label(__('heir_certificate.heirs.address'))
                                            ->rows(2)
                                            ->maxLength(65535)
                                            ->placeholder(__('heir_certificate.placeholders.heir_address'))
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('relationship')
                                            ->label(__('heir_certificate.heirs.relationship'))
                                            ->maxLength(255)
                                            ->placeholder(__('heir_certificate.placeholders.relationship'))
                                            ->helperText(__('heir_certificate.heirs.relationship_helper'))
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->collapsed()
                            ->cloneable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heir_name'] ?? __('heir_certificate.heirs.new_heir'))
                            ->reorderableWithButtons()
                            ->addActionLabel(__('heir_certificate.heirs.add_button'))
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                            )
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columns(2),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('formatted_certificate_number')
                    ->label(__('heir_certificate.columns.certificate_no'))
                    ->state(fn (HeirCertificate $record): string => $record->formatted_certificate_number)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('year', $direction)->orderBy('certificate_number', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('certificate_number', 'like', "%{$search}%")
                            ->orWhere('year', 'like', "%{$search}%");
                    })
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-hashtag'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('heir_certificate.columns.status'))
                    ->badge()
                    ->color(fn (CertificateStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (CertificateStatus $state): string => $state->getLabel())
                    ->sortable(),

                Tables\Columns\TextColumn::make('certificate_date')
                    ->label(__('heir_certificate.columns.certificate_date'))
                    ->date('d M Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('applicant_name')
                    ->label(__('heir_certificate.columns.applicant'))
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

                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('heir_certificate.columns.phone_number'))
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage(__('heir_certificate.table.phone_copied'))
                    ->placeholder(__('heir_certificate.table.not_provided')),

                Tables\Columns\TextColumn::make('deceased_name')
                    ->label(__('heir_certificate.columns.deceased'))
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

                Tables\Columns\TextColumn::make('date_of_death')
                    ->label(__('heir_certificate.columns.date_of_death'))
                    ->date('d M Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('place_of_death')
                    ->label(__('heir_certificate.columns.place_of_death'))
                    ->searchable()
                    ->limit(25)
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('heirs_count')
                    ->label(__('heir_certificate.columns.heirs'))
                    ->counts('heirs')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('personInCharge.name')
                    ->label(__('heir_certificate.columns.person_in_charge'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-user-circle')
                    ->placeholder(__('heir_certificate.table.not_assigned')),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('heir_certificate.columns.created_by'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('heir_certificate.columns.created_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('heir_certificate.columns.updated_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->defaultSort('year', 'desc')
            ->defaultSort('certificate_number', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('heir_certificate.filters.status'))
                    ->options([
                        CertificateStatus::ON_PROGRESS->value => CertificateStatus::ON_PROGRESS->getLabel(),
                        CertificateStatus::COMPLETED->value => CertificateStatus::COMPLETED->getLabel(),
                    ])
                    ->native(false)
                    ->placeholder(__('heir_certificate.filters.all_statuses')),

                Tables\Filters\SelectFilter::make('year')
                    ->label(__('heir_certificate.filters.year'))
                    ->options(function (): array {
                        $years = HeirCertificate::query()
                            ->select('year')
                            ->distinct()
                            ->whereNotNull('year')
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray();
                        return $years;
                    })
                    ->native(false)
                    ->placeholder(__('heir_certificate.filters.all_years')),

                Tables\Filters\SelectFilter::make('person_in_charge_id')
                    ->label(__('heir_certificate.filters.person_in_charge'))
                    ->relationship('personInCharge', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder(__('heir_certificate.filters.all_users')),

                Tables\Filters\SelectFilter::make('created_by')
                    ->label(__('heir_certificate.filters.created_by'))
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder(__('heir_certificate.filters.all_users')),

                Tables\Filters\Filter::make('certificate_date')
                    ->form([
                        Forms\Components\DatePicker::make('certificate_from')
                            ->label(__('heir_certificate.filters.certificate_from'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                        Forms\Components\DatePicker::make('certificate_until')
                            ->label(__('heir_certificate.filters.certificate_until'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['certificate_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('certificate_date', '>=', $date),
                            )
                            ->when(
                                $data['certificate_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('certificate_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['certificate_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('heir_certificate.filter_indicators.certificate_from', [
                                    'date' => \Carbon\Carbon::parse($data['certificate_from'])->format('d M Y')
                                ])
                            )->removeField('certificate_from');
                        }

                        if ($data['certificate_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('heir_certificate.filter_indicators.certificate_until', [
                                    'date' => \Carbon\Carbon::parse($data['certificate_until'])->format('d M Y')
                                ])
                            )->removeField('certificate_until');
                        }

                        return $indicators;
                    }),

                Tables\Filters\Filter::make('date_of_death')
                    ->form([
                        Forms\Components\DatePicker::make('death_from')
                            ->label(__('heir_certificate.filters.death_from'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                        Forms\Components\DatePicker::make('death_until')
                            ->label(__('heir_certificate.filters.death_until'))
                            ->native(false)
                            ->displayFormat('d M Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['death_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_death', '>=', $date),
                            )
                            ->when(
                                $data['death_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_death', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['death_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('heir_certificate.filter_indicators.death_from', [
                                    'date' => \Carbon\Carbon::parse($data['death_from'])->format('d M Y')
                                ])
                            )->removeField('death_from');
                        }

                        if ($data['death_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                __('heir_certificate.filter_indicators.death_until', [
                                    'date' => \Carbon\Carbon::parse($data['death_until'])->format('d M Y')
                                ])
                            )->removeField('death_until');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('heir_certificate.actions.view'))
                        ->color('primary'),

                    Tables\Actions\EditAction::make()
                        ->label(__('heir_certificate.actions.edit'))
                        ->color('warning'),

                    Tables\Actions\Action::make('send_whatsapp')
                        ->label(__('heir_certificate.actions.send_whatsapp'))
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->form(function (HeirCertificate $record) {
                            // Get default campaign
                            $defaultCampaign = WhatsAppCampaign::where('name', 'Heir Certificate Ready')->first();

                            // Build hidden variable fields with auto-filled values
                            $hiddenFields = [];
                            if ($defaultCampaign && !empty($defaultCampaign->variables)) {
                                foreach ($defaultCampaign->variables as $variable) {
                                    $value = null;
                                    if ($variable === 'applicant_name') {
                                        $value = $record->applicant_name;
                                    }

                                    $hiddenFields[] = Forms\Components\Hidden::make('variable_' . $variable)
                                        ->default($value);
                                }
                            }

                            return array_merge([
                                Forms\Components\Select::make('campaign_id')
                                    ->label(__('heir_certificate.whatsapp.select_campaign'))
                                    ->options(fn () => WhatsAppCampaign::where('is_active', true)
                                        ->pluck('name', 'id'))
                                    ->default($defaultCampaign?->id)
                                    ->required()
                                    ->live()
                                    ->searchable()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) use ($record) {
                                        if ($state) {
                                            $campaign = WhatsAppCampaign::find($state);
                                            if ($campaign && !empty($campaign->variables)) {
                                                // Auto-fill variables from certificate
                                                foreach ($campaign->variables as $variable) {
                                                    if ($variable === 'applicant_name') {
                                                        $set('variable_' . $variable, $record->applicant_name);
                                                    }
                                                }
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('phone_number')
                                    ->label(__('heir_certificate.whatsapp.phone_number'))
                                    ->tel()
                                    ->default($record->phone_number)
                                    ->required()
                                    ->placeholder(__('heir_certificate.whatsapp.phone_number_placeholder'))
                                    ->helperText(__('heir_certificate.whatsapp.phone_number_helper'))
                                    ->rule('regex:/^(08|62)\d{8,13}$/'),

                                Forms\Components\Placeholder::make('message_preview')
                                    ->label(__('heir_certificate.whatsapp.message_preview'))
                                    ->content(function (Forms\Get $get) use ($record) {
                                        if (!$get('campaign_id')) {
                                            return __('heir_certificate.whatsapp.no_preview');
                                        }

                                        $campaign = WhatsAppCampaign::find($get('campaign_id'));

                                        if (!$campaign) {
                                            return __('heir_certificate.whatsapp.campaign_not_found');
                                        }

                                        $message = $campaign->template;

                                        // Replace company name
                                        $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                        // Auto-replace variables from certificate
                                        if (!empty($campaign->variables)) {
                                            foreach ($campaign->variables as $variable) {
                                                if ($variable === 'applicant_name') {
                                                    $message = str_replace('[' . $variable . ']', $record->applicant_name, $message);
                                                }
                                            }
                                        }

                                        return new \Illuminate\Support\HtmlString(
                                            '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                            '<pre class="whitespace-pre-wrap break-words text-sm overflow-auto">' . e($message) . '</pre>' .
                                            '</div>'
                                        );
                                    })
                                    ->visible(fn (Forms\Get $get) => (bool) $get('campaign_id'))
                                    ->columnSpanFull(),
                            ], $hiddenFields);
                        })
                        ->action(function (array $data, HeirCertificate $record) {
                            $campaign = WhatsAppCampaign::find($data['campaign_id']);

                            if (!$campaign) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('heir_certificate.notifications.campaign_not_found'))
                                    ->body(__('heir_certificate.notifications.campaign_not_found'))
                                    ->persistent()
                                    ->send();
                                return;
                            }

                            // Collect variables from form data
                            $variables = [];
                            if (!empty($campaign->variables)) {
                                foreach ($campaign->variables as $variable) {
                                    $key = 'variable_' . $variable;
                                    if (isset($data[$key])) {
                                        $variables[$variable] = $data[$key];
                                    }
                                }
                            }

                            // Send via Campaign facade
                            $result = \App\Facades\Campaign::send(
                                $campaign->name,
                                $data['phone_number'],
                                $variables
                            );

                            if ($result['success']) {
                                Notification::make()
                                    ->success()
                                    ->title(__('heir_certificate.notifications.message_sent'))
                                    ->body(__('heir_certificate.notifications.whatsapp_sent', [
                                        'phone' => $data['phone_number']
                                    ]))
                                    ->send();
                            } else {
                                Notification::make()
                                    ->danger()
                                    ->title(__('heir_certificate.notifications.failed_to_send'))
                                    ->body($result['message'] ?? __('heir_certificate.notifications.whatsapp_failed'))
                                    ->persistent()
                                    ->send();
                            }
                        })
                        ->modalHeading(__('heir_certificate.whatsapp.modal_heading'))
                        ->modalDescription(__('heir_certificate.whatsapp.modal_description'))
                        ->modalSubmitActionLabel(__('heir_certificate.whatsapp.modal_submit'))
                        ->modalWidth('lg')
                        ->disabled(fn (HeirCertificate $record): bool => empty($record->phone_number))
                        ->tooltip(fn (HeirCertificate $record): ?string =>
                            empty($record->phone_number) ? __('heir_certificate.whatsapp.phone_not_set_tooltip') : null
                        ),

                    Tables\Actions\Action::make('mark_completed')
                        ->label(__('heir_certificate.actions.mark_completed'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(__('heir_certificate.modals.mark_completed.heading'))
                        ->modalDescription(__('heir_certificate.modals.mark_completed.description'))
                        ->modalSubmitActionLabel(__('heir_certificate.modals.mark_completed.submit'))
                        ->action(function (HeirCertificate $record) {
                            $record->update(['status' => CertificateStatus::COMPLETED]);
                            Notification::make()
                                ->success()
                                ->title(__('heir_certificate.notifications.status_updated'))
                                ->body(__('heir_certificate.notifications.marked_completed', [
                                    'number' => $record->formatted_certificate_number
                                ]))
                                ->send();
                        })
                        ->visible(fn (HeirCertificate $record): bool => $record->status === CertificateStatus::ON_PROGRESS),

                    Tables\Actions\Action::make('mark_on_progress')
                        ->label(__('heir_certificate.actions.mark_on_progress'))
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading(__('heir_certificate.modals.mark_on_progress.heading'))
                        ->modalDescription(__('heir_certificate.modals.mark_on_progress.description'))
                        ->modalSubmitActionLabel(__('heir_certificate.modals.mark_on_progress.submit'))
                        ->action(function (HeirCertificate $record) {
                            $record->update(['status' => CertificateStatus::ON_PROGRESS]);
                            Notification::make()
                                ->success()
                                ->title(__('heir_certificate.notifications.status_updated'))
                                ->body(__('heir_certificate.notifications.marked_on_progress', [
                                    'number' => $record->formatted_certificate_number
                                ]))
                                ->send();
                        })
                        ->visible(fn (HeirCertificate $record): bool => $record->status === CertificateStatus::COMPLETED),

                    Tables\Actions\DeleteAction::make()
                        ->label(__('heir_certificate.actions.delete'))
                        ->requiresConfirmation(),
                ])
                    ->label(__('heir_certificate.actions.actions'))
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label(__('heir_certificate.actions.export_selected'))
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'heir_certificates_selected_' . date('Y-m-d') . '_' . date('His'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                                ->withColumns([
                                    \pxlrbt\FilamentExcel\Columns\Column::make('formatted_certificate_number')
                                        ->heading(__('heir_certificate.export.certificate_number'))
                                        ->formatStateUsing(fn (HeirCertificate $record) => $record->formatted_certificate_number),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('certificate_date')
                                        ->heading(__('heir_certificate.export.certificate_date'))
                                        ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y') : '-'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('status')
                                        ->heading(__('heir_certificate.export.status'))
                                        ->formatStateUsing(fn (CertificateStatus $state) => $state->getLabel()),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('applicant_name')
                                        ->heading(__('heir_certificate.export.applicant_name')),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('applicant_address')
                                        ->heading(__('heir_certificate.export.applicant_address')),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('phone_number')
                                        ->heading(__('heir_certificate.export.phone_number'))
                                        ->formatStateUsing(fn ($state) => $state ?? '-'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('deceased_name')
                                        ->heading(__('heir_certificate.export.deceased_name')),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('place_of_death')
                                        ->heading(__('heir_certificate.export.place_of_death')),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('date_of_death')
                                        ->heading(__('heir_certificate.export.date_of_death'))
                                        ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y') : '-'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('personInCharge.name')
                                        ->heading(__('heir_certificate.export.person_in_charge'))
                                        ->formatStateUsing(fn ($state) => $state ?? __('heir_certificate.table.not_assigned')),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('heirs_count')
                                        ->heading(__('heir_certificate.export.heirs_count'))
                                        ->formatStateUsing(fn (HeirCertificate $record) => $record->heirs()->count()),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('heirs')
                                        ->heading(__('heir_certificate.export.heirs_names'))
                                        ->formatStateUsing(fn (HeirCertificate $record) => $record->heirs->pluck('heir_name')->join(', ') ?: '-'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('creator.name')
                                        ->heading(__('heir_certificate.export.created_by'))
                                        ->formatStateUsing(fn ($state) => $state ?? '-'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('created_at')
                                        ->heading(__('heir_certificate.export.created_at'))
                                        ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y H:i') : '-'),
                                ])
                        ]),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('heir_certificate.actions.delete'))
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('heir_certificate.actions.create'))
                    ->icon('heroicon-o-plus'),
            ])
            ->emptyStateHeading(__('heir_certificate.table.empty_state_heading'))
            ->emptyStateDescription(__('heir_certificate.table.empty_state_description'))
            ->emptyStateIcon('heroicon-o-document-text');
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
            'index' => Pages\ListHeirCertificates::route('/'),
            'create' => Pages\CreateHeirCertificate::route('/create'),
            'view' => Pages\ViewHeirCertificate::route('/{record}'),
            'edit' => Pages\EditHeirCertificate::route('/{record}/edit'),
        ];
    }

    /**
     * Get the global search result title.
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->applicant_name . ' - ' . $record->deceased_name;
    }

    /**
     * Get the global search result details.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['applicant_name', 'deceased_name', 'place_of_death'];
    }

    /**
     * Get the global search result details.
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('heir_certificate.global_search.certificate_date') => $record->certificate_date->format('d M Y'),
            __('heir_certificate.global_search.deceased') => $record->deceased_name,
            __('heir_certificate.global_search.heirs') => __('heir_certificate.global_search.heirs', [
                'count' => $record->heirs()->count()
            ]),
        ];
    }
}
