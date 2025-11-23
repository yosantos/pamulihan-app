# WhatsApp Campaign Message System

A complete, reusable system for sending WhatsApp campaign messages from anywhere in your Laravel application.

## Overview

This system allows you to send WhatsApp messages using pre-defined campaign templates from:
- Filament Resources (Create/Edit pages)
- Filament Table Actions
- Controllers
- Event Listeners
- Artisan Commands
- Queue Jobs
- Anywhere in your codebase

## Quick Start

### 1. Send a Campaign Message

```php
use App\Facades\Campaign;

Campaign::send('Welcome Campaign', '628123456789', [
    'user_name' => 'John Doe',
    'code' => '123456'
]);
```

### 2. In Filament Resource

```php
protected function afterCreate(): void
{
    Campaign::send('Order Confirmation', $this->record->customer_phone, [
        'order_id' => $this->record->order_number,
        'total' => number_format($this->record->total, 0, ',', '.')
    ]);
}
```

### 3. Using Model Trait

```php
use App\Traits\SendsCampaignMessages;

class Order extends Model
{
    use SendsCampaignMessages;
}

// Usage
$order->sendCampaign('Order Confirmation', $order->customer_phone, [
    'order_id' => $order->order_number
]);
```

## System Components

### Core Service
**Location:** `app/Services/CampaignMessageService.php`

The main service that handles all campaign message sending logic:
- Send by campaign name or ID
- Variable validation and replacement
- Phone number validation
- WhatsApp message record creation
- Error handling and logging

### Facade
**Location:** `app/Facades/Campaign.php`

Provides easy access to the service with shorter syntax:
- `Campaign::send()` - Send campaign by name
- `Campaign::sendById()` - Send campaign by ID
- `Campaign::preview()` - Preview message
- `Campaign::isAvailable()` - Check if campaign exists and is active
- And more...

### Trait
**Location:** `app/Traits/SendsCampaignMessages.php`

Add to any model to enable campaign sending:
```php
class Order extends Model
{
    use SendsCampaignMessages;
}
```

### Queue Jobs
**Location:** `app/Jobs/`

- `SendCampaignMessageJob.php` - Send single message asynchronously
- `SendBulkCampaignJob.php` - Send bulk messages asynchronously

### Examples
**Location:** `app/Examples/CampaignIntegrationExamples.php`

Comprehensive examples showing integration in:
- Filament Resources
- Controllers
- Event Listeners
- Queue Jobs
- Artisan Commands
- And more...

## Documentation

### Full Documentation
See: `CAMPAIGN_INTEGRATION_GUIDE.md`

Complete guide covering:
- Setup and configuration
- All usage methods
- Advanced features
- Error handling
- Best practices
- Troubleshooting
- API reference

### Quick Reference
See: `CAMPAIGN_QUICK_REFERENCE.md`

Quick copy-paste examples for:
- Basic sending
- Common patterns
- Error handling
- Filament integration
- Queue jobs

## Installation

### 1. Service is Already Registered

The service is registered in `app/Providers/AppServiceProvider.php`:

```php
public function register(): void
{
    $this->app->singleton('campaign.message.service', function ($app) {
        return new \App\Services\CampaignMessageService(
            $app->make(\App\Services\WhatsAppService::class)
        );
    });
}
```

### 2. (Optional) Add Facade Alias

Add to `config/app.php`:

```php
'aliases' => [
    // ...
    'Campaign' => App\Facades\Campaign::class,
],
```

### 3. Seed Common Campaigns

Run the seeder to create common campaign templates:

```bash
php artisan db:seed --class=CommonCampaignsSeeder
```

This creates 18 ready-to-use campaign templates including:
- Welcome Campaign
- OTP Campaign
- Order Confirmation
- Order Shipped
- Payment Reminder
- And more...

### 4. Configure Queue (For Async Sending)

Add to `config/queue.php`:

```php
'connections' => [
    'database' => [
        'queue' => ['whatsapp', 'whatsapp-bulk', 'default'],
    ],
],
```

Run queue worker:

```bash
php artisan queue:work --queue=whatsapp,whatsapp-bulk,default
```

## Usage Examples

### Example 1: Send After Creating User

```php
namespace App\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Facades\Campaign;

class CreateUser extends CreateRecord
{
    protected function afterCreate(): void
    {
        Campaign::send('Welcome Campaign', $this->record->phone, [
            'user_name' => $this->record->name,
            'code' => rand(100000, 999999)
        ]);
    }
}
```

### Example 2: Send When Order Status Changes

```php
namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Facades\Campaign;

class EditOrder extends EditRecord
{
    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status')) {
            $campaigns = [
                'processing' => 'Order Processing',
                'shipped' => 'Order Shipped',
                'delivered' => 'Order Delivered',
            ];

            if (isset($campaigns[$this->record->status])) {
                Campaign::send(
                    $campaigns[$this->record->status],
                    $this->record->customer_phone,
                    ['order_id' => $this->record->order_number]
                );
            }
        }
    }
}
```

### Example 3: Send in Controller

```php
public function store(Request $request)
{
    $order = Order::create($request->validated());

    Campaign::send('Order Confirmation', $order->customer_phone, [
        'order_id' => $order->order_number,
        'customer_name' => $order->customer_name,
        'total' => number_format($order->total, 0, ',', '.'),
    ]);

    return redirect()->route('orders.show', $order);
}
```

### Example 4: Async Sending with Queue

```php
use App\Jobs\SendCampaignMessageJob;

SendCampaignMessageJob::dispatch(
    'Welcome Campaign',
    $user->phone,
    ['user_name' => $user->name, 'code' => $code]
);
```

### Example 5: Bulk Sending

```php
$recipients = [
    ['phone' => '628111111111', 'variables' => ['name' => 'John']],
    ['phone' => '628222222222', 'variables' => ['name' => 'Jane']],
];

$result = Campaign::sendBulk('Newsletter', $recipients);

echo "Success: {$result['success_count']}";
echo "Failed: {$result['failed_count']}";
```

## Available Methods

### Send Methods
```php
Campaign::send($name, $phone, $variables)        // Send by campaign name
Campaign::sendById($id, $phone, $variables)      // Send by campaign ID
Campaign::sendBulk($name, $recipients)           // Send to multiple recipients
```

### Validation & Preview
```php
Campaign::preview($name, $variables)             // Preview without sending
Campaign::validate($name, $variables)            // Validate variables
Campaign::isAvailable($name)                     // Check if campaign exists & active
```

### Information Methods
```php
Campaign::active()                               // Get all active campaigns
Campaign::variables($name)                       // Get required variables
Campaign::getByName($name)                       // Get campaign object
Campaign::getById($id)                           // Get campaign object
```

## Response Structure

All methods return a consistent response:

```php
[
    'success' => true|false,
    'message' => 'Success or error message',
    'data' => [
        'message_id' => 123,
        'campaign_id' => 5,
        'campaign_name' => 'Welcome Campaign',
        'phone_number' => '628123456789',
    ] | null
]
```

## Error Handling

Always check the result:

```php
$result = Campaign::send('Welcome', $phone, $vars);

if (!$result['success']) {
    Log::error('Campaign failed', ['error' => $result['message']]);
    // Handle error gracefully
}
```

Common errors:
- `Campaign 'X' not found` - Campaign doesn't exist
- `Campaign 'X' is not active` - Campaign is disabled
- `Invalid phone number format` - Phone number invalid
- `Missing required variables: X, Y` - Required variables not provided

## Best Practices

1. **Always handle errors** - Check `$result['success']`
2. **Use preview** - Test new campaigns with `Campaign::preview()`
3. **Use queues for bulk** - Don't send many messages synchronously
4. **Use constants** - Define campaign names as constants
5. **Validate first** - Use `Campaign::validate()` before bulk sending
6. **Format numbers** - Use `number_format()` for displaying prices
7. **Check availability** - Use `Campaign::isAvailable()` before sending
8. **Log failures** - Always log failed sends for debugging

## Common Campaigns

The seeder creates these ready-to-use campaigns:

**Active by default:**
- Welcome Campaign
- OTP Campaign
- Order Confirmation
- Order Processing
- Order Shipped
- Order Delivered
- Order Cancelled
- Payment Reminder
- Payment Received
- Password Reset
- Account Update

**Inactive by default (activate as needed):**
- Product Created Notification
- Product Updated Notification
- Weekly Newsletter
- Promotion Campaign
- Birthday Greeting
- Stock Alert
- Review Request

## Files Created

```
app/
├── Services/
│   └── CampaignMessageService.php          # Core service
├── Facades/
│   └── Campaign.php                         # Facade
├── Traits/
│   └── SendsCampaignMessages.php           # Model trait
├── Jobs/
│   ├── SendCampaignMessageJob.php          # Single message job
│   └── SendBulkCampaignJob.php             # Bulk message job
├── Examples/
│   └── CampaignIntegrationExamples.php     # 12 detailed examples
└── Providers/
    └── AppServiceProvider.php               # Service registration (modified)

database/seeders/
└── CommonCampaignsSeeder.php                # Campaign templates seeder

Root/
├── CAMPAIGN_INTEGRATION_GUIDE.md            # Complete documentation
├── CAMPAIGN_QUICK_REFERENCE.md              # Quick reference guide
└── CAMPAIGN_SYSTEM_README.md                # This file
```

## Testing the System

### 1. Seed Campaigns

```bash
php artisan db:seed --class=CommonCampaignsSeeder
```

### 2. Preview a Campaign

```php
$preview = Campaign::preview('Welcome Campaign', [
    'user_name' => 'Test User',
    'code' => '123456'
]);

dd($preview['preview']);
```

### 3. Send a Test Message

```php
$result = Campaign::send('OTP Campaign', '628123456789', [
    'code' => '123456',
    'expiry_minutes' => '5'
]);

dd($result);
```

### 4. Check Active Campaigns

```php
$campaigns = Campaign::active();

foreach ($campaigns as $campaign) {
    echo $campaign->name . "\n";
    echo "Variables: " . implode(', ', $campaign->variables) . "\n\n";
}
```

## Integration Checklist

- [x] Core service created
- [x] Facade registered
- [x] Model trait available
- [x] Queue jobs ready
- [x] Examples documented
- [x] Full documentation written
- [x] Quick reference created
- [x] Common campaigns seeder ready
- [x] Error handling implemented
- [x] Logging configured

## Support & Troubleshooting

### Common Issues

**Campaign not found:**
- Check campaign exists in database
- Verify campaign name spelling (case-sensitive)
- Use `Campaign::active()` to see all campaigns

**Campaign not active:**
- Enable in Filament admin panel
- Or: `WhatsAppCampaign::where('name', 'X')->update(['is_active' => true])`

**Missing variables:**
- Use `Campaign::variables('Campaign Name')` to see required variables
- Ensure all variables are provided in the array

**Invalid phone number:**
- Use Indonesian format: `628123456789`
- Or local format (will convert): `0812-3456-789`

**Queue jobs not running:**
- Start queue worker: `php artisan queue:work`
- Check failed jobs: `php artisan queue:failed`

### Documentation

- **Full guide:** `CAMPAIGN_INTEGRATION_GUIDE.md` (10,000+ words)
- **Quick reference:** `CAMPAIGN_QUICK_REFERENCE.md` (Quick examples)
- **Code examples:** `app/Examples/CampaignIntegrationExamples.php` (12 examples)

### Logs

Check Laravel logs for debugging:
```bash
tail -f storage/logs/laravel.log
```

## Next Steps

1. **Seed campaigns:**
   ```bash
   php artisan db:seed --class=CommonCampaignsSeeder
   ```

2. **Test the system:**
   ```php
   Campaign::preview('Welcome Campaign', ['user_name' => 'Test', 'code' => '123']);
   ```

3. **Integrate in your resources:**
   See examples in `app/Examples/CampaignIntegrationExamples.php`

4. **Create custom campaigns:**
   Use Filament admin panel to create campaigns specific to your needs

5. **Set up queue workers (for production):**
   Configure Supervisor to keep queue workers running

## License

Part of Pamulihan App V2 - Internal Use

---

**Created:** 2025-11-20
**Version:** 1.0.0
**Status:** Production Ready
