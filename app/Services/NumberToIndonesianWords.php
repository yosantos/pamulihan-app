<?php

namespace App\Services;

class NumberToIndonesianWords
{
    /**
     * Convert a number to Indonesian words.
     *
     * @param float|int $number
     * @param bool $isCurrency If true, adds "Rupiah" for currency
     * @return string
     */
    public static function convert($number, bool $isCurrency = true): string
    {
        if ($number == 0) {
            return $isCurrency ? 'Nol Rupiah' : 'Nol';
        }

        // Handle negative numbers
        $isNegative = $number < 0;
        $number = abs($number);

        // Separate integer and decimal parts
        $parts = explode('.', (string) $number);
        $integerPart = (int) $parts[0];
        $decimalPart = isset($parts[1]) ? $parts[1] : null;

        $result = self::convertIntegerPart($integerPart);

        // Add decimal part if exists
        if ($decimalPart !== null && $decimalPart > 0) {
            $result .= ' Koma ' . self::convertDecimalPart($decimalPart);
        }

        // Add currency suffix if needed
        if ($isCurrency && $decimalPart === null) {
            $result .= ' Rupiah';
        }

        // Add negative prefix if needed
        if ($isNegative) {
            $result = 'Minus ' . $result;
        }

        return $result;
    }

    /**
     * Convert integer part to words.
     *
     * @param int $number
     * @return string
     */
    private static function convertIntegerPart(int $number): string
    {
        if ($number == 0) {
            return '';
        }

        $words = [
            '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan',
            'Sepuluh', 'Sebelas'
        ];

        if ($number < 12) {
            return $words[$number];
        }

        if ($number < 20) {
            return $words[$number - 10] . ' Belas';
        }

        if ($number < 100) {
            $tens = floor($number / 10);
            $ones = $number % 10;
            return $words[$tens] . ' Puluh' . ($ones > 0 ? ' ' . $words[$ones] : '');
        }

        if ($number < 200) {
            $remainder = $number - 100;
            return 'Seratus' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            return $words[$hundreds] . ' Ratus' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        if ($number < 2000) {
            $remainder = $number - 1000;
            return 'Seribu' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        if ($number < 1000000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            return self::convertIntegerPart($thousands) . ' Ribu' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        if ($number < 1000000000) {
            $millions = floor($number / 1000000);
            $remainder = $number % 1000000;
            return self::convertIntegerPart($millions) . ' Juta' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        if ($number < 1000000000000) {
            $billions = floor($number / 1000000000);
            $remainder = $number % 1000000000;
            return self::convertIntegerPart($billions) . ' Miliar' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
        }

        // For trillions
        $trillions = floor($number / 1000000000000);
        $remainder = $number % 1000000000000;
        return self::convertIntegerPart($trillions) . ' Triliun' . ($remainder > 0 ? ' ' . self::convertIntegerPart($remainder) : '');
    }

    /**
     * Convert decimal part to words (reads each digit).
     *
     * @param string $decimal
     * @return string
     */
    private static function convertDecimalPart(string $decimal): string
    {
        $words = [
            '0' => 'Nol', '1' => 'Satu', '2' => 'Dua', '3' => 'Tiga', '4' => 'Empat',
            '5' => 'Lima', '6' => 'Enam', '7' => 'Tujuh', '8' => 'Delapan', '9' => 'Sembilan'
        ];

        $result = [];
        $digits = str_split($decimal);

        foreach ($digits as $digit) {
            $result[] = $words[$digit];
        }

        return implode(' ', $result);
    }

    /**
     * Convert area to Indonesian words (without currency).
     *
     * @param float|int $area
     * @return string
     */
    public static function convertArea($area): string
    {
        return self::convert($area, false);
    }

    /**
     * Convert currency to Indonesian words.
     *
     * @param float|int $amount
     * @return string
     */
    public static function convertCurrency($amount): string
    {
        return self::convert($amount, true);
    }
}
