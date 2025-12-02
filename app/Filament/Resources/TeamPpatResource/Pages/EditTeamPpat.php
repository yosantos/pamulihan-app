<?php

namespace App\Filament\Resources\TeamPpatResource\Pages;

use App\Filament\Resources\TeamPpatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamPpat extends EditRecord
{
    protected static string $resource = TeamPpatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
