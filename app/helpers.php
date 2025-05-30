<?php

if (!function_exists('fa_number')) {
    /**
     * تبدیل عدد انگلیسی به فارسی با جداکننده (هزارگان و اعشاری)
     * @param string|float|int $number
     * @param int $decimals
     * @return string
     */
    function fa_number($number, $decimals = 0)
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', ','];
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫', '٬'];
        $number = number_format($number, $decimals, '.', ',');
        return str_replace($en, $fa, $number);
    }
}
