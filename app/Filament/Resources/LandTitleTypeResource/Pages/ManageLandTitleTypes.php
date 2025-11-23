<?php

namespace App\Filament\Resources\LandTitleTypeResource\Pages;

use App\Filament\Resources\LandTitleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLandTitleTypes extends ManageRecords
{
    protected static string $resource = LandTitleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
