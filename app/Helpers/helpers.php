<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

if ( ! function_exists('settings')) {
    function settings()
    {
        return cache()->rememberForever('settings', function () {
            if (Schema::hasTable('settings')) {
                return App\Models\Setting::with('currency')->first();
            }

            return null;
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
        $currencyCode = $settings->currency->code ?? 'USD'; // Assuming currency code is stored in settings, defaulting to USD if not available
        $locale = $settings->currency->locale ?? 'en_US';  // Assuming locale is stored or default to 'en_US'

        // $value change to type int|float, string given,
        return Number::currency(
            intval($value),
            $currencyCode,
            $locale
        );
    }
}

// formatPercentage --> Number::percentage(25) // 25%

if ( ! function_exists('format_percentage')) {
    function format_percentage($value, $decimals = 2)
    {
        return Number::percentage($value, $decimals);
    }
}

if ( ! function_exists('checkInvoiceControl')) {
    function checkInvoiceControl($name)
    {
        $invoiceControl = settings()->invoice_control;

        foreach ($invoiceControl as $control) {
            if ($control['name'] === $name) {
                return $control['status'];
            }
        }
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
