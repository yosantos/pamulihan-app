# Heir Certificate Dashboard Widgets

This directory contains custom Filament dashboard widgets for the Heir Certificate resource. The widgets provide comprehensive analytics and insights for certificate management.

## Widgets Overview

### 1. HeirCertificateStatsWidget
**File:** `HeirCertificateStatsWidget.php`
**Type:** Stats Overview Widget
**Sort Order:** 1 (displays first)

#### Features
- **Three Stat Cards:**
  - Total Certificates: Shows total count for the selected period
  - On Progress: Shows certificates with "On Progress" status (orange/warning color)
  - Completed: Shows certificates with "Completed" status (green/success color)

- **Date Range Filters:**
  - Today: Current day only
  - Last 7 Days: Previous week (default)
  - Last 30 Days: Previous month
  - Last 365 Days: Previous year

- **Comparison Metrics:**
  - Each stat shows percentage change compared to the previous period
  - Trend icons (up/down arrows or flat line)
  - Example: "+15.2% from previous period"

- **Sparkline Charts:**
  - Small inline chart on each stat card showing the trend
  - Visual representation of data over the selected period

#### Implementation Details
```php
// Filter options
protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last 7 Days',
        'month' => 'Last 30 Days',
        'year' => 'Last 365 Days',
    ];
}

// Default filter
public ?string $filter = 'week';
```

### 2. HeirCertificateChartWidget
**File:** `HeirCertificateChartWidget.php`
**Type:** Line Chart Widget
**Sort Order:** 2 (displays second)

#### Features
- **Multi-Dataset Line Chart:**
  - On Progress Line (Orange): Shows certificates created with "On Progress" status
  - Completed Line (Green): Shows certificates created with "Completed" status
  - Total Line (Blue, dashed): Shows combined total of all certificates

- **Date Range Filters:**
  - Today: Hourly breakdown for the current day
  - Last 7 Days: Daily breakdown (default)
  - Last 30 Days: Daily breakdown
  - Last 365 Days: Weekly breakdown

- **Interactive Features:**
  - Hover tooltips showing exact counts
  - Legend at bottom to toggle datasets on/off
  - Smooth line curves (tension: 0.4)
  - Semi-transparent fill under lines

#### Chart Configuration
```php
// Chart type
protected function getType(): string
{
    return 'line';
}

// Adaptive intervals based on filter
protected function getInterval(): string
{
    return match ($this->filter) {
        'today' => '1 hour',
        'week' => '1 day',
        'month' => '1 day',
        'year' => '1 week',
        default => '1 day',
    };
}
```

## How to Use

### Viewing on Dashboard
1. Navigate to `/admin/dashboard` in your browser
2. The widgets will automatically appear on the dashboard
3. Both widgets are already registered via `discoverWidgets()` in `AdminPanelProvider`

### Filtering Data
1. **Select a Time Range:**
   - Click the filter dropdown on either widget
   - Choose from: Today, Last 7 Days, Last 30 Days, Last 365 Days
   - Both widgets maintain independent filters

2. **Interpreting Stats:**
   - Green up arrow = increase from previous period
   - Red down arrow = decrease from previous period
   - Flat line = no change from previous period

3. **Reading the Chart:**
   - Orange line = On Progress certificates
   - Green line = Completed certificates
   - Blue dashed line = Total of both
   - Hover over data points for exact values

## Widget Registration

Widgets are automatically discovered by Filament. The configuration in `AdminPanelProvider.php`:

```php
->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
```

## Performance Considerations

### Optimization Features
1. **No Auto-Refresh:** Polling is disabled (`pollingInterval = null`) to reduce database load
2. **Efficient Queries:** Uses indexed `created_at` and `status` columns
3. **Sparkline Limiting:** Sparkline data is limited to max 7 data points
4. **Chart Intervals:** Automatically adjusts based on time range (hourly, daily, weekly)

### Database Indexes
Ensure the following indexes exist on `heir_certificates` table:
```sql
-- Already exists from migrations
INDEX idx_created_at (created_at)
INDEX idx_status (status)
```

## Customization

### Changing Colors
To modify chart colors, edit the `getData()` method in `HeirCertificateChartWidget.php`:

```php
[
    'label' => 'On Progress',
    'backgroundColor' => 'rgba(251, 146, 60, 0.2)', // Orange with 20% opacity
    'borderColor' => 'rgb(251, 146, 60)',           // Solid orange
]
```

### Changing Default Filter
To change the default filter, modify the `$filter` property:

```php
public ?string $filter = 'month'; // Changed from 'week' to 'month'
```

### Adding New Filters
Add new filter options in the `getFilters()` method:

```php
protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last 7 Days',
        'month' => 'Last 30 Days',
        'quarter' => 'Last 90 Days', // New filter
        'year' => 'Last 365 Days',
    ];
}
```

Then add the corresponding logic in `getDateRange()` method.

### Changing Chart Type
To change from line chart to bar chart, modify `HeirCertificateChartWidget.php`:

```php
protected function getType(): string
{
    return 'bar'; // Changed from 'line' to 'bar'
}
```

## Displaying on Specific Pages

### Show on Dashboard Only (Current Setup)
Widgets are automatically shown on the dashboard by default.

### Show on Resource Page
To display these widgets on the HeirCertificateResource list page:

1. Open `app/Filament/Resources/HeirCertificateResource/Pages/ListHeirCertificates.php`
2. Add the `getHeaderWidgets()` method:

```php
protected function getHeaderWidgets(): array
{
    return [
        \App\Filament\Widgets\HeirCertificateStatsWidget::class,
        \App\Filament\Widgets\HeirCertificateChartWidget::class,
    ];
}
```

### Control Widget Width
To make widgets span the full width:

```php
protected static ?int $columnSpan = 'full'; // Full width
// or
protected static ?int $columnSpan = 2; // Span 2 columns in a grid
```

## Troubleshooting

### Widgets Not Showing
1. Clear cache: `php artisan cache:clear`
2. Clear view cache: `php artisan view:clear`
3. Verify `discoverWidgets()` is configured in `AdminPanelProvider`
4. Check file permissions on widget files

### Data Not Loading
1. Verify `HeirCertificate` model exists
2. Check database connection
3. Ensure `created_at` and `status` columns exist in `heir_certificates` table
4. Verify `CertificateStatus` enum has `ON_PROGRESS` and `COMPLETED` cases

### Chart Not Rendering
1. Ensure Chart.js is loaded (automatically included with Filament)
2. Check browser console for JavaScript errors
3. Verify `getData()` returns proper array structure

## File Locations

```
app/
└── Filament/
    └── Widgets/
        ├── HeirCertificateStatsWidget.php
        ├── HeirCertificateChartWidget.php
        └── README.md (this file)
```

## Dependencies

These widgets rely on:
- **Filament v3**: Base widget classes
- **Laravel**: Eloquent ORM, Carbon dates
- **Chart.js**: Chart rendering (included with Filament)
- **App\Models\HeirCertificate**: Certificate model
- **App\Enums\CertificateStatus**: Status enum

## Support

For issues or questions:
1. Check the Filament documentation: https://filamentphp.com/docs
2. Review the widget source code comments
3. Verify your Laravel and Filament versions are compatible

## Version History

- **v1.0.0** (2025-11-21): Initial creation with stats and chart widgets
