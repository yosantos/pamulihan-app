<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Campaign Message Service
        $this->app->singleton('campaign.message.service', function ($app) {
            return new \App\Services\CampaignMessageService(
                $app->make(\App\Services\WhatsAppService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Model Observers
        \App\Models\LandTitle::observe(\App\Observers\LandTitleObserver::class);

        // Configure Filament Language Switcher
        \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::configureUsing(function (\BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch $switch) {
            $switch
                ->locales(['id', 'en'])
                ->labels([
                    'id' => 'Bahasa Indonesia',
                    'en' => 'English',
                ])
                ->circular();
        });
    }
}
