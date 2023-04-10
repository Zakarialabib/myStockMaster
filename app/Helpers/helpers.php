<?php

declare(strict_types=1);

if ( ! function_exists('settings')) {
    function settings()
    {
        return cache()->rememberForever('settings', function () {
            return \App\Models\Setting::with('currency')->firstOrFail();
        });
    }
}

if ( ! function_exists('format_currency')) {
    function format_currency($value, $format = true)
    {
        if ( ! $format) {
            return $value;
        }

        $settings = settings();
        $position = $settings->default_currency_position;
        $symbol = $settings->currency->symbol;
        $decimalSeparator = $settings->currency->decimal_separator;
        $thousandSeparator = $settings->currency->thousand_separator;

        return $position === 'prefix'
            ? $symbol.number_format((float) $value, 2, $decimalSeparator, $thousandSeparator)
            : number_format((float) $value, 2, $decimalSeparator, $thousandSeparator).$symbol;
    }
}

if ( ! function_exists('format_date')) {
    function format_date($date, $format = true)
    {
        if (empty($date)) {
            return '';
        }

        if ( ! $format) {
            return $date;
        }

        $settings = settings();
        $date_format = $settings->default_date_format;

        return date($date_format, strtotime($date));
    }
}

if ( ! function_exists('make_reference_id')) {
    function make_reference_id($prefix, $number)
    {
        return $prefix.'-'.str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }
}

if ( ! function_exists('array_merge_numeric_values')) {
    function array_merge_numeric_values()
    {
        $arrays = func_get_args();
        $merged = [];

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if ( ! is_numeric($value)) {
                    continue;
                }

                if ( ! isset($merged[$key])) {
                    $merged[$key] = $value;
                } else {
                    $merged[$key] += $value;
                }
            }
        }

        return $merged;
    }
}
