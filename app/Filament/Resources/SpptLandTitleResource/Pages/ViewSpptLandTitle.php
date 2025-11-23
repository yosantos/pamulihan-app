<?php

namespace App\Filament\Resources\SpptLandTitleResource\Pages;

use App\Filament\Resources\SpptLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpptLandTitle extends ViewRecord
{
    protected static string $resource = SpptLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
