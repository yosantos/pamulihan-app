<?php

namespace App\Filament\Resources\NationalIdApplicantResource\Pages;

use App\Filament\Resources\NationalIdApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNationalIdApplicant extends ViewRecord
{
    protected static string $resource = NationalIdApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
