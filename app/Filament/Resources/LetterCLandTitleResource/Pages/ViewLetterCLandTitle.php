<?php

namespace App\Filament\Resources\LetterCLandTitleResource\Pages;

use App\Filament\Resources\LetterCLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLetterCLandTitle extends ViewRecord
{
    protected static string $resource = LetterCLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
