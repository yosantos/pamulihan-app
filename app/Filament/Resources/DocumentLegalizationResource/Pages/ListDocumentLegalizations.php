<?php

namespace App\Filament\Resources\DocumentLegalizationResource\Pages;

use App\Filament\Resources\DocumentLegalizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentLegalizations extends ListRecords
{
    protected static string $resource = DocumentLegalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
