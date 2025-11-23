# WhatsApp Campaign Integration Guide

Complete guide for using the Campaign Message Service to send WhatsApp messages from anywhere in your Laravel application.

## Table of Contents

1. [Overview](#overview)
2. [Setup](#setup)
3. [Basic Usage](#basic-usage)
4. [Integration Methods](#integration-methods)
5. [Advanced Features](#advanced-features)
6. [Queue Jobs](#queue-jobs)
7. [Error Handling](#error-handling)
8. [Best Practices](#best-practices)
9. [Common Campaigns](#common-campaigns)
10. [Troubleshooting](#troubleshooting)

---

## Overview

The Campaign Message Service provides a reusable, centralized system for sending WhatsApp messages using pre-defined campaign templates throughout your application.

### Key Features

- Send campaign messages from anywhere (Resources, Controllers, Jobs, Events)
- Support for both campaign ID and campaign name
- Variable replacement in templates
- Automatic validation of phone numbers and required variables
- Audit trail via WhatsAppMessage records
- Bulk sending capabilities
- Queue support for async sending
- Preview messages before sending
- Comprehensive error handling

### Architecture

```
App/
├── Services/
│   └── CampaignMessageService.php    # Core service
├── Facades/
│   └── Campaign.php                   # Facade for easy access
├── Traits/
│   └── SendsCampaignMessages.php     # Trait for models
├── Jobs/
│   ├── SendCampaignMessageJob.php    # Single message queue job
│   └── SendBulkCampaignJob.php       # Bulk messages queue job
└── Examples/
    └── CampaignIntegrationExamples.php # Reference examples
```

---

## Setup

### 1. Register the Service (Already Done)

The service is automatically registered in `AppServiceProvider`:

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->singleton('campaign.message.service', function ($app) {
        return new \App\Services\CampaignMessageService(
            $app->make(\App\Services\WhatsAppService::class)
        );
    });
}
```

### 2. Add Facade Alias (Optional)

Add to `config/app.php` for global access:

```php
'aliases' => [
    // ...
    'Campaign' => App\Facades\Campaign::class,
],
```

### 3. Configure Queue (Optional)

For queued sending, ensure your queue is configured:

```bash
php artisan queue:work --queue=whatsapp,whatsapp-bulk,default
```

---

## Basic Usage

### Method 1: Using the Service Directly

```php
use App\Services\CampaignMessageService;

$campaignService = app(CampaignMessageService::class);

$result = $campaignService->sendCampaignByName(
    campaignName: 'Welcome Campaign',
    phoneNumber: '628123456789',
    variables: [
        'user_name' => 'John Doe',
        'code' => '123456'
    ]
);

if ($result['success']) {
    // Message sent successfully
    $messageId = $result['data']['message_id'];
} else {
    // Handle error
    $error = $result['message'];
}
```

### Method 2: Using the Facade

```php
use App\Facades\Campaign;

Campaign::send('Welcome Campaign', '628123456789', [
    'user_name' => 'John Doe',
    'code' => '123456'
]);
```

### Method 3: Using the Trait

```php
use App\Traits\SendsCampaignMessages;

class Order extends Model
{
    use SendsCampaignMessages;

    public function sendConfirmation()
    {
        return $this->sendCampaign(
            'Order Confirmation',
            $this->customer_phone,
            [
                'order_id' => $this->order_number,
                'total' => number_format($this->total, 0, ',', '.')
            ]
        );
    }
}

// Usage
$order->sendConfirmation();
```

---

## Integration Methods

### 1. Filament Resource Create Page

```php
namespace App\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Facades\Campaign;

class CreateUser extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\UserResource::class;

    protected function afterCreate(): void
    {
        Campaign::send('Welcome Campaign', $this->record->phone, [
            'user_name' => $this->record->name,
            'account_id' => $this->record->id,
        ]);

        // Show notification
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('User created and welcome message sent')
            ->send();
    }
}
```

### 2. Filament Resource Edit Page

```php
namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Facades\Campaign;

class EditOrder extends EditRecord
{
    protected static string $resource = \App\Filament\Resources\OrderResource::class;

    protected function afterSave(): void
    {
        // Send notification only if status changed
        if ($this->record->wasChanged('status')) {
            $campaignMap = [
                'processing' => 'Order Processing',
                'shipped' => 'Order Shipped',
                'delivered' => 'Order Delivered',
            ];

            $campaign = $campaignMap[$this->record->status] ?? null;

            if ($campaign) {
                Campaign::send($campaign, $this->record->customer_phone, [
                    'order_id' => $this->record->order_number,
                    'status' => ucfirst($this->record->status),
                ]);
            }
        }
    }
}
```

### 3. Filament Table Actions

```php
use Filament\Tables;
use App\Facades\Campaign;

Tables\Actions\Action::make('send_reminder')
    ->label('Send Reminder')
    ->icon('heroicon-o-chat-bubble-left')
    ->action(function ($record) {
        $result = Campaign::send(
            'Payment Reminder',
            $record->customer_phone,
            [
                'order_id' => $record->order_number,
                'amount_due' => number_format($record->total, 0, ',', '.'),
            ]
        );

        if ($result['success']) {
            \Filament\Notifications\Notification::make()
                ->success()
                ->title('Reminder sent')
                ->send();
        }
    })
    ->requiresConfirmation()
```

### 4. Controllers

```php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Facades\Campaign;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create($request->validated());

        // Send order confirmation
        Campaign::send('Order Confirmation', $order->customer_phone, [
            'order_id' => $order->order_number,
            'customer_name' => $order->customer_name,
            'total' => number_format($order->total, 0, ',', '.'),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created and confirmation sent');
    }
}
```

### 5. Event Listeners

```php
namespace App\Listeners;

use App\Events\OrderCreated;
use App\Facades\Campaign;

class SendOrderConfirmation
{
    public function handle(OrderCreated $event): void
    {
        Campaign::send('Order Confirmation', $event->order->customer_phone, [
            'order_id' => $event->order->order_number,
            'total' => number_format($event->order->total, 0, ',', '.'),
        ]);
    }
}
```

Register in `EventServiceProvider`:

```php
protected $listen = [
    OrderCreated::class => [
        SendOrderConfirmation::class,
    ],
];
```

### 6. Artisan Commands

```php
namespace App\Console\Commands;

use App\Models\User;
use App\Facades\Campaign;
use Illuminate\Console\Command;

class SendWeeklyNewsletter extends Command
{
    protected $signature = 'campaign:newsletter';

    public function handle(): int
    {
        $users = User::where('subscribed', true)->get();

        foreach ($users as $user) {
            Campaign::send('Weekly Newsletter', $user->phone, [
                'user_name' => $user->name,
            ]);
        }

        $this->info("Newsletter sent to {$users->count()} users");
        return Command::SUCCESS;
    }
}
```

---

## Advanced Features

### 1. Preview Messages

Preview a message without sending:

```php
use App\Facades\Campaign;

$preview = Campaign::preview('Order Confirmation', [
    'order_id' => 'ORD-12345',
    'customer_name' => 'John Doe',
    'total' => '1.500.000',
]);

if ($preview['success']) {
    echo $preview['preview']; // Shows the final message
}
```

### 2. Validate Variables

Check if all required variables are provided:

```php
$validation = Campaign::validate('Welcome Campaign', [
    'user_name' => 'John',
    // Missing 'code' variable
]);

if (!$validation['valid']) {
    $missing = $validation['missing_variables']; // ['code']
}
```

### 3. Check Campaign Availability

```php
if (Campaign::isAvailable('Welcome Campaign')) {
    // Campaign exists and is active
}
```

### 4. Get Campaign Variables

```php
$variables = Campaign::variables('Welcome Campaign');
// Returns: ['user_name', 'code', 'email']
```

### 5. Get Active Campaigns

```php
$campaigns = Campaign::active();

foreach ($campaigns as $campaign) {
    echo $campaign->name;
    echo implode(', ', $campaign->variables);
}
```

### 6. Bulk Sending

```php
use App\Facades\Campaign;

$recipients = [
    [
        'phone' => '628123456789',
        'variables' => ['user_name' => 'John', 'code' => '123']
    ],
    [
        'phone' => '628987654321',
        'variables' => ['user_name' => 'Jane', 'code' => '456']
    ],
];

$result = Campaign::sendBulk('OTP Campaign', $recipients);

echo "Success: {$result['success_count']}";
echo "Failed: {$result['failed_count']}";
```

---

## Queue Jobs

### 1. Single Message Job

Send a single message asynchronously:

```php
use App\Jobs\SendCampaignMessageJob;

SendCampaignMessageJob::dispatch(
    'Welcome Campaign',
    '628123456789',
    ['user_name' => 'John', 'code' => '123456']
);
```

With delay:

```php
SendCampaignMessageJob::dispatch(...)
    ->delay(now()->addMinutes(5));
```

### 2. Bulk Campaign Job

Send to multiple recipients:

```php
use App\Jobs\SendBulkCampaignJob;

$recipients = [
    ['phone' => '628123456789', 'variables' => ['name' => 'John']],
    ['phone' => '628987654321', 'variables' => ['name' => 'Jane']],
];

// Option 1: Send all at once
SendBulkCampaignJob::dispatch('Newsletter', $recipients);

// Option 2: Dispatch individual jobs (better for rate limiting)
SendBulkCampaignJob::dispatch('Newsletter', $recipients, null, true);
```

### 3. Queue Configuration

Add to your queue configuration:

```php
// config/queue.php
'connections' => [
    'database' => [
        'queue' => ['whatsapp', 'whatsapp-bulk', 'default'],
    ],
],
```

Run queue workers:

```bash
# All queues
php artisan queue:work

# Specific queue
php artisan queue:work --queue=whatsapp,whatsapp-bulk,default

# With supervisor (production)
php artisan queue:work --queue=whatsapp --tries=3 --timeout=90
```

---

## Error Handling

### Understanding Response Structure

All methods return a consistent response structure:

```php
[
    'success' => true|false,
    'message' => 'Success or error message',
    'data' => [
        'message_id' => 123,
        'campaign_id' => 5,
        'campaign_name' => 'Welcome Campaign',
        'phone_number' => '628123456789',
        'whatsapp_response' => [...],
    ] | null
]
```

### Common Errors

#### 1. Campaign Not Found

```php
$result = Campaign::send('Non Existent Campaign', '628123456789', []);

if (!$result['success']) {
    // $result['message'] = "Campaign 'Non Existent Campaign' not found"
}
```

#### 2. Campaign Inactive

```php
// Campaign exists but is_active = false
$result = Campaign::send('Inactive Campaign', '628123456789', []);
// $result['message'] = "Campaign 'Inactive Campaign' is not active"
```

#### 3. Invalid Phone Number

```php
$result = Campaign::send('Welcome', 'invalid-phone', []);
// $result['message'] = "Invalid phone number format: invalid-phone"
```

#### 4. Missing Variables

```php
$result = Campaign::send('Welcome Campaign', '628123456789', [
    'user_name' => 'John'
    // Missing 'code' variable
]);
// $result['message'] = "Missing required variables: code"
// $result['data']['missing_variables'] = ['code']
```

### Graceful Error Handling Example

```php
use App\Facades\Campaign;
use Illuminate\Support\Facades\Log;

public function sendNotification($order)
{
    $result = Campaign::send('Order Confirmation', $order->customer_phone, [
        'order_id' => $order->order_number,
        'total' => number_format($order->total, 0, ',', '.'),
    ]);

    if (!$result['success']) {
        // Log the error
        Log::warning('Failed to send order confirmation', [
            'order_id' => $order->id,
            'error' => $result['message'],
        ]);

        // Notify admin (optional)
        // Notification::route('mail', config('app.admin_email'))
        //     ->notify(new CampaignFailedNotification($result));

        // Don't fail the entire process
        return false;
    }

    return true;
}
```

### Retry Logic

```php
public function sendWithRetry($campaignName, $phone, $variables, $maxRetries = 3)
{
    $attempt = 0;

    while ($attempt < $maxRetries) {
        $result = Campaign::send($campaignName, $phone, $variables);

        if ($result['success']) {
            return $result;
        }

        $attempt++;

        if ($attempt < $maxRetries) {
            sleep(pow(2, $attempt)); // Exponential backoff
        }
    }

    return $result; // Return last failed attempt
}
```

---

## Best Practices

### 1. Always Handle Errors

```php
// Bad
Campaign::send('Welcome', $phone, $vars);

// Good
$result = Campaign::send('Welcome', $phone, $vars);
if (!$result['success']) {
    Log::error('Campaign failed', ['error' => $result['message']]);
}
```

### 2. Use Preview for Testing

```php
// Preview before sending in production
$preview = Campaign::preview('New Campaign', $testVars);

if ($preview['success']) {
    echo "Preview: " . $preview['preview'];
    // Verify the message looks correct before bulk sending
}
```

### 3. Validate Before Bulk Sending

```php
$validation = Campaign::validate('Newsletter', $variables);

if (!$validation['valid']) {
    return "Missing: " . implode(', ', $validation['missing_variables']);
}

// Proceed with bulk send
```

### 4. Use Queues for Bulk Operations

```php
// Don't do this (blocks request)
foreach ($users as $user) {
    Campaign::send('Newsletter', $user->phone, [...]);
}

// Do this (async)
SendBulkCampaignJob::dispatch('Newsletter', $recipients);
```

### 5. Format Numbers Properly

```php
// Good - formatted for display
'total' => number_format($order->total, 0, ',', '.'), // 1.500.000

// Good - formatted for currency
'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
```

### 6. Use Model Methods

```php
class Order extends Model
{
    use SendsCampaignMessages;

    public function sendConfirmation()
    {
        return $this->sendCampaign('Order Confirmation',
            $this->customer_phone,
            $this->getConfirmationVariables()
        );
    }

    protected function getConfirmationVariables(): array
    {
        return [
            'order_id' => $this->order_number,
            'customer_name' => $this->customer_name,
            'total' => number_format($this->total, 0, ',', '.'),
            'items_count' => $this->items->count(),
        ];
    }
}
```

### 7. Check Campaign Availability

```php
// Before using a campaign, check if it exists
if (!Campaign::isAvailable('Promotional Campaign')) {
    Log::warning('Campaign not available');
    return;
}

Campaign::send('Promotional Campaign', ...);
```

### 8. Use Constants for Campaign Names

```php
class CampaignNames
{
    const WELCOME = 'Welcome Campaign';
    const ORDER_CONFIRMATION = 'Order Confirmation';
    const ORDER_SHIPPED = 'Order Shipped';
    const PAYMENT_REMINDER = 'Payment Reminder';
}

// Usage
Campaign::send('Welcome Campaign', $phone, $vars);
```

---

## Common Campaigns

### Suggested Campaign Templates

#### 1. Welcome Campaign

**Variables:** `user_name`, `code`

**Template:**
```
Selamat datang [user_name]!

Terima kasih telah mendaftar di [Name_Company].
Kode verifikasi Anda: [code]

Gunakan kode ini untuk mengaktifkan akun Anda.
```

**Usage:**
```php
Campaign::send('Welcome Campaign', $user->phone, [
    'user_name' => $user->name,
    'code' => $verificationCode,
]);
```

#### 2. Order Confirmation

**Variables:** `order_id`, `customer_name`, `total`, `items_count`

**Template:**
```
Halo [customer_name],

Pesanan Anda telah diterima!

Order ID: [order_id]
Total: Rp [total]
Jumlah item: [items_count]

Terima kasih telah berbelanja di [Name_Company].
```

#### 3. Order Shipped

**Variables:** `order_id`, `tracking_number`, `courier`

**Template:**
```
Pesanan Anda telah dikirim!

Order ID: [order_id]
Kurir: [courier]
No. Resi: [tracking_number]

Lacak paket Anda di website kurir.
```

#### 4. Payment Reminder

**Variables:** `order_id`, `amount_due`, `due_date`

**Template:**
```
Pengingat Pembayaran

Order ID: [order_id]
Jumlah: Rp [amount_due]
Jatuh tempo: [due_date]

Segera lakukan pembayaran untuk menghindari pembatalan.
```

#### 5. OTP Verification

**Variables:** `code`, `expiry_minutes`

**Template:**
```
Kode OTP Anda: [code]

Berlaku selama [expiry_minutes] menit.
Jangan bagikan kode ini kepada siapapun.

[Name_Company]
```

---

## Troubleshooting

### Issue: Campaign Not Found

**Problem:** Getting "Campaign 'X' not found" error

**Solution:**
1. Check campaign name spelling (case-sensitive)
2. Verify campaign exists in database
3. Check if using correct campaign name:

```php
// Check all campaigns
$campaigns = Campaign::active();
foreach ($campaigns as $campaign) {
    echo $campaign->name . "\n";
}
```

### Issue: Campaign Inactive

**Problem:** Campaign exists but getting "not active" error

**Solution:**
1. Check `is_active` field in database
2. Activate in Filament admin panel
3. Or programmatically:

```php
$campaign = WhatsAppCampaign::where('name', 'Campaign Name')->first();
$campaign->update(['is_active' => true]);
```

### Issue: Missing Variables

**Problem:** Getting "Missing required variables" error

**Solution:**
1. Check what variables are required:

```php
$variables = Campaign::variables('Campaign Name');
// Returns array of required variables
```

2. Ensure all are provided:

```php
Campaign::send('Campaign Name', $phone, [
    'var1' => 'value1',
    'var2' => 'value2',
    // etc.
]);
```

### Issue: Invalid Phone Number

**Problem:** Phone number format not accepted

**Solution:**
Use Indonesian format with country code:

```php
// Good formats
'628123456789'     // Recommended
'62-812-3456-789'  // Will be cleaned
'0812-3456-789'    // Will be converted to 628123456789

// Bad format
'+62 812 3456 789' // Remove + sign
'812-3456-789'     // Missing leading 0 or 62
```

### Issue: Queue Jobs Not Processing

**Problem:** Jobs dispatched but not running

**Solution:**
1. Ensure queue worker is running:

```bash
php artisan queue:work --queue=whatsapp,default
```

2. Check failed jobs:

```bash
php artisan queue:failed
```

3. Retry failed jobs:

```bash
php artisan queue:retry all
```

### Issue: Rate Limiting

**Problem:** Too many messages, some fail

**Solution:**
1. Use queue with delays:

```php
foreach ($users as $index => $user) {
    SendCampaignMessageJob::dispatch(...)
        ->delay(now()->addSeconds($index * 2)); // 2 seconds apart
}
```

2. Or use bulk job with batching (automatic delays between batches)

---

## API Reference

### CampaignMessageService Methods

#### `sendCampaignByName(string $campaignName, string $phoneNumber, array $variables = [], ?int $userId = null): array`

Send campaign by name

#### `sendCampaignMessage(int $campaignId, string $phoneNumber, array $variables = [], ?int $userId = null): array`

Send campaign by ID

#### `previewCampaignMessage(string $campaignName, array $variables = []): array`

Preview message without sending

#### `campaignExistsAndActive(string $campaignName): bool`

Check if campaign exists and is active

#### `getActiveCampaigns(): Collection`

Get all active campaigns

#### `getCampaignVariables(string $campaignName): ?array`

Get required variables for a campaign

#### `validateCampaignVariables(string $campaignName, array $variables): array`

Validate if all required variables are provided

#### `sendBulkCampaign(string $campaignName, array $recipients, ?int $userId = null): array`

Send campaign to multiple recipients

### Facade Aliases

The `Campaign` facade provides shorter method names:

```php
Campaign::send()           // -> sendCampaignByName()
Campaign::sendById()       // -> sendCampaignMessage()
Campaign::preview()        // -> previewCampaignMessage()
Campaign::isAvailable()    // -> campaignExistsAndActive()
Campaign::active()         // -> getActiveCampaigns()
Campaign::variables()      // -> getCampaignVariables()
Campaign::validate()       // -> validateCampaignVariables()
Campaign::sendBulk()       // -> sendBulkCampaign()
Campaign::getByName()      // -> getCampaignByName()
Campaign::getById()        // -> getCampaignById()
```

---

## Support

For issues or questions:

1. Check this documentation
2. Review the examples in `app/Examples/CampaignIntegrationExamples.php`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Check WhatsApp message records in Filament admin panel

---

**Last Updated:** 2025-11-20
