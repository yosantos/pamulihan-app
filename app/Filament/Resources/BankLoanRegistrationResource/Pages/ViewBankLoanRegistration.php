<?php

namespace App\Filament\Resources\BankLoanRegistrationResource\Pages;

use App\Filament\Resources\BankLoanRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBankLoanRegistration extends ViewRecord
{
    protected static string $resource = BankLoanRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
