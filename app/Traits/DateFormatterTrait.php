<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

trait DateFormatterTrait
{
    public function formatDate($dateString, $inputFormat = 'd-m-Y', $outputFormat = 'Y-m-d')
    {
        try {
            return Carbon::createFromFormat($inputFormat, $dateString)->format($outputFormat);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            return ValidationException::withMessages([
                'date_field' => "Invalid date format for '$dateString'. Expected format: $inputFormat."
            ]);
        } catch (\Exception $e) {
            return ValidationException::withMessages([
                'date_field' => "An unexpected error occurred while formatting the date."
            ]);
        }
    }
}
