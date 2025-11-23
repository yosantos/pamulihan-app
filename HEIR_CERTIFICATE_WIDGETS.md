# Heir Certificate Dashboard Widgets - Complete Documentation

## Overview

Two comprehensive dashboard widgets have been created for the Heir Certificate resource, providing real-time analytics and insights for certificate management.

## Files Created

### 1. HeirCertificateStatsWidget
**Location:** `/app/Filament/Widgets/HeirCertificateStatsWidget.php`

A stats overview widget displaying three key metrics with comparison to previous periods.

### 2. HeirCertificateChartWidget
**Location:** `/app/Filament/Widgets/HeirCertificateChartWidget.php`

A line chart widget showing certificate creation trends over time.

### 3. Documentation
**Location:** `/app/Filament/Widgets/README.md`

Comprehensive documentation for both widgets including usage, customization, and troubleshooting.

---

## Widget Features

### HeirCertificateStatsWidget

#### Three Stat Cards

1. **Total Certificates**
   - Count of all certificates in selected period
   - Color: Primary (Blue)
   - Icon: Document text
   - Includes sparkline chart

2. **On Progress**
   - Count of certificates with "ON_PROGRESS" status
   - Color: Warning (Orange)
   - Icon: Clock
   - Includes sparkline chart

3. **Completed**
   - Count of certificates with "COMPLETED" status
   - Color: Success (Green)
   - Icon: Check circle
   - Includes sparkline chart

#### Date Range Filters

- **Today**: Current day only
- **Last 7 Days**: Previous week (default)
- **Last 30 Days**: Previous month
- **Last 365 Days**: Previous year

#### Comparison Metrics

Each stat card shows:
- Current period count
- Percentage change from previous period
- Trend indicator (up/down/flat arrow)
- Small sparkline chart showing trend

Example: "+15.2% from previous period ↑"

---

### HeirCertificateChartWidget

#### Multi-Dataset Line Chart

Three data series:

1. **On Progress Line**
   - Color: Orange (rgba(251, 146, 60))
   - Shows certificates with ON_PROGRESS status
   - Filled area with 20% opacity

2. **Completed Line**
   - Color: Green (rgba(34, 197, 94))
   - Shows certificates with COMPLETED status
   - Filled area with 20% opacity

3. **Total Line**
   - Color: Blue (rgba(59, 130, 246))
   - Shows combined total
   - Dashed line style
   - Filled area with 20% opacity

#### Dynamic Intervals

Chart automatically adjusts intervals based on selected filter:

- **Today**: Hourly breakdown (24 data points)
- **Last 7 Days**: Daily breakdown (7 data points)
- **Last 30 Days**: Daily breakdown (30 data points)
- **Last 365 Days**: Weekly breakdown (~52 data points)

#### Interactive Features

- Hover tooltips showing exact counts
- Click legend items to toggle datasets on/off
- Smooth line curves (tension: 0.4)
- Responsive design adapts to screen size

---

## Visual Layout

### Dashboard Display

```
┌──────────────────────────────────────────────────────────────────┐
│  Pamulihan Admin Dashboard                                       │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  HeirCertificateStatsWidget           [Filter: Last 7 Days ▼]   │
│  ┌─────────────┬─────────────┬─────────────┐                   │
│  │   Total     │ On Progress │  Completed  │                   │
│  │    50       │     30      │     20      │                   │
│  │  Primary    │   Warning   │   Success   │                   │
│  │ ─────       │ ─────       │ ─────       │                   │
│  │ +15.2% ↑    │ +10.0% ↑    │ +25.0% ↑    │                   │
│  │ from prev   │ from prev   │ from prev   │                   │
│  └─────────────┴─────────────┴─────────────┘                   │
│                                                                  │
│  HeirCertificateChartWidget           [Filter: Last 7 Days ▼]   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Heir Certificates Trend                                 │   │
│  │                                                           │   │
│  │   40 ┤                                     •────•         │   │
│  │   35 ┤                          •────•               •   │   │
│  │   30 ┤               •────•                              │   │
│  │   25 ┤    •────•                                         │   │
│  │   20 ┤                                                    │   │
│  │      └────┬────┬────┬────┬────┬────┬────┐               │   │
│  │         Nov15 Nov16 Nov17 Nov18 Nov19 Nov20 Nov21       │   │
│  │                                                           │   │
│  │  Legend: ■ On Progress  ■ Completed  ■ ■ Total          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Implementation Details

### Widget Registration

Widgets are automatically discovered by the AdminPanelProvider:

**File:** `app/Providers/Filament/AdminPanelProvider.php`

```php
->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
```

No additional registration needed - widgets appear automatically on dashboard.

---

## Code Architecture

### HeirCertificateStatsWidget Structure

```php
class HeirCertificateStatsWidget extends BaseWidget
{
    // Properties
    protected static ?int $sort = 1;              // Display order
    public ?string $filter = 'week';              // Default filter

    // Main Methods
    protected function getStats(): array          // Returns stat cards
    protected function getFilters(): ?array       // Returns filter options

    // Helper Methods
    protected function getDateRange(string $filter): array
    protected function getComparisonDescription(int $current, int $previous): string
    protected function getComparisonIcon(int $current, int $previous): ?string
    protected function getSparklineData(Carbon $startDate, Carbon $endDate, ?CertificateStatus $status): array
}
```

### HeirCertificateChartWidget Structure

```php
class HeirCertificateChartWidget extends ChartWidget
{
    // Properties
    protected static ?string $heading = 'Heir Certificates Trend';
    protected static ?int $sort = 2;              // Display order
    protected static ?string $maxHeight = '300px';
    public ?string $filter = 'week';              // Default filter

    // Main Methods
    protected function getData(): array           // Returns chart data
    protected function getType(): string          // Returns 'line'
    protected function getFilters(): ?array       // Returns filter options
    protected function getOptions(): array        // Chart.js options

    // Helper Methods
    protected function getDateRange(string $filter): array
    protected function getInterval(): string      // '1 hour', '1 day', '1 week'
    protected function getDateFormat(): string    // 'H:00', 'M d'
    protected function getEndOfInterval(Carbon $date): Carbon
}
```

---

## Database Queries

### Stats Widget Queries

For each stat card (Total, On Progress, Completed):

```sql
-- Current period count
SELECT COUNT(*) FROM heir_certificates
WHERE created_at BETWEEN ? AND ?
AND status = ?;

-- Previous period count (for comparison)
SELECT COUNT(*) FROM heir_certificates
WHERE created_at BETWEEN ? AND ?
AND status = ?;

-- Sparkline data (per day/interval)
SELECT COUNT(*) FROM heir_certificates
WHERE DATE(created_at) = ?
AND status = ?;
```

### Chart Widget Queries

For each data point in the chart:

```sql
-- On Progress count for date range
SELECT COUNT(*) FROM heir_certificates
WHERE created_at >= ? AND created_at <= ?
AND status = 'on_progress';

-- Completed count for date range
SELECT COUNT(*) FROM heir_certificates
WHERE created_at >= ? AND created_at <= ?
AND status = 'completed';
```

---

## Performance Optimization

### Built-in Optimizations

1. **No Auto-Polling**
   ```php
   protected static ?string $pollingInterval = null;
   ```
   Prevents automatic refresh to reduce database load

2. **Indexed Queries**
   - Queries use `created_at` (indexed timestamp)
   - Queries use `status` (indexed enum)

3. **Limited Data Points**
   - Sparklines limited to 7 points maximum
   - Chart intervals adjust based on time range

4. **Efficient Date Ranges**
   - Uses Carbon for optimized date calculations
   - CarbonPeriod for efficient iteration

### Recommended Indexes

Ensure these indexes exist on `heir_certificates` table:

```sql
CREATE INDEX idx_heir_certificates_created_at ON heir_certificates(created_at);
CREATE INDEX idx_heir_certificates_status ON heir_certificates(status);
CREATE INDEX idx_heir_certificates_status_created ON heir_certificates(status, created_at);
```

---

## Customization Guide

### Change Default Filter

Both widgets default to "Last 7 Days". To change:

```php
// Change this line in both widget files
public ?string $filter = 'month'; // Changed from 'week'
```

### Change Chart Colors

Edit `getData()` in HeirCertificateChartWidget.php:

```php
[
    'label' => 'On Progress',
    'backgroundColor' => 'rgba(255, 0, 0, 0.2)',  // Red background
    'borderColor' => 'rgb(255, 0, 0)',            // Red border
]
```

### Change Chart Type

Change line chart to bar chart:

```php
protected function getType(): string
{
    return 'bar'; // Changed from 'line'
}
```

### Add Custom Filter

Add new filter option:

```php
protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last 7 Days',
        'month' => 'Last 30 Days',
        'quarter' => 'Last 90 Days',    // New filter
        'year' => 'Last 365 Days',
    ];
}
```

Then add handling in `getDateRange()`:

```php
case 'quarter':
    $startDate = now()->subDays(89)->startOfDay();
    $previousEndDate = now()->subDays(90)->endOfDay();
    $previousStartDate = now()->subDays(179)->startOfDay();
    break;
```

### Display on Resource Page

To show widgets on the HeirCertificate resource list page:

**File:** `app/Filament/Resources/HeirCertificateResource/Pages/ListHeirCertificates.php`

```php
protected function getHeaderWidgets(): array
{
    return [
        \App\Filament\Widgets\HeirCertificateStatsWidget::class,
        \App\Filament\Widgets\HeirCertificateChartWidget::class,
    ];
}
```

---

## Testing the Widgets

### Access the Dashboard

1. Navigate to: `http://your-domain/admin/dashboard`
2. You should see both widgets displayed
3. Try changing the filter dropdown
4. Hover over chart lines to see tooltips

### Test Filters

1. **Today**: Should show only today's data
2. **Last 7 Days**: Should show past week
3. **Last 30 Days**: Should show past month
4. **Last 365 Days**: Should show past year with weekly intervals

### Test Data

Create test certificates with different statuses and dates:

```bash
php artisan tinker
```

```php
// Create test certificates
$statuses = ['on_progress', 'completed'];
$dates = collect(range(0, 30))->map(fn($i) => now()->subDays($i));

foreach ($dates as $date) {
    HeirCertificate::create([
        'certificate_date' => $date,
        'applicant_name' => 'Test Applicant',
        'applicant_address' => 'Test Address',
        'deceased_name' => 'Test Deceased',
        'place_of_death' => 'Test Place',
        'date_of_death' => $date->subYears(1),
        'status' => $statuses[array_rand($statuses)],
        'created_at' => $date,
    ]);
}
```

---

## Troubleshooting

### Widgets Not Appearing

**Solution:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### No Data Showing

**Check:**
1. Database has HeirCertificate records
2. Records have `created_at` timestamps
3. Records have valid `status` enum values

**Debug:**
```php
// In widget, add to getStats() or getData():
dd(HeirCertificate::count()); // Check total records
dd($startDate, $endDate);      // Check date range
```

### Chart Not Rendering

**Check:**
1. Browser console for JavaScript errors
2. Chart.js is loaded (automatic with Filament)
3. `getData()` returns valid array structure

**Debug:**
```php
// In HeirCertificateChartWidget
protected function getData(): array
{
    $data = [/* your data */];
    dd($data); // Inspect data structure
    return $data;
}
```

### Performance Issues

**Solutions:**
1. Add database indexes (see Performance Optimization section)
2. Reduce sparkline data points
3. Increase chart intervals for large date ranges
4. Consider caching for expensive queries

---

## Dependencies

- **Laravel 10+**: Framework
- **Filament 3**: Admin panel framework
- **Chart.js**: Chart rendering (included with Filament)
- **Carbon**: Date manipulation
- **App\Models\HeirCertificate**: Certificate model
- **App\Enums\CertificateStatus**: Status enum (ON_PROGRESS, COMPLETED)

---

## Security Considerations

### Authorization

Widgets respect Filament's built-in authorization. Users must be authenticated to view dashboard.

To add custom authorization:

```php
public static function canView(): bool
{
    return auth()->user()->can('view_heir_certificate_stats');
}
```

### Data Privacy

Widgets only show aggregate counts, no sensitive personal data is displayed in widgets.

---

## Future Enhancements

### Possible Additions

1. **Export Functionality**
   - Export chart as PNG/PDF
   - Export stats as CSV

2. **Additional Metrics**
   - Average processing time
   - Certificates by person in charge
   - Heirs per certificate average

3. **Interactive Filtering**
   - Click stat card to filter chart
   - Date range picker instead of presets

4. **Real-time Updates**
   - Enable polling for live updates
   - WebSocket integration

5. **Drill-down Capability**
   - Click chart to see certificate list
   - Filter by date range on click

---

## Support and Resources

### Documentation
- **Filament Widgets**: https://filamentphp.com/docs/3.x/panels/dashboard#widgets
- **Filament Stats**: https://filamentphp.com/docs/3.x/widgets/stats-overview
- **Filament Charts**: https://filamentphp.com/docs/3.x/widgets/charts

### File Locations
```
/app
  /Filament
    /Widgets
      ├── HeirCertificateStatsWidget.php    (Stats overview)
      ├── HeirCertificateChartWidget.php    (Line chart)
      └── README.md                         (Technical docs)
/HEIR_CERTIFICATE_WIDGETS.md                (This file)
```

---

## Summary

Two professional dashboard widgets have been created for comprehensive Heir Certificate analytics:

1. **HeirCertificateStatsWidget** - Shows key metrics with comparison
2. **HeirCertificateChartWidget** - Displays trends over time

Both widgets feature:
- Date range filtering (Today, Week, Month, Year)
- Professional color-coding (orange for on_progress, green for completed)
- Responsive design
- Performance optimization
- Clean, maintainable code following Laravel and Filament best practices

The widgets are automatically discovered and displayed on the admin dashboard at `/admin/dashboard`.
