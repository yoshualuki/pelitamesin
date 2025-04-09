<?php

namespace App\Helpers;

class NumberHelper
{
    public static function formatWithThousandSeparator($number)
    {
        return number_format($number, 0, ',', '.');
    }
}