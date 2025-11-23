<?php

namespace App\Filament\Resources\HeirCertificateResource\Pages;

use App\Enums\CertificateStatus;
use App\Filament\Resources\HeirCertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewHeirCertificate extends ViewRecord
{
    protected static string $resource = HeirCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Certificate Information')
                    ->schema([
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('formatted_certificate_number')
                                    ->label('Certificate Number')
                                    ->icon('heroicon-o-hashtag')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->size('lg'),

                                Components\TextEntry::make('certificate_date')
                                    ->label('Certificate Date')
                                    ->date('d M Y')
                                    ->icon('heroicon-o-calendar'),

                                Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (CertificateStatus $state): string => $state->getColor())
                                    ->formatStateUsing(fn (CertificateStatus $state): string => $state->getLabel()),
                            ]),

                        Components\TextEntry::make('personInCharge.name')
                            ->label('Person In Charge (PIC)')
                            ->icon('heroicon-o-user-circle')
                            ->placeholder('Not assigned')
                            ->default('Not assigned')
                            ->helperText('The person responsible for handling this certificate')
                            ->badge()
                            ->color('info'),

                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('applicant_name')
                                    ->label('Applicant Name')
                                    ->icon('heroicon-o-user'),

                                Components\TextEntry::make('phone_number')
                                    ->label('Phone Number')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->copyMessage('Phone number copied!')
                                    ->placeholder('Not provided'),
                            ]),

                        Components\TextEntry::make('applicant_address')
                            ->label('Applicant Address')
                            ->icon('heroicon-o-map-pin')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Components\Section::make('Deceased Information')
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('deceased_name')
                                    ->label('Deceased Name')
                                    ->icon('heroicon-o-user'),

                                Components\TextEntry::make('place_of_death')
                                    ->label('Place of Death')
                                    ->icon('heroicon-o-map-pin'),

                                Components\TextEntry::make('date_of_death')
                                    ->label('Date of Death')
                                    ->date('d M Y')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Components\Section::make('Heirs Information')
                    ->schema([
                        Components\RepeatableEntry::make('heirs')
                            ->label('')
                            ->schema([
                                Components\Grid::make(3)
                                    ->schema([
                                        Components\TextEntry::make('heir_name')
                                            ->label('Heir Name')
                                            ->icon('heroicon-o-user')
                                            ->weight('bold'),

                                        Components\TextEntry::make('heir_address')
                                            ->label('Heir Address')
                                            ->icon('heroicon-o-map-pin')
                                            ->placeholder('Not provided'),

                                        Components\TextEntry::make('relationship')
                                            ->label('Relationship')
                                            ->icon('heroicon-o-link')
                                            ->badge()
                                            ->color('info')
                                            ->placeholder('Not specified'),
                                    ]),
                            ])
                            ->columns(1),
                    ])
                    ->collapsible(),

                Components\Section::make('System Information')
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('creator.name')
                                    ->label('Created By')
                                    ->icon('heroicon-o-user')
                                    ->placeholder('System'),

                                Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime('d M Y H:i')
                                    ->icon('heroicon-o-clock'),

                                Components\TextEntry::make('updater.name')
                                    ->label('Updated By')
                                    ->icon('heroicon-o-user')
                                    ->placeholder('System'),

                                Components\TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime('d M Y H:i')
                                    ->icon('heroicon-o-clock'),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
