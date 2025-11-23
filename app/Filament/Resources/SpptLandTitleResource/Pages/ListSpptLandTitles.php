<?php

namespace App\Filament\Resources\SpptLandTitleResource\Pages;

use App\Filament\Resources\SpptLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpptLandTitles extends ListRecords
{
    protected static string $resource = SpptLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
