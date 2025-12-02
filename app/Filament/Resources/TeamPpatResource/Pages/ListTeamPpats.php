<?php

namespace App\Filament\Resources\TeamPpatResource\Pages;

use App\Filament\Resources\TeamPpatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamPpats extends ListRecords
{
    protected static string $resource = TeamPpatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
