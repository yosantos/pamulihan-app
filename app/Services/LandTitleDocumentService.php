<?php

namespace App\Services;

use App\Models\LandTitle;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LandTitleDocumentService
{
    /**
     * Map of Indonesian day names
     */
    private const INDONESIAN_DAYS = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    /**
     * Map of Indonesian month names
     */
    private const INDONESIAN_MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Generate document for a land title
     *
     * @param LandTitle $landTitle
     * @return string Path to generated file
     * @throws \Exception
     */
    public function generate(LandTitle $landTitle): string
    {
        // Load relationships
        $landTitle->load([
            'landTitleType',
            'spptLandTitle.village',
            'letterCLandTitle',
            'landTitleApplicants.user',
            'landTitleApplicants.applicantType',
            'creator'
        ]);

        // Get template path
        $templatePath = $this->getTemplate($landTitle);

        if (!file_exists($templatePath)) {
            throw new \Exception("Template not found: {$templatePath}");
        }

        // Create template processor
        $template = new TemplateProcessor($templatePath);

        // Fill all placeholders
        $this->fillPlaceholders($template, $landTitle);

        // Generate output filename
        $outputFilename = $this->generateOutputFilename($landTitle);
        $outputPath = storage_path('app/generated_documents/' . $outputFilename);

        // Save the document
        $template->saveAs($outputPath);

        return $outputPath;
    }

    /**
     * Get the template path based on land title type and applicant configuration
     *
     * @param LandTitle $landTitle
     * @return string
     */
    private function getTemplate(LandTitle $landTitle): string
    {
        $code = $landTitle->landTitleType->code ?? 'sale_purchase';

        // Count applicants by type
        $sellersCount = $landTitle->applicantsByType('Seller')->count();
        $buyersCount = $landTitle->applicantsByType('Buyer')->count();

        // Determine template configuration
        $sellerConfig = $sellersCount > 1 ? 'multiple_sellers' : 'single_seller';
        $buyerConfig = $buyersCount > 1 ? 'multiple_buyers' : 'single_buyer';

        $templateName = "{$code}_{$sellerConfig}_{$buyerConfig}.docx";
        $templatePath = storage_path("app/templates/land_titles/{$code}/{$templateName}");

        // Fallback to default template if specific configuration not found
        if (!file_exists($templatePath)) {
            $templatePath = storage_path("app/templates/land_titles/{$code}/sale_purchase_multiple_sellers_single_buyer.docx");
        }

        return $templatePath;
    }

    /**
     * Fill all placeholders in the template
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillPlaceholders(TemplateProcessor $template, LandTitle $landTitle): void
    {
        $this->fillPPATInfo($template, $landTitle);
        $this->fillDocumentInfo($template, $landTitle);
        $this->fillSellers($template, $landTitle);
        $this->fillBuyer($template, $landTitle);
        $this->fillLandInfo($template, $landTitle);
        $this->fillTransactionInfo($template, $landTitle);
        $this->fillWitnesses($template, $landTitle);
    }

    /**
     * Fill PPAT (Notary) information
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillPPATInfo($template, $landTitle): void
    {
        $creator = $landTitle->creator;

        $template->setValue('ppat_name', $creator->name ?? '-');
        $template->setValue('ppat_address', $this->formatAddress($creator) ?? '-');
    }

    /**
     * Fill document information (number, date, etc.)
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillDocumentInfo($template, $landTitle): void
    {
        $documentDate = Carbon::parse($landTitle->created_at);

        $template->setValue('document_number', $landTitle->formatted_number ?? '-');
        $template->setValue('document_day', $this->getIndonesianDayName($documentDate));
        $template->setValue('document_date', $documentDate->day);
        $template->setValue('document_month', $this->getIndonesianMonthName($documentDate->month));
        $template->setValue('document_year', $documentDate->year);
        $template->setValue('document_year_words', $this->numberToWords($documentDate->year));
    }

    /**
     * Fill sellers information (handle multiple sellers)
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillSellers($template, $landTitle): void
    {
        $sellers = $landTitle->applicantsByType('Seller')->get();

        if ($sellers->isEmpty()) {
            $template->setValue('seller_name', '-');
            $template->setValue('seller_birthplace', '-');
            $template->setValue('seller_birthdate', '-');
            $template->setValue('seller_age', '-');
            $template->setValue('seller_occupation', '-');
            $template->setValue('seller_national_id_number', '-');
            $template->setValue('seller_address', '-');
            return;
        }

        // Check if template has seller count variable for cloning
        try {
            $template->cloneRow('seller_name', $sellers->count());

            foreach ($sellers as $index => $seller) {
                $rowIndex = $index + 1;
                $user = $seller->user;

                $template->setValue("seller_name#{$rowIndex}", $user->name ?? '-');
                $template->setValue("seller_birthplace#{$rowIndex}", $user->birthplace ?? '-');
                $template->setValue("seller_birthdate#{$rowIndex}",
                    $user->birthdate ? $this->formatIndonesianDate($user->birthdate) : '-');
                $template->setValue("seller_age#{$rowIndex}",
                    $user->birthdate ? $this->calculateAge($user->birthdate) : '-');
                $template->setValue("seller_occupation#{$rowIndex}", $user->occupation ?? '-');
                $template->setValue("seller_national_id_number#{$rowIndex}", $user->national_id_number ?? '-');
                $template->setValue("seller_address#{$rowIndex}", $this->formatAddress($user) ?? '-');
            }
        } catch (\Exception $e) {
            // If cloning fails, just fill single seller
            $firstSeller = $sellers->first();
            $user = $firstSeller->user;

            $template->setValue('seller_name', $user->name ?? '-');
            $template->setValue('seller_birthplace', $user->birthplace ?? '-');
            $template->setValue('seller_birthdate',
                $user->birthdate ? $this->formatIndonesianDate($user->birthdate) : '-');
            $template->setValue('seller_age',
                $user->birthdate ? $this->calculateAge($user->birthdate) : '-');
            $template->setValue('seller_occupation', $user->occupation ?? '-');
            $template->setValue('seller_national_id_number', $user->national_id_number ?? '-');
            $template->setValue('seller_address', $this->formatAddress($user) ?? '-');
        }
    }

    /**
     * Fill buyer information
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillBuyer($template, $landTitle): void
    {
        $buyer = $landTitle->applicantsByType('Buyer')->first();

        if (!$buyer) {
            $template->setValue('buyer_name', '-');
            $template->setValue('buyer_birthplace', '-');
            $template->setValue('buyer_birthdate', '-');
            $template->setValue('buyer_age', '-');
            $template->setValue('buyer_occupation', '-');
            $template->setValue('buyer_national_id_number', '-');
            $template->setValue('buyer_address', '-');
            return;
        }

        $user = $buyer->user;

        $template->setValue('buyer_name', $user->name ?? '-');
        $template->setValue('buyer_birthplace', $user->birthplace ?? '-');
        $template->setValue('buyer_birthdate',
            $user->birthdate ? $this->formatIndonesianDate($user->birthdate) : '-');
        $template->setValue('buyer_age',
            $user->birthdate ? $this->calculateAge($user->birthdate) : '-');
        $template->setValue('buyer_occupation', $user->occupation ?? '-');
        $template->setValue('buyer_national_id_number', $user->national_id_number ?? '-');
        $template->setValue('buyer_address', $this->formatAddress($user) ?? '-');
    }

    /**
     * Fill land information (SPPT, Letter C, borders)
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillLandInfo($template, $landTitle): void
    {
        // SPPT Information
        if ($landTitle->spptLandTitle) {
            $sppt = $landTitle->spptLandTitle;
            $template->setValue('sppt_number', $sppt->number ?? '-');
            $template->setValue('sppt_year', $sppt->year ?? '-');
            $template->setValue('sppt_owner', $sppt->owner ?? '-');
            $template->setValue('sppt_block', $sppt->block ?? '-');
            $template->setValue('sppt_land_area', $sppt->land_area ?? '-');
            $template->setValue('sppt_building_area', $sppt->building_area ?? '-');
            $template->setValue('sppt_village', $sppt->village->name ?? '-');
        } else {
            $template->setValue('sppt_number', '-');
            $template->setValue('sppt_year', '-');
            $template->setValue('sppt_owner', '-');
            $template->setValue('sppt_block', '-');
            $template->setValue('sppt_land_area', '-');
            $template->setValue('sppt_building_area', '-');
            $template->setValue('sppt_village', '-');
        }

        // Letter C Information
        if ($landTitle->letterCLandTitle) {
            $letterC = $landTitle->letterCLandTitle;
            $template->setValue('letter_c_name', $letterC->name ?? '-');
            $template->setValue('letter_c_number', $letterC->number_of_c ?? '-');
            $template->setValue('letter_c_persil', $letterC->number_of_persil ?? '-');
            $template->setValue('letter_c_class', $letterC->class ?? '-');
            $template->setValue('letter_c_land_area', $letterC->land_area ?? '-');
            $template->setValue('letter_c_date',
                $letterC->date ? $this->formatIndonesianDate($letterC->date) : '-');
        } else {
            $template->setValue('letter_c_name', '-');
            $template->setValue('letter_c_number', '-');
            $template->setValue('letter_c_persil', '-');
            $template->setValue('letter_c_class', '-');
            $template->setValue('letter_c_land_area', '-');
            $template->setValue('letter_c_date', '-');
        }

        // Land area and borders
        $template->setValue('land_area', $landTitle->area_of_the_land ?? '-');
        $template->setValue('land_area_words', $landTitle->area_of_the_land_wording ??
            ($landTitle->area_of_the_land ? $this->numberToWords($landTitle->area_of_the_land) : '-'));

        $template->setValue('north_border', $landTitle->north_border ?? '-');
        $template->setValue('east_border', $landTitle->east_border ?? '-');
        $template->setValue('south_border', $landTitle->south_border ?? '-');
        $template->setValue('west_border', $landTitle->west_border ?? '-');
    }

    /**
     * Fill transaction information (amount, fees, taxes)
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillTransactionInfo($template, $landTitle): void
    {
        // Transaction amount
        $template->setValue('transaction_amount',
            $landTitle->transaction_amount ? number_format($landTitle->transaction_amount, 0, ',', '.') : '-');
        $template->setValue('transaction_amount_words',
            $landTitle->transaction_amount_wording ??
            ($landTitle->transaction_amount ? $this->numberToWords($landTitle->transaction_amount) : '-'));

        // Fees and taxes
        $template->setValue('pph',
            $landTitle->pph ? number_format($landTitle->pph, 0, ',', '.') : '-');
        $template->setValue('bphtb',
            $landTitle->bphtb ? number_format($landTitle->bphtb, 0, ',', '.') : '-');
        $template->setValue('adm',
            $landTitle->adm ? number_format($landTitle->adm, 0, ',', '.') : '-');
        $template->setValue('pbb',
            $landTitle->pbb ? number_format($landTitle->pbb, 0, ',', '.') : '-');
        $template->setValue('adm_certificate',
            $landTitle->adm_certificate ? number_format($landTitle->adm_certificate, 0, ',', '.') : '-');

        // Total amount
        $template->setValue('total_amount',
            $landTitle->total_amount ? number_format($landTitle->total_amount, 0, ',', '.') : '-');
    }

    /**
     * Fill witnesses information (handle multiple witnesses)
     *
     * @param TemplateProcessor $template
     * @param LandTitle $landTitle
     * @return void
     */
    private function fillWitnesses($template, $landTitle): void
    {
        $witnesses = $landTitle->applicantsByType('Witness')->get();

        if ($witnesses->isEmpty()) {
            // Fill default witness placeholders
            for ($i = 1; $i <= 2; $i++) {
                $template->setValue("witness_{$i}_name", '-');
                $template->setValue("witness_{$i}_birthplace", '-');
                $template->setValue("witness_{$i}_birthdate", '-');
                $template->setValue("witness_{$i}_age", '-');
                $template->setValue("witness_{$i}_occupation", '-');
                $template->setValue("witness_{$i}_national_id_number", '-');
                $template->setValue("witness_{$i}_address", '-');
            }
            return;
        }

        // Try to clone witnesses if template supports it
        try {
            $template->cloneRow('witness_name', $witnesses->count());

            foreach ($witnesses as $index => $witness) {
                $rowIndex = $index + 1;
                $user = $witness->user;

                $template->setValue("witness_name#{$rowIndex}", $user->name ?? '-');
                $template->setValue("witness_birthplace#{$rowIndex}", $user->birthplace ?? '-');
                $template->setValue("witness_birthdate#{$rowIndex}",
                    $user->birthdate ? $this->formatIndonesianDate($user->birthdate) : '-');
                $template->setValue("witness_age#{$rowIndex}",
                    $user->birthdate ? $this->calculateAge($user->birthdate) : '-');
                $template->setValue("witness_occupation#{$rowIndex}", $user->occupation ?? '-');
                $template->setValue("witness_national_id_number#{$rowIndex}", $user->national_id_number ?? '-');
                $template->setValue("witness_address#{$rowIndex}", $this->formatAddress($user) ?? '-');
            }
        } catch (\Exception $e) {
            // If cloning fails, fill numbered witnesses (witness_1_name, witness_2_name, etc.)
            foreach ($witnesses as $index => $witness) {
                $witnessNumber = $index + 1;
                $user = $witness->user;

                $template->setValue("witness_{$witnessNumber}_name", $user->name ?? '-');
                $template->setValue("witness_{$witnessNumber}_birthplace", $user->birthplace ?? '-');
                $template->setValue("witness_{$witnessNumber}_birthdate",
                    $user->birthdate ? $this->formatIndonesianDate($user->birthdate) : '-');
                $template->setValue("witness_{$witnessNumber}_age",
                    $user->birthdate ? $this->calculateAge($user->birthdate) : '-');
                $template->setValue("witness_{$witnessNumber}_occupation", $user->occupation ?? '-');
                $template->setValue("witness_{$witnessNumber}_national_id_number", $user->national_id_number ?? '-');
                $template->setValue("witness_{$witnessNumber}_address", $this->formatAddress($user) ?? '-');
            }
        }
    }

    /**
     * Format date in Indonesian format (e.g., "15 November 2025")
     *
     * @param mixed $date
     * @return string
     */
    private function formatIndonesianDate($date): string
    {
        $carbonDate = Carbon::parse($date);
        $day = $carbonDate->day;
        $month = $this->getIndonesianMonthName($carbonDate->month);
        $year = $carbonDate->year;

        return "{$day} {$month} {$year}";
    }

    /**
     * Get Indonesian day name
     *
     * @param Carbon $date
     * @return string
     */
    private function getIndonesianDayName(Carbon $date): string
    {
        $englishDay = $date->format('l');
        return self::INDONESIAN_DAYS[$englishDay] ?? $englishDay;
    }

    /**
     * Get Indonesian month name
     *
     * @param int $monthNumber
     * @return string
     */
    private function getIndonesianMonthName(int $monthNumber): string
    {
        return self::INDONESIAN_MONTHS[$monthNumber] ?? '';
    }

    /**
     * Calculate age from birthdate
     *
     * @param mixed $birthdate
     * @return string
     */
    private function calculateAge($birthdate): string
    {
        $age = Carbon::parse($birthdate)->age;
        return "{$age} tahun";
    }

    /**
     * Format user address
     *
     * @param \App\Models\User|null $user
     * @return string
     */
    private function formatAddress($user): string
    {
        if (!$user) {
            return '-';
        }

        $addressParts = array_filter([
            $user->road,
            $user->village,
            $user->district,
            $user->city,
            $user->province,
        ]);

        $rtRw = '';
        if ($user->rt || $user->rw) {
            $rtRw = 'RT ' . ($user->rt ?? '-') . ' / RW ' . ($user->rw ?? '-');
            array_unshift($addressParts, $rtRw);
        }

        return !empty($addressParts) ? implode(', ', $addressParts) : '-';
    }

    /**
     * Convert number to Indonesian words
     * This is a simplified version - you may want to use a dedicated package
     *
     * @param mixed $number
     * @return string
     */
    private function numberToWords($number): string
    {
        if (!$number) {
            return 'nol';
        }

        $number = (int) $number;

        $ones = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
            'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
        ];

        if ($number < 20) {
            return $ones[$number];
        }

        if ($number < 100) {
            $tens = (int) ($number / 10);
            $remainder = $number % 10;
            return ($tens == 1 ? 'sepuluh' : $ones[$tens] . ' puluh') .
                   ($remainder > 0 ? ' ' . $ones[$remainder] : '');
        }

        if ($number < 200) {
            $remainder = $number % 100;
            return 'seratus' . ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000) {
            $hundreds = (int) ($number / 100);
            $remainder = $number % 100;
            return $ones[$hundreds] . ' ratus' .
                   ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 2000) {
            $remainder = $number % 1000;
            return 'seribu' . ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000) {
            $thousands = (int) ($number / 1000);
            $remainder = $number % 1000;
            return $this->numberToWords($thousands) . ' ribu' .
                   ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000000) {
            $millions = (int) ($number / 1000000);
            $remainder = $number % 1000000;
            return $this->numberToWords($millions) . ' juta' .
                   ($remainder > 0 ? ' ' . $this->numberToWords($remainder) : '');
        }

        return (string) $number; // Fallback for very large numbers
    }

    /**
     * Generate output filename
     *
     * @param LandTitle $landTitle
     * @return string
     */
    private function generateOutputFilename(LandTitle $landTitle): string
    {
        $typeCode = $landTitle->landTitleType->code ?? 'land_title';
        $number = str_replace('/', '_', $landTitle->formatted_number);
        $timestamp = now()->format('YmdHis');

        return "{$typeCode}_{$number}_{$timestamp}.docx";
    }
}
