<?php

namespace App\Services;

use DateTime;

class DataTransforms
{
    public static function dateFormat(string $date, string $format = 'd/m/Y'): string
    {
        $originalDate = DateTime::createFromFormat($format, $date);
        return $originalDate ? $originalDate->format('Y-m-d') : $date;
    }
}
