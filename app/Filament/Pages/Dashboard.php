<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('dashboard.filter_date_range'))
                    ->schema([
                        DatePicker::make('start_date')
                            ->label(__('dashboard.start_date'))
                            ->default(now()->subDays(6))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(fn ($get) => $get('end_date') ?: now()),

                        DatePicker::make('end_date')
                            ->label(__('dashboard.end_date'))
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(fn ($get) => $get('start_date'))
                            ->maxDate(now()),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
