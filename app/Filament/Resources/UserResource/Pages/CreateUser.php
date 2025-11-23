<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Mutate form data before creating the record
     * Auto-generate email and password if not provided
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate email if not provided
        if (empty($data['email'])) {
            $data['email'] = $this->generateEmailFromName($data['name']);
        }

        // Auto-generate password if not provided
        if (empty($data['password'])) {
            $data['password'] = $this->generateRandomPassword();
        }

        return $data;
    }

    /**
     * Generate email from name with @mypamulihan.com suffix
     * Ensures uniqueness by appending numbers if necessary
     */
    protected function generateEmailFromName(string $name): string
    {
        // Convert name to lowercase and replace spaces with dots
        $baseEmail = Str::slug(Str::lower($name), '.');
        $suffix = '@mypamulihan.com';

        $email = $baseEmail . $suffix;

        // Check if email already exists, append number if needed
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . $suffix;
            $counter++;
        }

        return $email;
    }

    /**
     * Generate a random secure password
     * 12 characters with letters, numbers, and symbols
     */
    protected function generateRandomPassword(int $length = 12): string
    {
        return Str::password($length, true, true, false, false);
    }
}
