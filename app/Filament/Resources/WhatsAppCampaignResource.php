<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppCampaignResource\Pages;
use App\Filament\Resources\WhatsAppCampaignResource\RelationManagers;
use App\Models\WhatsAppCampaign;
use App\Services\TemplateVariableParser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class WhatsAppCampaignResource extends Resource
{
    protected static ?string $model = WhatsAppCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('whatsapp_campaign.navigation');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('whatsapp_campaign.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('whatsapp_campaign.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('whatsapp_campaign.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('whatsapp_campaign.sections.campaign_information.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('whatsapp_campaign.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('whatsapp_campaign.placeholders.name'))
                            ->helperText(__('whatsapp_campaign.helpers.name')),

                        Forms\Components\TextInput::make('company_name')
                            ->label(__('whatsapp_campaign.fields.company_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('whatsapp_campaign.placeholders.company_name'))
                            ->helperText(__('whatsapp_campaign.helpers.company_name')),

                        Forms\Components\Textarea::make('description')
                            ->label(__('whatsapp_campaign.fields.description'))
                            ->rows(2)
                            ->placeholder(__('whatsapp_campaign.placeholders.description'))
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('whatsapp_campaign.fields.is_active'))
                            ->default(true)
                            ->helperText(__('whatsapp_campaign.helpers.is_active')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('whatsapp_campaign.sections.message_template.title'))
                    ->schema([
                        Forms\Components\Textarea::make('template')
                            ->label(__('whatsapp_campaign.fields.template'))
                            ->required()
                            ->rows(5)
                            ->placeholder(__('whatsapp_campaign.placeholders.template'))
                            ->helperText(__('whatsapp_campaign.helpers.template'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (empty($state)) {
                                    return;
                                }

                                $parser = new TemplateVariableParser();
                                $variables = $parser->extractVariables($state);

                                // Remove Name_Company as it's a static variable
                                $variables = array_filter($variables, fn($var) => $var !== 'Name_Company');

                                // Set the dynamic variables
                                $set('variables', array_values($variables));
                            })
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('template_preview')
                            ->label(__('whatsapp_campaign.template.character_count', ['count' => 0]))
                            ->content(function ($get) {
                                $template = $get('template');
                                if (empty($template)) {
                                    return __('whatsapp_campaign.template.character_count', ['count' => 0]);
                                }
                                $count = strlen($template);
                                $color = $count > 1000 ? 'danger' : ($count > 500 ? 'warning' : 'success');
                                return new \Illuminate\Support\HtmlString(
                                    "<span class='text-{$color}-600 font-semibold'>" . __('whatsapp_campaign.template.character_count', ['count' => $count]) . "</span>"
                                );
                            }),

                        Forms\Components\Placeholder::make('detected_variables')
                            ->label(__('whatsapp_campaign.template.detected_variables'))
                            ->content(function ($get) {
                                $template = $get('template');
                                if (empty($template)) {
                                    return __('whatsapp_campaign.template.no_variables');
                                }

                                $parser = new TemplateVariableParser();
                                $variables = $parser->extractVariables($template);

                                if (empty($variables)) {
                                    return __('whatsapp_campaign.template.no_variables');
                                }

                                $tags = collect($variables)->map(function ($var) {
                                    $isStatic = $var === 'Name_Company';
                                    $badge = $isStatic ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
                                    $label = $isStatic ? __('whatsapp_campaign.template.static_label') : __('whatsapp_campaign.template.dynamic_label');
                                    return "<span class='inline-flex items-center gap-1 px-2 py-1 rounded text-xs {$badge}'>[{$var}] <span class='text-xs opacity-75'>({$label})</span></span>";
                                })->implode(' ');

                                return new \Illuminate\Support\HtmlString($tags);
                            })
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('whatsapp_campaign.sections.dynamic_variables.title'))
                    ->schema([
                        Forms\Components\TagsInput::make('variables')
                            ->label(__('whatsapp_campaign.fields.variables'))
                            ->placeholder(__('whatsapp_campaign.placeholders.variables'))
                            ->helperText(__('whatsapp_campaign.helpers.variables'))
                            ->columnSpanFull(),
                    ])
                    ->description(__('whatsapp_campaign.sections.dynamic_variables.description')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('whatsapp_campaign.columns.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('whatsapp_campaign.columns.company'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('template')
                    ->label(__('whatsapp_campaign.columns.template_preview'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('variables')
                    ->label(__('whatsapp_campaign.columns.variables'))
                    ->badge()
                    ->color(Color::Blue)
                    ->getStateUsing(function (WhatsAppCampaign $record) {
                        return $record->variables ?? [];
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('whatsapp_campaign.columns.active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label(__('whatsapp_campaign.columns.usage'))
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(function ($state) {
                        if ($state == 0) return Color::Gray;
                        if ($state < 10) return Color::Blue;
                        if ($state < 50) return Color::Green;
                        return Color::Orange;
                    }),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('whatsapp_campaign.columns.created_by'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('whatsapp_campaign.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('whatsapp_campaign.filters.status'))
                    ->placeholder(__('whatsapp_campaign.filters.all_campaigns'))
                    ->trueLabel(__('whatsapp_campaign.filters.active_campaigns'))
                    ->falseLabel(__('whatsapp_campaign.filters.inactive_campaigns')),

                Tables\Filters\Filter::make('has_usage')
                    ->label(__('whatsapp_campaign.filters.has_usage'))
                    ->query(fn (Builder $query) => $query->where('usage_count', '>', 0)),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label(__('whatsapp_campaign.actions.preview'))
                    ->icon('heroicon-o-eye')
                    ->color(Color::Gray)
                    ->modalHeading(__('whatsapp_campaign.preview.heading'))
                    ->modalContent(function (WhatsAppCampaign $record) {
                        $variables = $record->getDynamicVariables();
                        $sampleValues = [];

                        foreach ($variables as $var) {
                            $sampleValues[$var] = "[{$var}]";
                        }

                        $preview = $record->replaceVariables($sampleValues);

                        return view('filament.campaign-preview', [
                            'campaign' => $record,
                            'preview' => $preview,
                            'variables' => $variables,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('whatsapp_campaign.preview.close')),

                Tables\Actions\Action::make('duplicate')
                    ->label(__('whatsapp_campaign.actions.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color(Color::Gray)
                    ->requiresConfirmation()
                    ->action(function (WhatsAppCampaign $record) {
                        $newCampaign = $record->replicate();
                        $newCampaign->name = $record->name . ' (Copy)';
                        $newCampaign->usage_count = 0;
                        $newCampaign->created_by = auth()->id();
                        $newCampaign->save();

                        Notification::make()
                            ->success()
                            ->title(__('whatsapp_campaign.notifications.duplicated.title'))
                            ->body(__('whatsapp_campaign.notifications.duplicated.body', ['name' => $record->name]))
                            ->send();
                    }),

                Tables\Actions\Action::make('send_message')
                    ->label(__('whatsapp_campaign.actions.send_message'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color(Color::Blue)
                    ->url(fn (WhatsAppCampaign $record) => WhatsAppMessageResource::getUrl('create', ['campaign' => $record->id])),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription(__('whatsapp_campaign.modals.delete.description')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('whatsapp_campaign.actions.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->color(Color::Green)
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);

                            Notification::make()
                                ->success()
                                ->title(__('whatsapp_campaign.notifications.activated.title'))
                                ->body(__('whatsapp_campaign.notifications.activated.body', ['count' => count($records)]))
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('whatsapp_campaign.actions.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->color(Color::Orange)
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);

                            Notification::make()
                                ->success()
                                ->title(__('whatsapp_campaign.notifications.deactivated.title'))
                                ->body(__('whatsapp_campaign.notifications.deactivated.body', ['count' => count($records)]))
                                ->send();
                        }),
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
            'index' => Pages\ListWhatsAppCampaigns::route('/'),
            'create' => Pages\CreateWhatsAppCampaign::route('/create'),
            'view' => Pages\ViewWhatsAppCampaign::route('/{record}'),
            'edit' => Pages\EditWhatsAppCampaign::route('/{record}/edit'),
        ];
    }
}
