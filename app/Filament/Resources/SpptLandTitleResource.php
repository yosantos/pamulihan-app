<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpptLandTitleResource\Pages;
use App\Models\SpptLandTitle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpptLandTitleResource extends Resource
{
    protected static ?string $model = SpptLandTitle::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Land Management';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('sppt_land_title.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('sppt_land_title.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('sppt_land_title.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('sppt_land_title.sections.basic_information'))
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label(__('sppt_land_title.fields.number'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('sppt_land_title.placeholders.number'))
                            ->helperText(__('sppt_land_title.helpers.number')),
                        Forms\Components\TextInput::make('year')
                            ->label(__('sppt_land_title.fields.year'))
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100)
                            ->default(now()->year)
                            ->placeholder(__('sppt_land_title.placeholders.year')),
                        Forms\Components\TextInput::make('owner')
                            ->label(__('sppt_land_title.fields.owner'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('sppt_land_title.placeholders.owner')),
                        Forms\Components\TextInput::make('block')
                            ->label(__('sppt_land_title.fields.block'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('sppt_land_title.placeholders.block')),
                        Forms\Components\Select::make('village_id')
                            ->label(__('sppt_land_title.fields.village'))
                            ->relationship('village', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->placeholder(__('sppt_land_title.placeholders.village')),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('sppt_land_title.sections.area_information'))
                    ->schema([
                        Forms\Components\TextInput::make('land_area')
                            ->label(__('sppt_land_title.fields.land_area'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->step(0.01)
                            ->suffix('m²')
                            ->placeholder(__('sppt_land_title.placeholders.land_area')),
                        Forms\Components\TextInput::make('building_area')
                            ->label(__('sppt_land_title.fields.building_area'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->step(0.01)
                            ->suffix('m²')
                            ->placeholder(__('sppt_land_title.placeholders.building_area')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('sppt_land_title.fields.number'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('year')
                    ->label(__('sppt_land_title.fields.year'))
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('owner')
                    ->label(__('sppt_land_title.fields.owner'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('block')
                    ->label(__('sppt_land_title.fields.block'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('sppt_land_title.fields.village'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('land_area')
                    ->label(__('sppt_land_title.fields.land_area'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m²')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('building_area')
                    ->label(__('sppt_land_title.fields.building_area'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m²')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('land_titles_count')
                    ->label(__('sppt_land_title.fields.references_count'))
                    ->counts('landTitles')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('sppt_land_title.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label(__('sppt_land_title.filters.year'))
                    ->options(function () {
                        $currentYear = now()->year;
                        $years = [];
                        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                            $years[$i] = $i;
                        }
                        return $years;
                    }),
                Tables\Filters\SelectFilter::make('village_id')
                    ->label(__('sppt_land_title.filters.village'))
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListSpptLandTitles::route('/'),
            'create' => Pages\CreateSpptLandTitle::route('/create'),
            'edit' => Pages\EditSpptLandTitle::route('/{record}/edit'),
            'view' => Pages\ViewSpptLandTitle::route('/{record}'),
        ];
    }
}
