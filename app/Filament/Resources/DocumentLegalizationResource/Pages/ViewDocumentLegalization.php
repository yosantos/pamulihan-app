<?php

namespace App\Filament\Resources\DocumentLegalizationResource\Pages;

use App\Filament\Resources\DocumentLegalizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentLegalization extends ViewRecord
{
    protected static string $resource = DocumentLegalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
