<?php

namespace App\Filament\Resources\LandTitleResource\Pages;

use App\Filament\Resources\LandTitleResource;
use App\Services\LandTitleDocumentService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLandTitle extends ViewRecord
{
    protected static string $resource = LandTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_document')
                ->label(__('land_title.actions.generate_document'))
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    try {
                        $service = app(LandTitleDocumentService::class);
                        $filePath = $service->generate($this->record);

                        Notification::make()
                            ->title(__('land_title.notifications.document_generated'))
                            ->success()
                            ->send();

                        return response()->download($filePath)->deleteFileAfterSend();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('land_title.notifications.document_generation_failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading(__('land_title.modals.generate_document.heading'))
                ->modalDescription(__('land_title.modals.generate_document.description'))
                ->modalSubmitActionLabel(__('land_title.modals.generate_document.submit'))
                ->modalCancelActionLabel(__('land_title.modals.generate_document.cancel')),
            Actions\EditAction::make(),
        ];
    }
}
