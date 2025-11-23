<?php

namespace App\Filament\Resources\WhatsAppCampaignResource\Pages;

use App\Filament\Resources\WhatsAppCampaignResource;
use App\Filament\Resources\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewWhatsAppCampaign extends ViewRecord
{
    protected static string $resource = WhatsAppCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_message')
                ->label('Send Message')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->url(fn () => WhatsAppMessageResource::getUrl('create', ['campaign' => $this->record->id])),

            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to delete this campaign? Messages sent using this campaign will remain but will lose the campaign reference.'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Campaign Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Campaign Name'),

                        Infolists\Components\TextEntry::make('company_name')
                            ->label('Company Name'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('No description provided')
                            ->columnSpanFull(),

                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Template')
                    ->schema([
                        Infolists\Components\TextEntry::make('template')
                            ->label('Message Template')
                            ->columnSpanFull()
                            ->prose(),

                        Infolists\Components\TextEntry::make('variables')
                            ->label('Dynamic Variables')
                            ->badge()
                            ->color('blue')
                            ->default(['None']),
                    ]),

                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('usage_count')
                            ->label('Times Used')
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                        Infolists\Components\TextEntry::make('success_rate')
                            ->label('Success Rate')
                            ->formatStateUsing(fn () => $this->record->getSuccessRate() . '%')
                            ->badge()
                            ->color(fn () => $this->record->getSuccessRate() >= 80 ? 'success' : ($this->record->getSuccessRate() >= 50 ? 'warning' : 'danger')),

                        Infolists\Components\TextEntry::make('last_used')
                            ->label('Last Used')
                            ->formatStateUsing(fn () => $this->record->getLastUsedAt()?->diffForHumans() ?? 'Never')
                            ->placeholder('Never'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('creator.name')
                            ->label('Created By'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }
}
