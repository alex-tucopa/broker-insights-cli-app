<?php

namespace App\Validators;

use DateTime;

class Constraints
{
    public static function minLength($input, $length)
    {
        return is_string($input) && (strlen($input) >= $length);
    }

    public static function maxLength($input, $length)
    {
        return is_string($input) && (strlen($input) <= $length);
    }

    public static function numeric($input)
    {
        return is_numeric($input);
    }

    public static function string($input)
    {
        return is_string($input);
    }

    public static function dateFormat($input, $format = 'Y-m-d')
    {
        $dateTime = DateTime::createFromFormat($format, $input);
        return $dateTime && $dateTime->format($format) === $input;
    }
}
