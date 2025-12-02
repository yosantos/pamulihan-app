<?php

namespace App\Filament\Resources\DocumentLegalizationResource\Pages;

use App\Filament\Resources\DocumentLegalizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentLegalization extends EditRecord
{
    protected static string $resource = DocumentLegalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
