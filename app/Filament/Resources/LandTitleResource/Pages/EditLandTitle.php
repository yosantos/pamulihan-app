<?php

namespace App\Filament\Resources\LandTitleResource\Pages;

use App\Filament\Resources\LandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandTitle extends EditRecord
{
    protected static string $resource = LandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
