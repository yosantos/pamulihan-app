<?php

namespace App\Filament\Resources\BankLoanRegistrationResource\Pages;

use App\Filament\Resources\BankLoanRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankLoanRegistration extends EditRecord
{
    protected static string $resource = BankLoanRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
