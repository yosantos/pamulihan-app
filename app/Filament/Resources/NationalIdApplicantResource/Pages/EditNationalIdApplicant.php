<?php

namespace App\Filament\Resources\NationalIdApplicantResource\Pages;

use App\Filament\Resources\NationalIdApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNationalIdApplicant extends EditRecord
{
    protected static string $resource = NationalIdApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
