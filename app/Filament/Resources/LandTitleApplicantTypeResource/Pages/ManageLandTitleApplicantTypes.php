<?php

namespace App\Filament\Resources\LandTitleApplicantTypeResource\Pages;

use App\Filament\Resources\LandTitleApplicantTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLandTitleApplicantTypes extends ManageRecords
{
    protected static string $resource = LandTitleApplicantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
