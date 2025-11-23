<?php

namespace App\Observers;

use App\Models\LandTitle;
use App\Services\NumberToIndonesianWords;

class LandTitleObserver
{
    /**
     * Handle the LandTitle "creating" event.
     */
    public function creating(LandTitle $landTitle): void
    {
        // Auto-generate number and year
        $year = now()->year;
        $lastNumber = LandTitle::where('year', $year)->max('number') ?? 0;
        $landTitle->number = $lastNumber + 1;
        $landTitle->year = $year;

        // Set created_by to the authenticated user
        if (auth()->check() && !$landTitle->created_by) {
            $landTitle->created_by = auth()->id();
        }

        // Auto-generate wordings and calculate total
        $this->updateCalculatedFields($landTitle);
    }

    /**
     * Handle the LandTitle "updating" event.
     */
    public function updating(LandTitle $landTitle): void
    {
        // Auto-generate wordings and calculate total when fields change
        $this->updateCalculatedFields($landTitle);
    }

    /**
     * Update all calculated fields (wordings and total amount).
     */
    private function updateCalculatedFields(LandTitle $landTitle): void
    {
        // Auto-generate transaction_amount_wording
        if ($landTitle->isDirty('transaction_amount') || !$landTitle->transaction_amount_wording) {
            $landTitle->transaction_amount_wording = NumberToIndonesianWords::convertCurrency(
                $landTitle->transaction_amount ?? 0
            );
        }

        // Auto-generate area_of_the_land_wording
        if ($landTitle->isDirty('area_of_the_land') || !$landTitle->area_of_the_land_wording) {
            $area = $landTitle->area_of_the_land ?? 0;
            if ($area > 0) {
                $landTitle->area_of_the_land_wording = NumberToIndonesianWords::convertArea($area) . ' Meter Persegi';
            } else {
                $landTitle->area_of_the_land_wording = null;
            }
        }

        // Auto-calculate ppat_amount (2% of transaction_amount) if not manually set
        if ($landTitle->isDirty('transaction_amount') && !$landTitle->isDirty('ppat_amount')) {
            $landTitle->ppat_amount = ($landTitle->transaction_amount ?? 0) * 0.02;
        }

        // Auto-calculate total_amount when any fee field changes
        $feeFields = ['pph', 'bphtb', 'adm', 'pbb', 'adm_certificate', 'ppat_amount'];
        $shouldRecalculate = false;

        foreach ($feeFields as $field) {
            if ($landTitle->isDirty($field)) {
                $shouldRecalculate = true;
                break;
            }
        }

        if ($shouldRecalculate || $landTitle->total_amount === null) {
            $landTitle->total_amount =
                ($landTitle->pph ?? 0) +
                ($landTitle->bphtb ?? 0) +
                ($landTitle->adm ?? 0) +
                ($landTitle->pbb ?? 0) +
                ($landTitle->adm_certificate ?? 0) +
                ($landTitle->ppat_amount ?? 0);
        }
    }
}
