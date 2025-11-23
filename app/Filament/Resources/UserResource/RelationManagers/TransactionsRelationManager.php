<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Wallet Transactions';

    protected static ?string $icon = 'heroicon-o-banknotes';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('Transaction ID')
                    ->searchable()
                    ->copyable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'deposit',
                        'danger' => 'withdraw',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($record): string => $record->type === 'deposit' ? 'success' : 'danger')
                    ->icon(fn ($record): string => $record->type === 'deposit' ? 'heroicon-o-arrow-down-circle' : 'heroicon-o-arrow-up-circle'),
                Tables\Columns\TextColumn::make('meta.description')
                    ->label('Description')
                    ->limit(50)
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('confirmed')
                    ->badge()
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Confirmed' : 'Pending'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'deposit' => 'Deposit',
                        'withdraw' => 'Withdraw',
                    ]),
                Tables\Filters\SelectFilter::make('confirmed')
                    ->label('Status')
                    ->options([
                        '1' => 'Confirmed',
                        '0' => 'Pending',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                // Removed create action - transactions should be managed through User actions
            ])
            ->actions([
                Tables\Actions\Action::make('viewDetails')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Transaction Details')
                    ->modalContent(fn ($record) => view('filament.resources.user-resource.modals.transaction-details', ['transaction' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
            ])
            ->bulkActions([
                // No bulk actions for transactions
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
