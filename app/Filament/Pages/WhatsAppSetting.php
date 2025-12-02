<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class WhatsAppSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string $view = 'filament.pages.whats-app-setting';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 20;

    public ?array $whatsappStatus = null;

    public bool $isLoading = true;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.communication');
    }

    public static function getNavigationLabel(): string
    {
        return __('whatsapp_setting.navigation_label');
    }

    public function getTitle(): string
    {
        return __('whatsapp_setting.title');
    }

    public function mount(): void
    {
        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        $this->isLoading = true;

        try {
            $apiKey = config('services.whatsapp.api_key');
            $baseUrl = config('services.whatsapp.base_url');

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
            ])->get("{$baseUrl}/api/status");

            if ($response->successful()) {
                $this->whatsappStatus = $response->json();
            } else {
                Notification::make()
                    ->danger()
                    ->title(__('whatsapp_setting.notifications.status_check_failed'))
                    ->body($response->body())
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('whatsapp_setting.notifications.connection_error'))
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isLoading = false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label(__('whatsapp_setting.actions.refresh'))
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->checkStatus()),

            Action::make('logout')
                ->label(__('whatsapp_setting.actions.logout'))
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('danger')
                ->visible(fn () => $this->whatsappStatus && ($this->whatsappStatus['status'] ?? '') === 'logged_in')
                ->requiresConfirmation()
                ->modalHeading(__('whatsapp_setting.modals.logout.heading'))
                ->modalDescription(__('whatsapp_setting.modals.logout.description'))
                ->modalSubmitActionLabel(__('whatsapp_setting.modals.logout.submit'))
                ->action(function () {
                    try {
                        $apiKey = config('services.whatsapp.api_key');
                        $baseUrl = config('services.whatsapp.base_url');

                        $response = Http::withHeaders([
                            'X-API-Key' => $apiKey,
                        ])->post("{$baseUrl}/api/logout");

                        if ($response->successful()) {
                            Notification::make()
                                ->success()
                                ->title(__('whatsapp_setting.notifications.logout_success'))
                                ->send();

                            // Refresh status after logout
                            $this->checkStatus();
                        } else {
                            Notification::make()
                                ->danger()
                                ->title(__('whatsapp_setting.notifications.logout_failed'))
                                ->body($response->body())
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title(__('whatsapp_setting.notifications.logout_error'))
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    public function getStatus(): ?string
    {
        return $this->whatsappStatus['status'] ?? null;
    }

    public function getQrCode(): ?string
    {
        return $this->whatsappStatus['qrCode'] ?? null;
    }

    public function getUser(): ?array
    {
        return $this->whatsappStatus['user'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->whatsappStatus['message'] ?? null;
    }

    public function isLoggedIn(): bool
    {
        return $this->getStatus() === 'logged_in';
    }

    public function isNotLoggedIn(): bool
    {
        return $this->getStatus() === 'not_logged_in';
    }
}
