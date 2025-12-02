<?php

namespace App\Filament\Resources;

use App\Enums\CertificateStatus;
use App\Filament\Resources\BankLoanRegistrationResource\Pages;
use App\Models\BankLoanRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BankLoanRegistrationResource extends Resource
{
    protected static ?string $model = BankLoanRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.civil_registration');
    }

    public static function getNavigationLabel(): string
    {
        return __('bank_loan.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('bank_loan.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('bank_loan.model_label');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('bank_loan.sections.registration_info'))
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('full_registration_number')
                                    ->label(__('bank_loan.fields.registration_number'))
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(__('bank_loan.placeholders.auto_generated'))
                                    ->visible(fn ($record) => $record !== null)
                                    ->default(fn ($record) => $record?->full_registration_number),

                                Forms\Components\DatePicker::make('date')
                                    ->label(__('bank_loan.fields.date'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->default(now())
                                    ->columnSpan(fn ($record) => $record === null ? 2 : 1),

                                Forms\Components\Select::make('status')
                                    ->label(__('bank_loan.fields.status'))
                                    ->options([
                                        CertificateStatus::ON_PROGRESS->value => __('bank_loan.status.on_progress'),
                                        CertificateStatus::COMPLETED->value => __('bank_loan.status.completed'),
                                    ])
                                    ->default(CertificateStatus::ON_PROGRESS->value)
                                    ->required()
                                    ->native(false),
                            ]),

                        Forms\Components\Select::make('person_in_charge_id')
                            ->label(__('bank_loan.fields.person_in_charge'))
                            ->relationship('personInCharge', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->native(false),
                    ]),

                Forms\Components\Section::make(__('bank_loan.sections.applicant_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('bank_loan.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('birthplace')
                                    ->label(__('bank_loan.fields.birthplace'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('birthdate')
                                    ->label(__('bank_loan.fields.birthdate'))
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->maxDate(now()),
                            ]),

                        Forms\Components\TextInput::make('occupation')
                            ->label(__('bank_loan.fields.occupation'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label(__('bank_loan.fields.address'))
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('village_id')
                            ->label(__('bank_loan.fields.village'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('village', 'name'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('bank_loan.sections.loan_info'))
                    ->schema([
                        Forms\Components\TextInput::make('bank')
                            ->label(__('bank_loan.fields.bank'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kohir')
                                    ->label(__('bank_loan.fields.kohir'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('land_of_area')
                                    ->label(__('bank_loan.fields.land_of_area'))
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('persil')
                                    ->label(__('bank_loan.fields.persil'))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nib')
                                    ->label(__('bank_loan.fields.nib'))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('no_shm')
                                    ->label(__('bank_loan.fields.no_shm'))
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('note')
                            ->label(__('bank_loan.fields.note'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_registration_number')
                    ->label(__('bank_loan.fields.registration_number'))
                    ->searchable(['registration_number', 'year'])
                    ->sortable(['registration_number', 'year'])
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('bank_loan.fields.date'))
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('bank_loan.fields.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bank')
                    ->label(__('bank_loan.fields.bank'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('bank_loan.fields.village'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('bank_loan.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor()),

                Tables\Columns\TextColumn::make('personInCharge.name')
                    ->label(__('bank_loan.fields.person_in_charge'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('bank_loan.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('bank_loan.fields.status'))
                    ->options([
                        CertificateStatus::ON_PROGRESS->value => __('bank_loan.status.on_progress'),
                        CertificateStatus::COMPLETED->value => __('bank_loan.status.completed'),
                    ]),

                Tables\Filters\SelectFilter::make('person_in_charge_id')
                    ->label(__('bank_loan.fields.person_in_charge'))
                    ->relationship('personInCharge', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('bank_loan.filters.date_from')),
                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('bank_loan.filters.date_until')),
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
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListBankLoanRegistrations::route('/'),
            'create' => Pages\CreateBankLoanRegistration::route('/create'),
            'view' => Pages\ViewBankLoanRegistration::route('/{record}'),
            'edit' => Pages\EditBankLoanRegistration::route('/{record}/edit'),
        ];
    }
}
