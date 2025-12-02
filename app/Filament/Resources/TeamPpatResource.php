<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamPpatResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeamPpatResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.land_management');
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('team_ppat.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('team_ppat.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('team_ppat.plural_model_label');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('team_ppat');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('team_ppat.sections.personal_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('team_ppat.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.name')),
                        Forms\Components\TextInput::make('email')
                            ->label(__('team_ppat.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder(__('team_ppat.placeholders.email')),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('team_ppat.fields.phone'))
                            ->tel()
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.phone')),
                        Forms\Components\DatePicker::make('birthdate')
                            ->label(__('team_ppat.fields.birthdate'))
                            ->native(false)
                            ->maxDate(now())
                            ->placeholder(__('team_ppat.placeholders.birthdate')),
                        Forms\Components\TextInput::make('birthplace')
                            ->label(__('team_ppat.fields.birthplace'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.birthplace')),
                        Forms\Components\TextInput::make('occupation')
                            ->label(__('team_ppat.fields.occupation'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.occupation')),
                        Forms\Components\TextInput::make('national_id_number')
                            ->label(__('team_ppat.fields.national_id_number'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.national_id_number')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('team_ppat.sections.address'))
                    ->schema([
                        Forms\Components\TextInput::make('road')
                            ->label(__('team_ppat.fields.road'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.road')),
                        Forms\Components\TextInput::make('rt')
                            ->label(__('team_ppat.fields.rt'))
                            ->maxLength(10)
                            ->placeholder(__('team_ppat.placeholders.rt')),
                        Forms\Components\TextInput::make('rw')
                            ->label(__('team_ppat.fields.rw'))
                            ->maxLength(10)
                            ->placeholder(__('team_ppat.placeholders.rw')),
                        Forms\Components\TextInput::make('village')
                            ->label(__('team_ppat.fields.village'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.village')),
                        Forms\Components\TextInput::make('district')
                            ->label(__('team_ppat.fields.district'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.district')),
                        Forms\Components\TextInput::make('city')
                            ->label(__('team_ppat.fields.city'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.city')),
                        Forms\Components\TextInput::make('province')
                            ->label(__('team_ppat.fields.province'))
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.province')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('team_ppat.sections.password'))
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label(__('team_ppat.fields.password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn ($context) => $context === 'create')
                            ->maxLength(255)
                            ->placeholder(__('team_ppat.placeholders.password'))
                            ->helperText(__('team_ppat.helpers.password')),
                    ])
                    ->columns(1)
                    ->visible(fn ($context) => $context === 'create' || request()->has('changePassword')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('team_ppat.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('team_ppat.columns.email'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('team_ppat.columns.phone'))
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('team_ppat.columns.city'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('occupation')
                    ->label(__('team_ppat.columns.occupation'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('team_ppat.columns.balance'))
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state < 0 => 'danger',
                        $state > 0 => 'success',
                        default => 'gray',
                    })
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('team_ppat.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_phone')
                    ->label(__('team_ppat.filters.has_phone'))
                    ->query(fn (Builder $query) => $query->whereNotNull('phone')),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('team_ppat.filters.created_from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('team_ppat.filters.created_until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Deposit Action
                    Tables\Actions\Action::make('deposit')
                        ->label(__('team_ppat.actions.deposit'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->form([
                            Forms\Components\Placeholder::make('current_balance')
                                ->label(__('team_ppat.wallet.current_balance'))
                                ->content(fn (User $record) => 'Rp ' . number_format($record->balance, 0, ',', '.')),
                            Forms\Components\TextInput::make('amount')
                                ->label(__('team_ppat.wallet.deposit_amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->helperText(__('team_ppat.wallet.deposit_helper')),
                            Forms\Components\Textarea::make('description')
                                ->label(__('team_ppat.wallet.description'))
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder(__('team_ppat.wallet.description_placeholder')),
                        ])
                        ->action(function (User $record, array $data) {
                            try {
                                $amount = (float) $data['amount'];

                                $record->deposit($amount, [
                                    'description' => $data['description'] ?? __('team_ppat.wallet.manual_deposit'),
                                    'created_by' => auth()->id(),
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title(__('team_ppat.notifications.deposit_success'))
                                    ->body(__('team_ppat.notifications.deposit_success_body', [
                                        'amount' => number_format($amount, 0, ',', '.'),
                                        'name' => $record->name,
                                    ]))
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('team_ppat.notifications.deposit_failed'))
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                        ->modalHeading(__('team_ppat.modals.deposit.heading'))
                        ->modalSubmitActionLabel(__('team_ppat.modals.deposit.submit'))
                        ->modalWidth('md'),

                    // Withdrawal Action (Force Withdrawal - allows negative balance)
                    Tables\Actions\Action::make('withdrawal')
                        ->label(__('team_ppat.actions.withdrawal'))
                        ->icon('heroicon-o-arrow-up-tray')
                        ->color('warning')
                        ->form([
                            Forms\Components\Placeholder::make('current_balance')
                                ->label(__('team_ppat.wallet.current_balance'))
                                ->content(fn (User $record) => 'Rp ' . number_format($record->balance, 0, ',', '.')),
                            Forms\Components\TextInput::make('amount')
                                ->label(__('team_ppat.wallet.withdrawal_amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->helperText(__('team_ppat.wallet.withdrawal_helper')),
                            Forms\Components\Textarea::make('description')
                                ->label(__('team_ppat.wallet.description'))
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder(__('team_ppat.wallet.description_placeholder')),
                        ])
                        ->action(function (User $record, array $data) {
                            try {
                                $amount = (float) $data['amount'];

                                // Force withdrawal - allows negative balance
                                $record->forceWithdraw($amount, [
                                    'description' => $data['description'] ?? __('team_ppat.wallet.manual_withdrawal'),
                                    'created_by' => auth()->id(),
                                ]);

                                $newBalance = $record->balance;
                                $isNegative = $newBalance < 0;

                                Notification::make()
                                    ->success()
                                    ->title(__('team_ppat.notifications.withdrawal_success'))
                                    ->body(__('team_ppat.notifications.withdrawal_success_body', [
                                        'amount' => number_format($amount, 0, ',', '.'),
                                        'name' => $record->name,
                                    ]) . ($isNegative ? ' ' . __('team_ppat.notifications.negative_balance_warning', [
                                        'balance' => number_format(abs($newBalance), 0, ',', '.'),
                                    ]) : ''))
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('team_ppat.notifications.withdrawal_failed'))
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                        ->modalHeading(__('team_ppat.modals.withdrawal.heading'))
                        ->modalDescription(__('team_ppat.modals.withdrawal.description'))
                        ->modalSubmitActionLabel(__('team_ppat.modals.withdrawal.submit'))
                        ->modalWidth('md'),

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
            'index' => Pages\ListTeamPpats::route('/'),
            'create' => Pages\CreateTeamPpat::route('/create'),
            'view' => Pages\ViewTeamPpat::route('/{record}'),
            'edit' => Pages\EditTeamPpat::route('/{record}/edit'),
        ];
    }
}
