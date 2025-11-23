<?php

namespace App\Filament\Resources\LetterCLandTitleResource\Pages;

use App\Filament\Resources\LetterCLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLetterCLandTitles extends ListRecords
{
    protected static string $resource = LetterCLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
