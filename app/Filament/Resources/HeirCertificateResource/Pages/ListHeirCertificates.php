<?php

namespace App\Filament\Resources\HeirCertificateResource\Pages;

use App\Enums\CertificateStatus;
use App\Filament\Resources\HeirCertificateResource;
use App\Filament\Widgets\HeirCertificateOverview;
use App\Models\HeirCertificate;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListHeirCertificates extends ListRecords
{
    protected static string $resource = HeirCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export to Excel')
                ->color('gray')
                ->icon('heroicon-o-arrow-down-tray')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn () => 'heir_certificates_' . date('Y-m-d') . '_' . date('His'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ->withColumns([
                            \pxlrbt\FilamentExcel\Columns\Column::make('formatted_certificate_number')
                                ->heading('Certificate Number')
                                ->formatStateUsing(fn (HeirCertificate $record) => $record->formatted_certificate_number),
                            \pxlrbt\FilamentExcel\Columns\Column::make('certificate_date')
                                ->heading('Certificate Date')
                                ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y') : '-'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('status')
                                ->heading('Status')
                                ->formatStateUsing(fn (CertificateStatus $state) => $state->getLabel()),
                            \pxlrbt\FilamentExcel\Columns\Column::make('applicant_name')
                                ->heading('Applicant Name'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('applicant_address')
                                ->heading('Applicant Address'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('phone_number')
                                ->heading('Phone Number')
                                ->formatStateUsing(fn ($state) => $state ?? '-'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('deceased_name')
                                ->heading('Deceased Name'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('place_of_death')
                                ->heading('Place of Death'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('date_of_death')
                                ->heading('Date of Death')
                                ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y') : '-'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('personInCharge.name')
                                ->heading('Person In Charge')
                                ->formatStateUsing(fn ($state) => $state ?? 'Not assigned'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('heirs_count')
                                ->heading('Number of Heirs')
                                ->formatStateUsing(fn (HeirCertificate $record) => $record->heirs()->count()),
                            \pxlrbt\FilamentExcel\Columns\Column::make('heirs')
                                ->heading('Heirs Names')
                                ->formatStateUsing(fn (HeirCertificate $record) => $record->heirs->pluck('heir_name')->join(', ') ?: '-'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('creator.name')
                                ->heading('Created By')
                                ->formatStateUsing(fn ($state) => $state ?? '-'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('created_at')
                                ->heading('Created At')
                                ->formatStateUsing(fn ($state) => $state ? $state->format('d M Y H:i') : '-'),
                        ])
                ]),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Get the header widgets for the page.
     *
     * @return array
     */
    protected function getHeaderWidgets(): array
    {
        return [
            HeirCertificateOverview::class,
        ];
    }
}
