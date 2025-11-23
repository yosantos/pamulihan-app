<?php

namespace App\Filament\Resources\LandTitleResource\Pages;

use App\Filament\Resources\LandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandTitles extends ListRecords
{
    protected static string $resource = LandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
