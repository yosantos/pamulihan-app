<?php

namespace App\Filament\Resources\WhatsAppMessageResource\Pages;

use App\Filament\Resources\WhatsAppMessageResource;
use App\Models\WhatsAppMessage;
use App\Services\WhatsAppService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsAppMessage extends ViewRecord
{
    protected static string $resource = WhatsAppMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Messages')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),

            Actions\Action::make('resend')
                ->label('Resend Message')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Resend WhatsApp Message')
                ->modalDescription(function (): string {
                    $record = $this->getRecord();
                    return "Are you sure you want to resend this message to {$record->phone_number}?";
                })
                ->modalSubmitActionLabel('Yes, Resend')
                ->action(function () {
                    $record = $this->getRecord();
                    $whatsappService = app(WhatsAppService::class);

                    try {
                        // Increment retry count
                        $record->incrementRetryCount();

                        // Attempt to send the message
                        $whatsappService->send($record->phone_number, $record->message);

                        // Mark as sent with current user
                        $record->markAsSent(Filament::auth()->id());

                        // Refresh the page data
                        $this->refreshFormData([
                            'status',
                            'sent_at',
                            'error_message',
                            'retry_count',
                            'sent_by',
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Message Resent Successfully')
                            ->body("Message successfully sent to {$record->phone_number}")
                            ->send();

                    } catch (\Exception $e) {
                        // Mark as failed with error
                        $record->markAsFailed($e->getMessage());

                        // Refresh the page data
                        $this->refreshFormData([
                            'status',
                            'error_message',
                            'retry_count',
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Failed to Resend Message')
                            ->body($e->getMessage())
                            ->persistent()
                            ->send();
                    }
                })
                ->visible(function (): bool {
                    return $this->getRecord()->isFailed();
                }),

            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    /**
     * Get custom title for the page
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'View WhatsApp Message';
    }
}
