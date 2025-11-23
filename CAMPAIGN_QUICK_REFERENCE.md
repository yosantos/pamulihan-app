# Campaign Message Service - Quick Reference

Quick reference guide for the most common use cases.

## Basic Sending

### 1. Simplest Way (Using Facade)

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

### 3. In Controller

```php
public function store(Request $request)
{
    $order = Order::create($request->validated());

    Campaign::send('Order Confirmation', $order->customer_phone, [
        'order_id' => $order->order_number,
    ]);

    return redirect()->route('orders.show', $order);
}
```

### 4. Using Model Trait

```php
// In Model
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

## Advanced Usage

### Preview Before Sending

```php
$preview = Campaign::preview('Welcome Campaign', [
    'user_name' => 'John',
    'code' => '123'
]);

echo $preview['preview']; // Shows final message
```

### Check Campaign Exists

```php
if (Campaign::isAvailable('Welcome Campaign')) {
    // Campaign exists and is active
}
```

### Get Required Variables

```php
$vars = Campaign::variables('Welcome Campaign');
// Returns: ['user_name', 'code']
```

### Validate Variables

```php
$result = Campaign::validate('Welcome Campaign', [
    'user_name' => 'John'
    // Missing 'code'
]);

if (!$result['valid']) {
    echo "Missing: " . implode(', ', $result['missing_variables']);
}
```

### Bulk Sending

```php
$recipients = [
    ['phone' => '628111111111', 'variables' => ['name' => 'John']],
    ['phone' => '628222222222', 'variables' => ['name' => 'Jane']],
];

$result = Campaign::sendBulk('Newsletter', $recipients);

echo "Success: {$result['success_count']}";
echo "Failed: {$result['failed_count']}";
```

## Queue Jobs

### Single Message (Async)

```php
use App\Jobs\SendCampaignMessageJob;

SendCampaignMessageJob::dispatch(
    'Welcome Campaign',
    '628123456789',
    ['user_name' => 'John', 'code' => '123']
);
```

### With Delay

```php
SendCampaignMessageJob::dispatch(...)
    ->delay(now()->addMinutes(5));
```

### Bulk Messages (Async)

```php
use App\Jobs\SendBulkCampaignJob;

SendBulkCampaignJob::dispatch('Newsletter', $recipients);
```

## Error Handling

### Check Result

```php
$result = Campaign::send(...);

if ($result['success']) {
    $messageId = $result['data']['message_id'];
} else {
    Log::error('Failed: ' . $result['message']);
}
```

### Common Errors

```php
// Campaign not found
"Campaign 'X' not found"

// Campaign inactive
"Campaign 'X' is not active"

// Invalid phone
"Invalid phone number format: X"

// Missing variables
"Missing required variables: code, user_name"
```

## Phone Number Format

```php
// Good formats
'628123456789'      // ✓ Recommended
'0812-3456-789'     // ✓ Will convert to 628123456789

// Bad format
'812-3456-789'      // ✗ Missing country code
```

## Filament Integration

### In Create Page

```php
protected function afterCreate(): void
{
    Campaign::send('Product Created', $this->record->user->phone, [
        'product_name' => $this->record->name
    ]);
}
```

### In Edit Page

```php
protected function afterSave(): void
{
    if ($this->record->wasChanged('status')) {
        Campaign::send('Status Updated', $this->record->phone, [
            'status' => $this->record->status
        ]);
    }
}
```

### In Table Action

```php
Tables\Actions\Action::make('send_reminder')
    ->action(function ($record) {
        Campaign::send('Reminder', $record->phone, [
            'order_id' => $record->id
        ]);
    })
```

## Available Methods

### Facade Methods

```php
Campaign::send($name, $phone, $vars)              // Send by name
Campaign::sendById($id, $phone, $vars)            // Send by ID
Campaign::preview($name, $vars)                   // Preview message
Campaign::isAvailable($name)                      // Check if exists & active
Campaign::active()                                // Get all active campaigns
Campaign::variables($name)                        // Get required variables
Campaign::validate($name, $vars)                  // Validate variables
Campaign::sendBulk($name, $recipients)            // Bulk send
Campaign::getByName($name)                        // Get campaign object
Campaign::getById($id)                            // Get campaign object
```

## Database-Driven Campaigns (Best Practice)

All campaigns are stored in the database and can be created/managed via the Filament admin panel.

```php
// Use campaign name directly (recommended)
Campaign::send('Welcome Campaign', $phone, $vars);

// Or use campaign ID if you have it
Campaign::sendById(5, $phone, $vars);

// Check available active campaigns
$campaigns = Campaign::active();
foreach ($campaigns as $campaign) {
    echo $campaign->name;
}
```

**Benefits:**
- Add new campaigns via admin panel without code changes
- No deployment needed for new campaigns
- Non-developers can manage campaigns
- More flexible and maintainable

## Common Patterns

### Send After Model Event

```php
class Order extends Model
{
    protected static function booted()
    {
        static::created(function ($order) {
            Campaign::send('Order Confirmation', $order->customer_phone, [
                'order_id' => $order->order_number
            ]);
        });
    }
}
```

### Send in Observer

```php
class OrderObserver
{
    public function created(Order $order)
    {
        Campaign::send('Order Confirmation', $order->customer_phone, [
            'order_id' => $order->order_number
        ]);
    }
}
```

### Send in Event Listener

```php
class SendOrderConfirmation
{
    public function handle(OrderCreated $event)
    {
        Campaign::send('Order Confirmation',
            $event->order->customer_phone,
            ['order_id' => $event->order->order_number]
        );
    }
}
```

## Tips

1. Always check the result for success
2. Use preview to test new campaigns
3. Use queues for bulk operations
4. Use campaign names directly (campaigns are stored in database)
5. Log errors for debugging
6. Format numbers properly (use number_format)
7. Check campaign availability before sending
8. Validate variables before bulk sending

## Need More Help?

See full documentation: `CAMPAIGN_INTEGRATION_GUIDE.md`

See examples: `app/Examples/CampaignIntegrationExamples.php`
