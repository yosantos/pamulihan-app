<?php

namespace App\Examples;

/**
 * Campaign Integration Examples
 *
 * This file contains comprehensive examples showing how to integrate
 * the Campaign Message Service in various parts of your application.
 *
 * NOTE: This is an example/reference file only - not meant to be executed directly
 */

// ============================================================================
// EXAMPLE 1: Using in Filament Resource Create Page
// ============================================================================

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Services\CampaignMessageService;

class CreateProduct extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\ProductResource::class;

    /**
     * Send campaign message after product is created
     */
    protected function afterCreate(): void
    {
        $campaign = app(CampaignMessageService::class);

        // Get the user's phone number (assuming product has user relationship)
        if ($this->record->user && $this->record->user->phone) {
            $result = $campaign->sendCampaignByName(
                campaignName: 'Product Created Notification',
                phoneNumber: $this->record->user->phone,
                variables: [
                    'user_name' => $this->record->user->name,
                    'product_name' => $this->record->name,
                    'price' => number_format($this->record->price, 0, ',', '.'),
                    'sku' => $this->record->sku,
                ],
                userId: auth()->id()
            );

            // Handle the result
            if ($result['success']) {
                \Filament\Notifications\Notification::make()
                    ->success()
                    ->title('Product created and notification sent')
                    ->body('WhatsApp notification was sent successfully.')
                    ->send();
            } else {
                \Filament\Notifications\Notification::make()
                    ->warning()
                    ->title('Product created but notification failed')
                    ->body($result['message'])
                    ->send();
            }
        }
    }
}

// ============================================================================
// EXAMPLE 2: Using in Filament Resource Edit Page
// ============================================================================

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Services\CampaignMessageService;

class EditOrder extends EditRecord
{
    protected static string $resource = \App\Filament\Resources\OrderResource::class;

    /**
     * Send campaign message when order status changes
     */
    protected function afterSave(): void
    {
        // Check if status was changed
        if ($this->record->wasChanged('status')) {
            $campaign = app(CampaignMessageService::class);

            // Different campaigns based on status
            $campaignMap = [
                'processing' => 'Order Processing Notification',
                'shipped' => 'Order Shipped Notification',
                'delivered' => 'Order Delivered Notification',
                'cancelled' => 'Order Cancelled Notification',
            ];

            $campaignName = $campaignMap[$this->record->status] ?? null;

            if ($campaignName && $this->record->customer_phone) {
                $campaign->sendCampaignByName(
                    campaignName: $campaignName,
                    phoneNumber: $this->record->customer_phone,
                    variables: [
                        'order_id' => $this->record->order_number,
                        'customer_name' => $this->record->customer_name,
                        'status' => ucfirst($this->record->status),
                        'total' => number_format($this->record->total, 0, ',', '.'),
                    ]
                );
            }
        }
    }
}

// ============================================================================
// EXAMPLE 3: Using the Facade (Shortest Syntax)
// ============================================================================

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Facades\Campaign;

class CreateUser extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\UserResource::class;

    protected function afterCreate(): void
    {
        // Using the Campaign facade - cleanest syntax
        Campaign::send('Welcome Campaign', $this->record->phone, [
            'user_name' => $this->record->name,
            'email' => $this->record->email,
            'account_id' => $this->record->id,
        ]);
    }
}

// ============================================================================
// EXAMPLE 4: Using the Trait in Models
// ============================================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SendsCampaignMessages;

class Order extends Model
{
    use SendsCampaignMessages;

    /**
     * Send confirmation message when order is created
     */
    public function sendConfirmation(): array
    {
        return $this->sendCampaign(
            'Order Confirmation',
            $this->customer_phone,
            [
                'order_id' => $this->order_number,
                'customer_name' => $this->customer_name,
                'total' => number_format($this->total, 0, ',', '.'),
                'items_count' => $this->items->count(),
            ]
        );
    }

    /**
     * Send shipping notification
     */
    public function sendShippingNotification(string $trackingNumber): array
    {
        return $this->sendCampaign(
            'Order Shipped Notification',
            $this->customer_phone,
            [
                'order_id' => $this->order_number,
                'customer_name' => $this->customer_name,
                'tracking_number' => $trackingNumber,
                'courier' => $this->courier_name,
            ]
        );
    }
}

// Then use in your code:
// $order = Order::create($data);
// $order->sendConfirmation();

// ============================================================================
// EXAMPLE 5: Using in Controllers
// ============================================================================

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CampaignMessageService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected CampaignMessageService $campaignService
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        // Create order
        $order = Order::create($validated);

        // Send confirmation campaign
        $result = $this->campaignService->sendCampaignByName(
            'Order Confirmation',
            $order->customer_phone,
            [
                'order_id' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => number_format($order->total, 0, ',', '.'),
            ]
        );

        if (!$result['success']) {
            // Log the error but don't fail the request
            \Log::warning('Failed to send order confirmation', [
                'order_id' => $order->id,
                'error' => $result['message'],
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully');
    }

    public function updateStatus(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Send status update notification
        if ($oldStatus !== $validated['status']) {
            $this->campaignService->sendCampaignByName(
                'Order Status Updated',
                $order->customer_phone,
                [
                    'order_id' => $order->order_number,
                    'old_status' => ucfirst($oldStatus),
                    'new_status' => ucfirst($validated['status']),
                ]
            );
        }

        return back()->with('success', 'Order status updated');
    }
}

// ============================================================================
// EXAMPLE 6: Using in Event Listeners
// ============================================================================

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\CampaignMessageService;

class SendOrderConfirmation
{
    public function __construct(
        protected CampaignMessageService $campaignService
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $this->campaignService->sendCampaignByName(
            'Order Confirmation',
            $order->customer_phone,
            [
                'order_id' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => number_format($order->total, 0, ',', '.'),
                'items_count' => $order->items->count(),
            ]
        );
    }
}

// Register in EventServiceProvider:
// protected $listen = [
//     OrderCreated::class => [
//         SendOrderConfirmation::class,
//     ],
// ];

// ============================================================================
// EXAMPLE 7: Using in Queue Jobs
// ============================================================================

namespace App\Jobs;

use App\Services\CampaignMessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $campaignName,
        public array $recipients,
        public ?int $userId = null
    ) {}

    public function handle(CampaignMessageService $campaignService): void
    {
        $result = $campaignService->sendBulkCampaign(
            $this->campaignName,
            $this->recipients,
            $this->userId
        );

        \Log::info('Bulk campaign sent', [
            'campaign' => $this->campaignName,
            'success_count' => $result['success_count'],
            'failed_count' => $result['failed_count'],
            'total' => $result['total'],
        ]);
    }
}

// Dispatch the job:
// SendBulkCampaignJob::dispatch('Newsletter', $recipients, auth()->id());

// ============================================================================
// EXAMPLE 8: Using in Artisan Commands
// ============================================================================

namespace App\Console\Commands;

use App\Models\User;
use App\Services\CampaignMessageService;
use Illuminate\Console\Command;

class SendWeeklyNewsletter extends Command
{
    protected $signature = 'campaign:newsletter';
    protected $description = 'Send weekly newsletter to all subscribed users';

    public function handle(CampaignMessageService $campaignService): int
    {
        $users = User::where('subscribed', true)
            ->whereNotNull('phone')
            ->get();

        $this->info("Sending newsletter to {$users->count()} users...");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $successCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            $result = $campaignService->sendCampaignByName(
                'Weekly Newsletter',
                $user->phone,
                [
                    'user_name' => $user->name,
                    'week_number' => now()->weekOfYear,
                ]
            );

            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
                $this->error("\nFailed for {$user->phone}: {$result['message']}");
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);
        $this->info("Newsletter sent!");
        $this->info("Success: {$successCount}");
        $this->info("Failed: {$failedCount}");

        return Command::SUCCESS;
    }
}

// ============================================================================
// EXAMPLE 9: Using with Filament Actions
// ============================================================================

namespace App\Filament\Resources\OrderResource;

use Filament\Resources\Resource;
use Filament\Tables;
use App\Facades\Campaign;

class OrderResource extends Resource
{
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number'),
                Tables\Columns\TextColumn::make('customer_name'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->actions([
                Tables\Actions\Action::make('send_reminder')
                    ->label('Send Reminder')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->action(function ($record) {
                        $result = Campaign::send(
                            'Payment Reminder',
                            $record->customer_phone,
                            [
                                'order_id' => $record->order_number,
                                'customer_name' => $record->customer_name,
                                'amount_due' => number_format($record->total, 0, ',', '.'),
                            ]
                        );

                        if ($result['success']) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Reminder sent')
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Failed to send reminder')
                                ->body($result['message'])
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending'),
            ]);
    }
}

// ============================================================================
// EXAMPLE 10: Preview Before Sending
// ============================================================================

namespace App\Http\Controllers;

use App\Facades\Campaign;

class CampaignPreviewController extends Controller
{
    public function preview()
    {
        // Preview a campaign before sending
        $preview = Campaign::preview('Order Confirmation', [
            'order_id' => 'ORD-12345',
            'customer_name' => 'John Doe',
            'total' => '1.500.000',
        ]);

        if ($preview['success']) {
            return response()->json([
                'preview' => $preview['preview'],
                'campaign' => $preview['campaign_name'],
            ]);
        }

        return response()->json([
            'error' => $preview['message'],
        ], 400);
    }
}

// ============================================================================
// EXAMPLE 11: Bulk Sending with Custom Logic
// ============================================================================

namespace App\Services;

use App\Models\User;
use App\Facades\Campaign;

class UserNotificationService
{
    public function sendBirthdayGreetings(): array
    {
        // Get users with birthdays today
        $users = User::whereMonth('birth_date', now()->month)
            ->whereDay('birth_date', now()->day)
            ->whereNotNull('phone')
            ->get();

        $recipients = $users->map(function ($user) {
            return [
                'phone' => $user->phone,
                'variables' => [
                    'user_name' => $user->name,
                    'age' => $user->birth_date->age,
                ],
            ];
        })->toArray();

        return Campaign::sendBulk('Birthday Greeting', $recipients);
    }

    public function sendPromotionToSegment(string $segment): array
    {
        $users = User::where('segment', $segment)
            ->where('marketing_consent', true)
            ->whereNotNull('phone')
            ->get();

        $recipients = $users->map(function ($user) {
            return [
                'phone' => $user->phone,
                'variables' => [
                    'user_name' => $user->name,
                    'loyalty_points' => $user->loyalty_points,
                    'discount_code' => $this->generateDiscountCode($user),
                ],
            ];
        })->toArray();

        return Campaign::sendBulk('Promotion Campaign', $recipients);
    }

    private function generateDiscountCode($user): string
    {
        return 'DISC-' . $user->id . '-' . now()->format('Ymd');
    }
}

// ============================================================================
// EXAMPLE 12: Error Handling and Retry Logic
// ============================================================================

namespace App\Services;

use App\Facades\Campaign;
use Illuminate\Support\Facades\Log;

class RobustCampaignSender
{
    public function sendWithRetry(
        string $campaignName,
        string $phoneNumber,
        array $variables,
        int $maxRetries = 3
    ): array {
        $attempt = 0;
        $lastError = null;

        while ($attempt < $maxRetries) {
            $result = Campaign::send($campaignName, $phoneNumber, $variables);

            if ($result['success']) {
                if ($attempt > 0) {
                    Log::info("Campaign sent successfully after {$attempt} retries", [
                        'campaign' => $campaignName,
                        'phone' => $phoneNumber,
                    ]);
                }
                return $result;
            }

            $lastError = $result['message'];
            $attempt++;

            if ($attempt < $maxRetries) {
                // Wait before retrying (exponential backoff)
                sleep(pow(2, $attempt));
            }
        }

        Log::error("Campaign failed after {$maxRetries} attempts", [
            'campaign' => $campaignName,
            'phone' => $phoneNumber,
            'error' => $lastError,
        ]);

        return [
            'success' => false,
            'message' => "Failed after {$maxRetries} attempts: {$lastError}",
        ];
    }
}
