<?php

namespace App\Filament\Resources\TeamPpatResource\Pages;

use App\Filament\Resources\TeamPpatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeamPpat extends ViewRecord
{
    protected static string $resource = TeamPpatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
