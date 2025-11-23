<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterCLandTitleResource\Pages;
use App\Models\LetterCLandTitle;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LetterCLandTitleResource extends Resource
{
    protected static ?string $model = LetterCLandTitle::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Land Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('letter_c_land_title.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('letter_c_land_title.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('letter_c_land_title.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('letter_c_land_title.sections.basic_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('letter_c_land_title.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('letter_c_land_title.placeholders.name'))
                            ->helperText(__('letter_c_land_title.helpers.name'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('village_id')
                            ->label(__('letter_c_land_title.fields.village'))
                            ->relationship('village', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('village.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->placeholder(__('letter_c_land_title.placeholders.village'))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('number_of_c')
                            ->label(__('letter_c_land_title.fields.number_of_c'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('letter_c_land_title.placeholders.number_of_c')),
                        Forms\Components\TextInput::make('number_of_persil')
                            ->label(__('letter_c_land_title.fields.number_of_persil'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('letter_c_land_title.placeholders.number_of_persil')),
                        Forms\Components\TextInput::make('class')
                            ->label(__('letter_c_land_title.fields.class'))
                            ->maxLength(255)
                            ->placeholder(__('letter_c_land_title.placeholders.class')),
                        Forms\Components\DatePicker::make('date')
                            ->label(__('letter_c_land_title.fields.date'))
                            ->placeholder(__('letter_c_land_title.placeholders.date')),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('letter_c_land_title.sections.area_information'))
                    ->schema([
                        Forms\Components\TextInput::make('land_area')
                            ->label(__('letter_c_land_title.fields.land_area'))
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->step(0.01)
                            ->suffix('m²')
                            ->placeholder(__('letter_c_land_title.placeholders.land_area')),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('letter_c_land_title.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('letter_c_land_title.fields.village'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('number_of_c')
                    ->label(__('letter_c_land_title.fields.number_of_c'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('number_of_persil')
                    ->label(__('letter_c_land_title.fields.number_of_persil'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('class')
                    ->label(__('letter_c_land_title.fields.class'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('land_area')
                    ->label(__('letter_c_land_title.fields.land_area'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' m²')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('letter_c_land_title.fields.date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('land_titles_count')
                    ->label(__('letter_c_land_title.fields.references_count'))
                    ->counts('landTitles')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('letter_c_land_title.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('letter_c_land_title.filters.date_from')),
                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('letter_c_land_title.filters.date_until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['date_until'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
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
            'index' => Pages\ListLetterCLandTitles::route('/'),
            'create' => Pages\CreateLetterCLandTitle::route('/create'),
            'edit' => Pages\EditLetterCLandTitle::route('/{record}/edit'),
            'view' => Pages\ViewLetterCLandTitle::route('/{record}'),
        ];
    }
}
