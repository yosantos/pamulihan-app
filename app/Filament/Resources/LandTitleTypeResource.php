<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandTitleTypeResource\Pages;
use App\Models\LandTitleType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LandTitleTypeResource extends Resource
{
    protected static ?string $model = LandTitleType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.land_management');
    }

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('land_title_types.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('land_title_types.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('land_title_types.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('land_title_types.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('land_title_types.placeholders.name')),
                        Forms\Components\TextInput::make('code')
                            ->label(__('land_title_types.fields.code'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->helperText(__('land_title_types.helpers.code'))
                            ->placeholder(__('land_title_types.placeholders.code')),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('land_title_types.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('land_title_types.columns.code'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('land_titles_count')
                    ->label(__('land_title_types.columns.usage_count'))
                    ->counts('landTitles')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('land_title_types.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLandTitleTypes::route('/'),
        ];
    }
}
