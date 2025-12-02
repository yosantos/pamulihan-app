<?php

namespace App\Filament\Widgets;

use App\Models\NationalIdApplicant;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class NationalIdApplicantOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    /**
     * Widget sort order on the dashboard.
     */
    protected static ?int $sort = 2;

    /**
     * Get the stats for the overview section.
     *
     * @return array
     */
    protected function getStats(): array
    {
        // Get filters from page
        $startDate = $this->filters['start_date'] ?? now()->subDays(6)->startOfDay();
        $endDate = $this->filters['end_date'] ?? now()->endOfDay();

        // Ensure dates are Carbon instances
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Current period counts
        $total = NationalIdApplicant::whereBetween('date', [$startDate, $endDate])->count();

        return [
            Stat::make(__('dashboard.national_id_applicants'), $total)
                ->description(__('dashboard.total_applicants'))
                ->descriptionIcon('heroicon-o-identification')
                ->color('success'),
        ];
    }
}
