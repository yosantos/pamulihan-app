<?php

namespace App\Filament\Resources\NationalIdApplicantResource\Pages;

use App\Filament\Resources\NationalIdApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNationalIdApplicants extends ListRecords
{
    protected static string $resource = NationalIdApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
