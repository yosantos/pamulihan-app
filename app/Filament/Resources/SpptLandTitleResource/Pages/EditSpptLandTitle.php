<?php

namespace App\Filament\Resources\SpptLandTitleResource\Pages;

use App\Filament\Resources\SpptLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpptLandTitle extends EditRecord
{
    protected static string $resource = SpptLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
