<?php

namespace App\Filament\Resources\FamilyIdCardResource\Pages;

use App\Filament\Resources\FamilyIdCardResource;
use App\Models\FamilyIdCard;
use App\Models\WhatsAppCampaign;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewFamilyIdCard extends ViewRecord
{
    protected static string $resource = FamilyIdCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_whatsapp')
                ->label(__('family_id_card.actions.send_whatsapp'))
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->form(function (FamilyIdCard $record) {
                    return [
                        Forms\Components\Placeholder::make('applicant_info')
                            ->label(__('family_id_card.whatsapp.applicant_info'))
                            ->content(
                                __('family_id_card.whatsapp.applicant_details', [
                                    'name' => $record->name,
                                    'phone' => $record->phone_number,
                                    'registration' => $record->no_registration,
                                ])
                            )
                            ->columnSpanFull(),

                        Forms\Components\Select::make('campaign_id')
                            ->label(__('family_id_card.whatsapp.select_campaign'))
                            ->options(fn () => WhatsAppCampaign::where('is_active', true)
                                ->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->placeholder(__('family_id_card.whatsapp.campaign_placeholder'))
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('message_preview')
                            ->label(__('family_id_card.whatsapp.message_preview'))
                            ->content(function (Forms\Get $get) use ($record) {
                                if (!$get('campaign_id')) {
                                    return __('family_id_card.whatsapp.no_preview');
                                }

                                $campaign = WhatsAppCampaign::find($get('campaign_id'));

                                if (!$campaign) {
                                    return __('family_id_card.whatsapp.campaign_not_found');
                                }

                                $message = $campaign->template;

                                // Replace company name
                                $message = str_replace('[Name_Company]', $campaign->company_name, $message);

                                // Replace dynamic variables
                                if (!empty($campaign->variables)) {
                                    foreach ($campaign->variables as $variable) {
                                        $value = match($variable) {
                                            'name' => $record->name,
                                            'phone_number' => $record->phone_number,
                                            'no_registration' => $record->no_registration,
                                            'date' => $record->date->format('d M Y'),
                                            'due_date' => $record->due_date->format('d M Y'),
                                            'rejection_reason' => $record->rejection_reason ?? '',
                                            'status' => $record->status->getLabel(),
                                            default => null,
                                        };

                                        if ($value !== null) {
                                            $message = str_replace('[' . $variable . ']', $value, $message);
                                        }
                                    }
                                }

                                return new \Illuminate\Support\HtmlString(
                                    '<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">' .
                                    '<pre class="whitespace-pre-wrap break-words text-sm overflow-auto">' . e($message) . '</pre>' .
                                    '</div>'
                                );
                            })
                            ->visible(fn (Forms\Get $get) => (bool) $get('campaign_id'))
                            ->columnSpanFull(),
                    ];
                })
                ->action(function (array $data, FamilyIdCard $record) {
                    $campaign = WhatsAppCampaign::find($data['campaign_id']);

                    if (!$campaign) {
                        Notification::make()
                            ->danger()
                            ->title(__('family_id_card.notifications.campaign_not_found'))
                            ->send();
                        return;
                    }

                    // Collect variables
                    $variables = [];
                    if (!empty($campaign->variables)) {
                        foreach ($campaign->variables as $variable) {
                            $value = match($variable) {
                                'name' => $record->name,
                                'phone_number' => $record->phone_number,
                                'no_registration' => $record->no_registration,
                                'date' => $record->date->format('d M Y'),
                                'due_date' => $record->due_date->format('d M Y'),
                                'rejection_reason' => $record->rejection_reason ?? '',
                                'status' => $record->status->getLabel(),
                                default => null,
                            };

                            if ($value !== null) {
                                $variables[$variable] = $value;
                            }
                        }
                    }

                    // Send via Campaign facade
                    $result = \App\Facades\Campaign::send(
                        $campaign->name,
                        $record->phone_number,
                        $variables
                    );

                    if ($result['success']) {
                        Notification::make()
                            ->success()
                            ->title(__('family_id_card.notifications.message_sent'))
                            ->body(__('family_id_card.notifications.whatsapp_sent', [
                                'registration' => $record->no_registration
                            ]))
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title(__('family_id_card.notifications.failed_to_send'))
                            ->body($result['message'] ?? __('family_id_card.notifications.whatsapp_failed'))
                            ->persistent()
                            ->send();
                    }
                })
                ->modalHeading(__('family_id_card.whatsapp.modal_heading'))
                ->modalDescription(__('family_id_card.whatsapp.modal_description'))
                ->modalSubmitActionLabel(__('family_id_card.whatsapp.modal_submit'))
                ->modalWidth('lg')
                ->disabled(fn (FamilyIdCard $record): bool => empty($record->phone_number))
                ->tooltip(fn (FamilyIdCard $record): ?string =>
                    empty($record->phone_number) ? __('family_id_card.whatsapp.phone_not_set') : null
                ),

            Actions\EditAction::make()
                ->label(__('family_id_card.actions.edit')),
        ];
    }
}
