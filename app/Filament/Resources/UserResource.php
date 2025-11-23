<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\WhatsAppService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('user.navigation');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('user.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('user.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('user.sections.user_information.title'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('user.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label(__('user.fields.email'))
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('user.fields.phone'))
                                    ->tel()
                                    ->maxLength(20)
                                    ->helperText(__('user.helpers.phone')),
                                Forms\Components\TextInput::make('password')
                                    ->label(__('user.fields.password'))
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255)
                                    ->revealable(),
                            ]),
                    ]),

                Forms\Components\Section::make(__('user.sections.personal_information.title'))
                    ->description(__('user.sections.personal_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('national_id_number')
                                    ->label(__('user.fields.national_id_number'))
                                    ->maxLength(16)
                                    ->length(16)
                                    ->helperText(__('user.helpers.national_id_number'))
                                    ->placeholder(__('user.placeholders.national_id_number')),
                                Forms\Components\TextInput::make('birthplace')
                                    ->label(__('user.fields.birthplace'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.birthplace')),
                                Forms\Components\DatePicker::make('birthdate')
                                    ->label(__('user.fields.birthdate'))
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->maxDate(now())
                                    ->placeholder(__('user.placeholders.birthdate')),
                                Forms\Components\TextInput::make('occupation')
                                    ->label(__('user.fields.occupation'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.occupation')),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make(__('user.sections.address_information.title'))
                    ->description(__('user.sections.address_information.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('province')
                                    ->label(__('user.fields.province'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.province')),
                                Forms\Components\TextInput::make('city')
                                    ->label(__('user.fields.city'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.city')),
                                Forms\Components\TextInput::make('district')
                                    ->label(__('user.fields.district'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.district')),
                                Forms\Components\TextInput::make('village')
                                    ->label(__('user.fields.village'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.village')),
                                Forms\Components\TextInput::make('road')
                                    ->label(__('user.fields.road'))
                                    ->maxLength(255)
                                    ->placeholder(__('user.placeholders.road')),
                                Forms\Components\TextInput::make('rt')
                                    ->label(__('user.fields.rt'))
                                    ->numeric()
                                    ->maxLength(3)
                                    ->helperText(__('user.helpers.rt'))
                                    ->placeholder(__('user.placeholders.rt')),
                                Forms\Components\TextInput::make('rw')
                                    ->label(__('user.fields.rw'))
                                    ->numeric()
                                    ->maxLength(3)
                                    ->helperText(__('user.helpers.rw'))
                                    ->placeholder(__('user.placeholders.rw')),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make(__('user.sections.avatar.title'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label(__('user.fields.avatar'))
                            ->collection('avatar')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->helperText(__('user.helpers.avatar')),
                    ]),

                Forms\Components\Section::make(__('user.sections.documents.title'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('documents')
                            ->label(__('user.fields.documents'))
                            ->collection('documents')
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(10)
                            ->maxSize(5120)
                            ->helperText(__('user.helpers.documents')),
                    ]),

                Forms\Components\Section::make(__('user.sections.roles_permissions.title'))
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label(__('user.fields.roles'))
                            ->relationship('roles', 'name')
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->label(__('user.columns.avatar'))
                    ->collection('avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('user.columns.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('user.columns.phone'))
                    ->searchable()
                    ->copyable()
                    ->placeholder(__('user.placeholders.phone_not_available')),
                Tables\Columns\TextColumn::make('national_id_number')
                    ->label(__('user.columns.national_id_number'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder(__('user.placeholders.phone_not_available')),
                Tables\Columns\TextColumn::make('birthdate')
                    ->label(__('user.columns.birthdate'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder(__('user.placeholders.phone_not_available')),
                Tables\Columns\TextColumn::make('balance')
                    ->label(__('user.columns.balance'))
                    ->money('IDR')
                    ->sortable()
                    ->state(fn (User $record): int => $record->balance),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('user.columns.roles'))
                    ->badge()
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'admin',
                        'success' => 'user',
                    ])
                    ->separator(','),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('user.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('user.filters.roles'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Wallet Actions
                    Tables\Actions\Action::make('addBalance')
                        ->label(__('user.actions.add_balance'))
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('amount')
                                ->label(__('user.wallet.add_balance.amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1000)
                                ->suffix('IDR'),
                            Forms\Components\Textarea::make('meta.description')
                                ->label(__('user.wallet.add_balance.description'))
                                ->maxLength(500),
                        ])
                        ->action(function (User $record, array $data): void {
                            $record->deposit($data['amount'], $data['meta'] ?? []);

                            Notification::make()
                                ->success()
                                ->title(__('user.notifications.balance_added.title'))
                                ->body(__('user.notifications.balance_added.body', [
                                    'amount' => number_format($data['amount']),
                                    'name' => $record->name
                                ]))
                                ->send();
                        }),

                    Tables\Actions\Action::make('deductBalance')
                        ->label(__('user.actions.deduct_balance'))
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\TextInput::make('amount')
                                ->label(__('user.wallet.deduct_balance.amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->suffix('IDR'),
                            Forms\Components\Textarea::make('meta.description')
                                ->label(__('user.wallet.deduct_balance.description'))
                                ->maxLength(500),
                        ])
                        ->action(function (User $record, array $data): void {
                            if ($record->balance < $data['amount']) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('user.notifications.insufficient_balance.title'))
                                    ->body(__('user.notifications.insufficient_balance.body', [
                                        'balance' => number_format($record->balance)
                                    ]))
                                    ->send();
                                return;
                            }

                            $record->withdraw($data['amount'], $data['meta'] ?? []);

                            Notification::make()
                                ->success()
                                ->title(__('user.notifications.balance_deducted.title'))
                                ->body(__('user.notifications.balance_deducted.body', [
                                    'amount' => number_format($data['amount']),
                                    'name' => $record->name
                                ]))
                                ->send();
                        }),

                    Tables\Actions\Action::make('transferBalance')
                        ->label(__('user.actions.transfer_balance'))
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('recipient_id')
                                ->label(__('user.wallet.transfer_balance.recipient'))
                                ->options(function (User $record) {
                                    return User::where('id', '!=', $record->id)
                                        ->pluck('name', 'id');
                                })
                                ->searchable()
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->label(__('user.wallet.transfer_balance.amount'))
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->suffix('IDR'),
                            Forms\Components\Textarea::make('meta.description')
                                ->label(__('user.wallet.transfer_balance.description'))
                                ->maxLength(500),
                        ])
                        ->action(function (User $record, array $data): void {
                            $recipient = User::find($data['recipient_id']);

                            if ($record->balance < $data['amount']) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('user.notifications.insufficient_balance.title'))
                                    ->body(__('user.notifications.insufficient_balance.body', [
                                        'balance' => number_format($record->balance)
                                    ]))
                                    ->send();
                                return;
                            }

                            $record->transfer($recipient, $data['amount'], $data['meta'] ?? []);

                            Notification::make()
                                ->success()
                                ->title(__('user.notifications.transfer_successful.title'))
                                ->body(__('user.notifications.transfer_successful.body', [
                                    'amount' => number_format($data['amount']),
                                    'from' => $record->name,
                                    'to' => $recipient->name
                                ]))
                                ->send();
                        }),

                    // WhatsApp Action
                    Tables\Actions\Action::make('sendWhatsApp')
                        ->label(__('user.actions.send_whatsapp'))
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('phone')
                                ->label(__('user.whatsapp.phone_number'))
                                ->default(fn (User $record) => $record->phone)
                                ->required()
                                ->helperText(__('user.whatsapp.phone_helper')),
                            Forms\Components\Textarea::make('message')
                                ->label(__('user.whatsapp.message'))
                                ->required()
                                ->rows(5)
                                ->maxLength(1000)
                                ->placeholder(__('user.whatsapp.message_placeholder')),
                        ])
                        ->action(function (User $record, array $data): void {
                            try {
                                $whatsappService = app(WhatsAppService::class);

                                if (!$whatsappService->validatePhoneNumber($data['phone'])) {
                                    Notification::make()
                                        ->danger()
                                        ->title(__('user.notifications.invalid_phone.title'))
                                        ->body(__('user.notifications.invalid_phone.body'))
                                        ->send();
                                    return;
                                }

                                $whatsappService->send($data['phone'], $data['message']);

                                Notification::make()
                                    ->success()
                                    ->title(__('user.notifications.whatsapp_sent.title'))
                                    ->body(__('user.notifications.whatsapp_sent.body', ['name' => $record->name]))
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('user.notifications.whatsapp_error.title'))
                                    ->body(__('user.notifications.whatsapp_error.body', ['message' => $e->getMessage()]))
                                    ->send();
                            }
                        })
                        ->visible(fn (User $record) => filled($record->phone)),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
