<?php

namespace App\Filament\Resources\FamilyIdCardResource\Pages;

use App\Filament\Resources\FamilyIdCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyIdCards extends ListRecords
{
    protected static string $resource = FamilyIdCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('family_id_card.actions.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
