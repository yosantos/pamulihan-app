<?php

namespace App\Filament\Resources\LetterCLandTitleResource\Pages;

use App\Filament\Resources\LetterCLandTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLetterCLandTitle extends EditRecord
{
    protected static string $resource = LetterCLandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
