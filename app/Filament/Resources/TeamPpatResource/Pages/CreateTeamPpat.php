<?php

namespace App\Filament\Resources\TeamPpatResource\Pages;

use App\Filament\Resources\TeamPpatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamPpat extends CreateRecord
{
    protected static string $resource = TeamPpatResource::class;

    protected function afterCreate(): void
    {
        // Assign team_ppat role to the newly created user
        $this->record->assignRole('team_ppat');
    }
}
