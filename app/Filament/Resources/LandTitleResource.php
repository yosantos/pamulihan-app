<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandTitleResource\Pages;
use App\Models\LandTitle;
use App\Services\LandTitleDocumentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Builder;

class LandTitleResource extends Resource
{
    protected static ?string $model = LandTitle::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.land_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('land_title.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('land_title.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('land_title.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('land_title.sections.land_title_information'))
                    ->schema([
                        Forms\Components\TextInput::make('formatted_number')
                            ->label(__('land_title.fields.land_title_number'))
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder(__('land_title.placeholders.auto_generated'))
                            ->helperText(__('land_title.helpers.land_title_number'))
                            ->visible(fn ($record) => $record !== null)
                            ->default(fn ($record) => $record?->formatted_number),
                        Forms\Components\Select::make('land_title_type_id')
                            ->label(__('land_title.fields.land_title_type'))
                            ->relationship('landTitleType', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->placeholder(__('land_title.placeholders.land_title_type')),
                        Forms\Components\Toggle::make('is_heir')
                            ->label(__('land_title.fields.is_heir'))
                            ->default(false)
                            ->helperText(__('land_title.helpers.is_heir'))
                            ->inline(false)
                            ->reactive(),
                        Forms\Components\TextInput::make('heir_from_name')
                            ->label(__('land_title.fields.heir_from_name'))
                            ->maxLength(255)
                            ->placeholder(__('land_title.placeholders.heir_from_name'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\TextInput::make('death_place')
                            ->label(__('land_title.fields.death_place'))
                            ->maxLength(255)
                            ->placeholder(__('land_title.placeholders.death_place'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\DatePicker::make('death_date')
                            ->label(__('land_title.fields.death_date'))
                            ->native(false)
                            ->maxDate(now())
                            ->placeholder(__('land_title.placeholders.death_date'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\TextInput::make('death_certificate_number')
                            ->label(__('land_title.fields.death_certificate_number'))
                            ->maxLength(255)
                            ->placeholder(__('land_title.placeholders.death_certificate_number'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\TextInput::make('death_certificate_issuer')
                            ->label(__('land_title.fields.death_certificate_issuer'))
                            ->maxLength(255)
                            ->placeholder(__('land_title.placeholders.death_certificate_issuer'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\DatePicker::make('death_certificate_date')
                            ->label(__('land_title.fields.death_certificate_date'))
                            ->native(false)
                            ->maxDate(now())
                            ->placeholder(__('land_title.placeholders.death_certificate_date'))
                            ->visible(fn ($get) => $get('is_heir') === true),
                        Forms\Components\Select::make('sppt_land_title_id')
                            ->label(__('land_title.fields.sppt_land_title'))
                            ->relationship('spptLandTitle', 'number')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->number} - {$record->year} ({$record->owner})")
                            ->placeholder(__('land_title.placeholders.sppt_land_title'))
                            ->helperText(__('land_title.helpers.sppt_land_title')),
                        Forms\Components\Select::make('letter_c_land_title_id')
                            ->label(__('land_title.fields.letter_c_land_title'))
                            ->relationship('letterCLandTitle', 'number_of_c')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->number_of_c} - {$record->name}")
                            ->placeholder(__('land_title.placeholders.letter_c_land_title'))
                            ->helperText(__('land_title.helpers.letter_c_land_title')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('land_title.sections.transaction_details'))
                    ->schema([
                        Forms\Components\TextInput::make('transaction_amount')
                            ->label(__('land_title.fields.transaction_amount'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.transaction_amount'))
                            ->helperText(__('land_title.helpers.transaction_amount'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                $amount = (float) str_replace(',', '', $state ?? 0);

                                // Auto-calculate PPH (2.5%)
                                $pph = $amount * 0.025;
                                $set('pph', $pph);

                                // Auto-calculate PPAT amount (2%) only if not manually changed
                                $ppatAmount = $amount * 0.02;
                                $currentPpatAmount = (float) str_replace(',', '', $get('ppat_amount') ?? 0);

                                // Only auto-fill if:
                                // 1. Creating new record (no $record), OR
                                // 2. Current ppat_amount is 0 or matches the auto-calculated value
                                if (!$record || $currentPpatAmount == 0 || abs($currentPpatAmount - $ppatAmount) < 0.01) {
                                    $set('ppat_amount', $ppatAmount);
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('transaction_amount_wording')
                            ->label(__('land_title.fields.transaction_amount_wording'))
                            ->content(function ($get) {
                                $amount = (float) str_replace(',', '', $get('transaction_amount') ?? 0);
                                if ($amount > 0) {
                                    return \App\Services\NumberToIndonesianWords::convertCurrency($amount);
                                }
                                return '-';
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('area_of_the_land')
                            ->label(__('land_title.fields.area_of_the_land'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->step(0.01)
                            ->suffix('m²')
                            ->placeholder(__('land_title.placeholders.area_of_the_land'))
                            ->reactive()
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('area_of_the_land_wording')
                            ->label(__('land_title.fields.area_of_the_land_wording'))
                            ->content(function ($get) {
                                $area = (float) ($get('area_of_the_land') ?? 0);
                                if ($area > 0) {
                                    return \App\Services\NumberToIndonesianWords::convertArea($area) . ' Meter Persegi';
                                }
                                return '-';
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('land_title.sections.fees_and_taxes'))
                    ->schema([
                        Forms\Components\TextInput::make('pph')
                            ->label(__('land_title.fields.pph'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.pph'))
                            ->helperText(__('land_title.helpers.pph'))
                            ->disabled()
                            ->dehydrated()
                            ->reactive(),
                        Forms\Components\TextInput::make('bphtb')
                            ->label(__('land_title.fields.bphtb'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.bphtb'))
                            ->reactive(),
                        Forms\Components\TextInput::make('adm')
                            ->label(__('land_title.fields.adm'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.adm'))
                            ->reactive(),
                        Forms\Components\TextInput::make('pbb')
                            ->label(__('land_title.fields.pbb'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.pbb'))
                            ->reactive(),
                        Forms\Components\TextInput::make('adm_certificate')
                            ->label(__('land_title.fields.adm_certificate'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.adm_certificate'))
                            ->reactive(),
                        Forms\Components\TextInput::make('ppat_amount')
                            ->label(__('land_title.fields.ppat_amount'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->placeholder(__('land_title.placeholders.ppat_amount'))
                            ->helperText(__('land_title.helpers.ppat_amount'))
                            ->reactive()
                            ->live(onBlur: true), // Allow manual editing
                        Forms\Components\Placeholder::make('total_amount')
                            ->label(__('land_title.fields.total_amount'))
                            ->content(function ($get) {
                                $pph = (float) str_replace(',', '', $get('pph') ?? 0);
                                $bphtb = (float) str_replace(',', '', $get('bphtb') ?? 0);
                                $adm = (float) str_replace(',', '', $get('adm') ?? 0);
                                $pbb = (float) str_replace(',', '', $get('pbb') ?? 0);
                                $admCertificate = (float) str_replace(',', '', $get('adm_certificate') ?? 0);
                                $ppatAmount = (float) str_replace(',', '', $get('ppat_amount') ?? 0);

                                $total = $pph + $bphtb + $adm + $pbb + $admCertificate + $ppatAmount;
                                return 'Rp ' . number_format($total, 0, ',', '.');
                            })
                            ->columnSpan(2),
                        Forms\Components\Select::make('status')
                            ->label(__('land_title.fields.status'))
                            ->options([
                                'pending' => __('land_title.status.pending'),
                                'paid' => __('land_title.status.paid'),
                                'completed' => __('land_title.status.completed'),
                                'cancelled' => __('land_title.status.cancelled'),
                            ])
                            ->default('pending')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->helperText(__('land_title.helpers.status')),
                        Forms\Components\Placeholder::make('paid_amount_display')
                            ->label(__('land_title.fields.paid_amount'))
                            ->content(fn ($get, $record) => 'Rp ' . number_format($record?->paid_amount ?? 0, 0, ',', '.')),
                        Forms\Components\Placeholder::make('remaining_amount_display')
                            ->label(__('land_title.fields.remaining_amount'))
                            ->content(fn ($get, $record) => 'Rp ' . number_format($record?->remaining_amount ?? 0, 0, ',', '.')),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('land_title.sections.border_information'))
                    ->schema([
                        Forms\Components\Textarea::make('north_border')
                            ->label(__('land_title.fields.north_border'))
                            ->maxLength(65535)
                            ->rows(3)
                            ->placeholder(__('land_title.placeholders.north_border')),
                        Forms\Components\Textarea::make('east_border')
                            ->label(__('land_title.fields.east_border'))
                            ->maxLength(65535)
                            ->rows(3)
                            ->placeholder(__('land_title.placeholders.east_border')),
                        Forms\Components\Textarea::make('west_border')
                            ->label(__('land_title.fields.west_border'))
                            ->maxLength(65535)
                            ->rows(3)
                            ->placeholder(__('land_title.placeholders.west_border')),
                        Forms\Components\Textarea::make('south_border')
                            ->label(__('land_title.fields.south_border'))
                            ->maxLength(65535)
                            ->rows(3)
                            ->placeholder(__('land_title.placeholders.south_border')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('land_title.sections.applicants'))
                    ->schema([
                        Forms\Components\Repeater::make('landTitleApplicants')
                            ->label(__('land_title.fields.applicants'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label(__('land_title.fields.applicant'))
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder(__('land_title.placeholders.applicant')),
                                Forms\Components\Select::make('land_title_applicant_type_id')
                                    ->label(__('land_title.fields.applicant_type'))
                                    ->relationship('applicantType', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder(__('land_title.placeholders.applicant_type')),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel(__('land_title.actions.add_applicant'))
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['user_id']
                                    ? \App\Models\User::find($state['user_id'])?->name
                                    : null
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('formatted_number')
                    ->label(__('land_title.columns.land_title_number'))
                    ->state(fn (LandTitle $record): string => $record->formatted_number)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('year', $direction)->orderBy('number', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('number', 'like', "%{$search}%")
                            ->orWhere('year', 'like', "%{$search}%");
                    })
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-hashtag'),
                Tables\Columns\TextColumn::make('landTitleType.name')
                    ->label(__('land_title.fields.land_title_type'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('village_name')
                    ->label(__('land_title.fields.village'))
                    ->state(function (LandTitle $record): string {
                        // Try to get village from SPPT first, then Letter C
                        if ($record->spptLandTitle && $record->spptLandTitle->village) {
                            return ucwords(strtolower($record->spptLandTitle->village->name));
                        }
                        return '-';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('spptLandTitle.village', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->leftJoin('sppt_land_titles', 'land_titles.sppt_land_title_id', '=', 'sppt_land_titles.id')
                            ->leftJoin('villages', 'sppt_land_titles.village_id', '=', 'villages.id')
                            ->orderBy('villages.name', $direction)
                            ->select('land_titles.*');
                    }),
                Tables\Columns\TextColumn::make('seller_name')
                    ->label(__('land_title.fields.seller'))
                    ->state(function (LandTitle $record): string {
                        $seller = $record->landTitleApplicants()
                            ->whereHas('applicantType', function ($query) {
                                $query->where('code', 'seller');
                            })
                            ->with('user')
                            ->first();
                        return $seller?->user?->name ?? '-';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('landTitleApplicants', function ($q) use ($search) {
                            $q->whereHas('applicantType', function ($at) {
                                $at->where('code', 'seller');
                            })->whereHas('user', function ($u) use ($search) {
                                $u->where('name', 'like', "%{$search}%");
                            });
                        });
                    }),
                Tables\Columns\TextColumn::make('buyer_name')
                    ->label(__('land_title.fields.buyer'))
                    ->state(function (LandTitle $record): string {
                        $buyer = $record->landTitleApplicants()
                            ->whereHas('applicantType', function ($query) {
                                $query->where('code', 'buyer');
                            })
                            ->with('user')
                            ->first();
                        return $buyer?->user?->name ?? '-';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('landTitleApplicants', function ($q) use ($search) {
                            $q->whereHas('applicantType', function ($at) {
                                $at->where('code', 'buyer');
                            })->whereHas('user', function ($u) use ($search) {
                                $u->where('name', 'like', "%{$search}%");
                            });
                        });
                    }),
                Tables\Columns\IconColumn::make('is_heir')
                    ->label(__('land_title.fields.is_heir'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('land_title.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => __("land_title.status.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_amount')
                    ->label(__('land_title.fields.transaction_amount'))
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('area_of_the_land')
                    ->label(__('land_title.fields.area_of_the_land'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m²')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('land_title.fields.total_amount'))
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('spptLandTitle.number')
                    ->label(__('land_title.fields.sppt_land_title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('letterCLandTitle.number_of_c')
                    ->label(__('land_title.fields.letter_c_land_title'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('land_title.fields.created_by'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('land_title.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('land_title_type_id')
                    ->label(__('land_title.filters.type'))
                    ->relationship('landTitleType', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('land_title.filters.created_from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('land_title.filters.created_until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('transaction_amount')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->label(__('land_title.filters.amount_from'))
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('amount_until')
                            ->label(__('land_title.filters.amount_until'))
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['amount_from'], fn ($q, $amount) => $q->where('transaction_amount', '>=', $amount))
                            ->when($data['amount_until'], fn ($q, $amount) => $q->where('transaction_amount', '<=', $amount));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('generate_document')
                        ->label(__('land_title.actions.generate_document'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (LandTitle $record) {
                            try {
                                $service = app(LandTitleDocumentService::class);
                                $filePath = $service->generate($record);

                                Notification::make()
                                    ->title(__('land_title.notifications.document_generated'))
                                    ->success()
                                    ->send();

                                return response()->download($filePath)->deleteFileAfterSend();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title(__('land_title.notifications.document_generation_failed'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading(__('land_title.modals.generate_document.heading'))
                        ->modalDescription(__('land_title.modals.generate_document.description'))
                        ->modalSubmitActionLabel(__('land_title.modals.generate_document.submit'))
                        ->modalCancelActionLabel(__('land_title.modals.generate_document.cancel')),

                    // Payment Action
                    Tables\Actions\Action::make('payment')
                        ->label(__('land_title.actions.payment'))
                        ->icon('heroicon-o-banknotes')
                        ->color('info')
                        ->visible(fn (LandTitle $record) => $record->status !== 'completed' && $record->status !== 'cancelled' && !$record->isFullyPaid())
                        ->form([
                            Forms\Components\Placeholder::make('remaining_info')
                                ->label(__('land_title.payment.remaining_amount'))
                                ->content(fn (LandTitle $record) => 'Rp ' . number_format($record->remaining_amount, 0, ',', '.')),
                            Forms\Components\TextInput::make('amount')
                                ->label(__('land_title.payment.amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(fn (LandTitle $record) => $record->remaining_amount)
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->helperText(__('land_title.payment.amount_helper')),
                            Forms\Components\DatePicker::make('payment_date')
                                ->label(__('land_title.payment.payment_date'))
                                ->required()
                                ->default(now())
                                ->maxDate(now())
                                ->native(false),
                            Forms\Components\Textarea::make('notes')
                                ->label(__('land_title.payment.notes'))
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder(__('land_title.payment.notes_placeholder')),
                        ])
                        ->action(function (LandTitle $record, array $data) {
                            try {
                                $amount = (float) $data['amount'];

                                // Get first buyer
                                $firstBuyer = $record->first_buyer;
                                if (!$firstBuyer) {
                                    throw new \Exception(__('land_title.errors.no_buyer'));
                                }

                                // Create payment record
                                $record->payments()->create([
                                    'amount' => $amount,
                                    'payment_date' => $data['payment_date'],
                                    'notes' => $data['notes'] ?? null,
                                    'created_by' => auth()->id(),
                                ]);

                                // Update paid amount
                                $record->paid_amount += $amount;

                                // Deposit to first buyer's wallet
                                $firstBuyer->deposit($amount, [
                                    'type' => 'land_title_payment',
                                    'land_title_id' => $record->id,
                                    'description' => __('land_title.payment.deposit_description', [
                                        'number' => $record->formatted_number,
                                    ]),
                                ]);

                                // Check if fully paid
                                if ($record->isFullyPaid()) {
                                    $record->status = 'paid';
                                }

                                $record->save();

                                Notification::make()
                                    ->success()
                                    ->title(__('land_title.notifications.payment_success'))
                                    ->body(__('land_title.notifications.payment_success_body', [
                                        'amount' => number_format($amount, 0, ',', '.'),
                                    ]))
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('land_title.notifications.payment_failed'))
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                        ->modalHeading(__('land_title.modals.payment.heading'))
                        ->modalSubmitActionLabel(__('land_title.modals.payment.submit'))
                        ->modalWidth('md'),

                    // Complete Action
                    Tables\Actions\Action::make('complete')
                        ->label(__('land_title.actions.complete'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (LandTitle $record) => $record->status === 'paid')
                        ->form([
                            Forms\Components\Section::make(__('land_title.complete.completion_info'))
                                ->schema([
                                    Forms\Components\TextInput::make('completion_number')
                                        ->label(__('land_title.complete.completion_number'))
                                        ->numeric()
                                        ->placeholder(__('land_title.complete.completion_number_placeholder')),
                                    Forms\Components\TextInput::make('completion_year')
                                        ->label(__('land_title.complete.completion_year'))
                                        ->numeric()
                                        ->placeholder(__('land_title.complete.completion_year_placeholder')),
                                ])->columns(2),

                            Forms\Components\Section::make(__('land_title.complete.adm_distribution'))
                                ->description(fn (LandTitle $record) => __('land_title.complete.adm_distribution_desc', ['amount' => number_format($record->adm, 0, ',', '.')]))
                                ->schema([
                                    Forms\Components\Repeater::make('adm_recipients')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\Select::make('user_id')
                                                ->label(__('land_title.complete.user'))
                                                ->required()
                                                ->searchable()
                                                ->options(function () {
                                                    return \App\Models\User::role('team_ppat')->pluck('name', 'id');
                                                })
                                                ->distinct(),
                                            Forms\Components\TextInput::make('amount')
                                                ->label(__('land_title.complete.amount'))
                                                ->required()
                                                ->numeric()
                                                ->minValue(1)
                                                ->prefix('Rp')
                                                ->mask(RawJs::make('$money($input)'))
                                                ->stripCharacters(','),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(0)
                                        ->addActionLabel(__('land_title.complete.add_recipient'))
                                        ->minItems(1),
                                ]),

                            Forms\Components\Section::make(__('land_title.complete.ppat_distribution'))
                                ->description(fn (LandTitle $record) => __('land_title.complete.ppat_distribution_desc', ['amount' => number_format($record->ppat_amount, 0, ',', '.')]))
                                ->schema([
                                    Forms\Components\Repeater::make('ppat_recipients')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\Select::make('user_id')
                                                ->label(__('land_title.complete.user'))
                                                ->required()
                                                ->searchable()
                                                ->options(function () {
                                                    return \App\Models\User::role('team_ppat')->pluck('name', 'id');
                                                })
                                                ->distinct(),
                                            Forms\Components\TextInput::make('percentage')
                                                ->label(__('land_title.complete.percentage'))
                                                ->required()
                                                ->numeric()
                                                ->minValue(0.01)
                                                ->maxValue(100)
                                                ->suffix('%')
                                                ->step(0.01)
                                                ->default(75),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(1)
                                        ->addActionLabel(__('land_title.complete.add_recipient'))
                                        ->minItems(1),
                                ]),
                        ])
                        ->action(function (LandTitle $record, array $data) {
                            try {
                                // Validate ADM distribution
                                $admTotal = collect($data['adm_recipients'])->sum(fn ($r) => (float) $r['amount']);
                                if ($admTotal != $record->adm) {
                                    throw new \Exception(__('land_title.errors.adm_total_mismatch', [
                                        'expected' => number_format($record->adm, 0, ',', '.'),
                                        'actual' => number_format($admTotal, 0, ',', '.'),
                                    ]));
                                }

                                // Validate PPAT distribution
                                $ppatPercentageTotal = collect($data['ppat_recipients'])->sum(fn ($r) => (float) $r['percentage']);
                                if (abs($ppatPercentageTotal - 100) > 0.01) {
                                    throw new \Exception(__('land_title.errors.ppat_percentage_mismatch', [
                                        'actual' => $ppatPercentageTotal,
                                    ]));
                                }

                                $firstBuyer = $record->first_buyer;
                                if (!$firstBuyer) {
                                    throw new \Exception(__('land_title.errors.no_buyer'));
                                }

                                // Check buyer balance
                                $totalNeeded = $record->bphtb + $record->pbb + $record->pph + $record->adm + $record->ppat_amount;
                                if ($firstBuyer->balance < $totalNeeded) {
                                    throw new \Exception(__('land_title.errors.insufficient_balance', [
                                        'balance' => number_format($firstBuyer->balance, 0, ',', '.'),
                                        'needed' => number_format($totalNeeded, 0, ',', '.'),
                                    ]));
                                }

                                \DB::transaction(function () use ($record, $data, $firstBuyer) {
                                    // Withdraw taxes (to bank/void)
                                    if ($record->bphtb > 0) {
                                        $firstBuyer->withdraw($record->bphtb, ['description' => "BPHTB - {$record->formatted_number}"]);
                                    }
                                    if ($record->pbb > 0) {
                                        $firstBuyer->withdraw($record->pbb, ['description' => "PBB - {$record->formatted_number}"]);
                                    }
                                    if ($record->pph > 0) {
                                        $firstBuyer->withdraw($record->pph, ['description' => "PPh - {$record->formatted_number}"]);
                                    }

                                    // Distribute ADM
                                    foreach ($data['adm_recipients'] as $recipient) {
                                        $user = \App\Models\User::find($recipient['user_id']);
                                        $amount = (float) $recipient['amount'];

                                        $firstBuyer->transfer($user, $amount, [
                                            'description' => "ADM - {$record->formatted_number}",
                                        ]);

                                        $record->recipients()->create([
                                            'user_id' => $recipient['user_id'],
                                            'type' => 'administration',
                                            'amount' => $amount,
                                            'calculated_amount' => $amount,
                                        ]);
                                    }

                                    // Distribute PPAT amount
                                    foreach ($data['ppat_recipients'] as $recipient) {
                                        $user = \App\Models\User::find($recipient['user_id']);
                                        $percentage = (float) $recipient['percentage'];
                                        $amount = ($record->ppat_amount * $percentage) / 100;

                                        $firstBuyer->transfer($user, $amount, [
                                            'description' => "PPAT {$percentage}% - {$record->formatted_number}",
                                        ]);

                                        $record->recipients()->create([
                                            'user_id' => $recipient['user_id'],
                                            'type' => 'ppat',
                                            'percentage' => $percentage,
                                            'calculated_amount' => $amount,
                                        ]);
                                    }

                                    // Update record
                                    $record->update([
                                        'completion_number' => $data['completion_number'] ?? null,
                                        'completion_year' => $data['completion_year'] ?? null,
                                        'status' => 'completed',
                                    ]);
                                });

                                Notification::make()
                                    ->success()
                                    ->title(__('land_title.notifications.complete_success'))
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('land_title.notifications.complete_failed'))
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                        ->modalHeading(__('land_title.modals.complete.heading'))
                        ->modalSubmitActionLabel(__('land_title.modals.complete.submit'))
                        ->modalWidth('3xl'),

                    // Withdrawal Action
                    Tables\Actions\Action::make('withdrawal')
                        ->label(__('land_title.actions.withdrawal'))
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->visible(fn (LandTitle $record) => $record->status === 'paid')
                        ->requiresConfirmation()
                        ->modalHeading(__('land_title.modals.withdrawal.heading'))
                        ->modalDescription(fn (LandTitle $record) => __('land_title.modals.withdrawal.description', [
                            'amount' => number_format($record->paid_amount, 0, ',', '.'),
                        ]))
                        ->modalSubmitActionLabel(__('land_title.modals.withdrawal.submit'))
                        ->action(function (LandTitle $record) {
                            try {
                                $firstBuyer = $record->first_buyer;
                                if (!$firstBuyer) {
                                    throw new \Exception(__('land_title.errors.no_buyer'));
                                }

                                \DB::transaction(function () use ($record, $firstBuyer) {
                                    // Withdraw deposited amount from buyer's wallet
                                    if ($record->paid_amount > 0) {
                                        $firstBuyer->withdraw($record->paid_amount, [
                                            'description' => __('land_title.withdrawal.description', [
                                                'number' => $record->formatted_number,
                                            ]),
                                        ]);
                                    }

                                    // Update record
                                    $record->update([
                                        'status' => 'cancelled',
                                        'paid_amount' => 0,
                                    ]);

                                    // Optionally keep payment records for audit trail
                                    // $record->payments()->delete(); // Uncomment if you want to delete payment records
                                });

                                Notification::make()
                                    ->success()
                                    ->title(__('land_title.notifications.withdrawal_success'))
                                    ->body(__('land_title.notifications.withdrawal_success_body', [
                                        'amount' => number_format($record->paid_amount, 0, ',', '.'),
                                    ]))
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('land_title.notifications.withdrawal_failed'))
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListLandTitles::route('/'),
            'create' => Pages\CreateLandTitle::route('/create'),
            'edit' => Pages\EditLandTitle::route('/{record}/edit'),
            'view' => Pages\ViewLandTitle::route('/{record}'),
        ];
    }
}
