<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentLegalizationResource\Pages;
use App\Models\DocumentLegalization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentLegalizationResource extends Resource
{
    protected static ?string $model = DocumentLegalization::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.civil_registration');
    }

    public static function getNavigationLabel(): string
    {
        return __('document_legalization.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('document_legalization.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('document_legalization.model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('document_legalization.sections.legalization_info'))
                    ->schema([
                        Forms\Components\TextInput::make('number_legalization')
                            ->label(__('document_legalization.fields.number_legalization'))
                            ->disabled()
                            ->dehydrated()
                            ->placeholder(__('document_legalization.placeholders.auto_generated')),

                        Forms\Components\DatePicker::make('date')
                            ->label(__('document_legalization.fields.date'))
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\TextInput::make('type_of_document')
                            ->label(__('document_legalization.fields.type_of_document'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('main_content_of_document')
                            ->label(__('document_legalization.fields.main_content_of_document'))
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('document_legalization.sections.applicant_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('document_legalization.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('occupation')
                            ->label(__('document_legalization.fields.occupation'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label(__('document_legalization.fields.address'))
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('village_id')
                            ->label(__('document_legalization.fields.village'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('village', 'name')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('note')
                            ->label(__('document_legalization.fields.note'))
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
                Tables\Columns\TextColumn::make('number_legalization')
                    ->label(__('document_legalization.fields.number_legalization'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('document_legalization.fields.date'))
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('document_legalization.fields.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type_of_document')
                    ->label(__('document_legalization.fields.type_of_document'))
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('document_legalization.fields.village'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('document_legalization.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('document_legalization.filters.date_from')),
                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('document_legalization.filters.date_until')),
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
            'index' => Pages\ListDocumentLegalizations::route('/'),
            'create' => Pages\CreateDocumentLegalization::route('/create'),
            'view' => Pages\ViewDocumentLegalization::route('/{record}'),
            'edit' => Pages\EditDocumentLegalization::route('/{record}/edit'),
        ];
    }
}
