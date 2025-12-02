<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NationalIdApplicantResource\Pages;
use App\Filament\Resources\NationalIdApplicantResource\RelationManagers;
use App\Models\NationalIdApplicant;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NationalIdApplicantResource extends Resource
{
    protected static ?string $model = NationalIdApplicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.civil_registration');
    }

    public static function getNavigationLabel(): string
    {
        return __('national_id_applicant.navigation_label');
    }

    public static function getPluralLabel(): string
    {
        return __('national_id_applicant.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('national_id_applicant.model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('national_id_applicant.sections.applicant_information'))
                    ->schema([
                        Forms\Components\TextInput::make('no_register')
                            ->label(__('national_id_applicant.fields.no_register'))
                            ->disabled()
                            ->dehydrated()
                            ->placeholder(__('national_id_applicant.placeholders.auto_generated')),

                        Forms\Components\DatePicker::make('date')
                            ->label(__('national_id_applicant.fields.date'))
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\TextInput::make('national_id_number')
                            ->label(__('national_id_applicant.fields.national_id_number'))
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('name')
                            ->label(__('national_id_applicant.fields.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('sex')
                            ->label(__('national_id_applicant.fields.sex'))
                            ->required()
                            ->options([
                                'f' => __('national_id_applicant.sex_options.female'),
                                'm' => __('national_id_applicant.sex_options.male'),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label(__('national_id_applicant.fields.address'))
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('village_id')
                            ->label(__('national_id_applicant.fields.village'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('village', 'name')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_register')
                    ->label(__('national_id_applicant.fields.no_register'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('national_id_applicant.fields.date'))
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('national_id_number')
                    ->label(__('national_id_applicant.fields.national_id_number'))
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('national_id_applicant.fields.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sex')
                    ->label(__('national_id_applicant.fields.sex'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string =>
                        $state === 'f'
                            ? __('national_id_applicant.sex_options.female')
                            : __('national_id_applicant.sex_options.male')
                    )
                    ->color(fn (string $state): string => $state === 'f' ? 'pink' : 'blue'),

                Tables\Columns\TextColumn::make('village.name')
                    ->label(__('national_id_applicant.fields.village'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('national_id_applicant.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->label(__('national_id_applicant.fields.sex'))
                    ->options([
                        'f' => __('national_id_applicant.sex_options.female'),
                        'm' => __('national_id_applicant.sex_options.male'),
                    ]),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label(__('national_id_applicant.filters.date_from')),
                        Forms\Components\DatePicker::make('date_until')
                            ->label(__('national_id_applicant.filters.date_until')),
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
            'index' => Pages\ListNationalIdApplicants::route('/'),
            'create' => Pages\CreateNationalIdApplicant::route('/create'),
            'view' => Pages\ViewNationalIdApplicant::route('/{record}'),
            'edit' => Pages\EditNationalIdApplicant::route('/{record}/edit'),
        ];
    }
}
