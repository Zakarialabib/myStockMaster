<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;

if (! function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        $settings = cache()->rememberForever('settings', function () {
            if (Schema::hasTable('settings')) {
                return App\Models\Setting::with('currency')->first();
            }

            return null;
        });

        if ($key === null) {
            return $settings;
        }

        return $settings ? ($settings->{$key} ?? $default) : $default;
    }
}

if (! function_exists('format_currency')) {
    function format_currency($value, $format = true)
    {
        if (! $format) {
            return $value;
        }

        $settings = settings();
        $currencyCode = $settings?->currency?->code ?? 'USD'; // Assuming currency code is stored in settings, defaulting to USD if not available
        $locale = $settings?->currency?->locale ?? 'en_US';  // Assuming locale is stored or default to 'en_US'

        return Number::currency(
            floatval($value),
            $currencyCode,
            $locale
        );
    }
}

// formatPercentage --> Number::percentage(25) // 25%

if (! function_exists('format_percentage')) {
    function format_percentage($value, $decimals = 2)
    {
        return Number::percentage($value, $decimals);
    }
}

if (! function_exists('checkInvoiceControl')) {
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

if (! function_exists('flag_image_url')) {
    function flag_image_url(string $languageCode): string
    {
        $flagMap = [
            'en' => 'flags/en.png',
            'fr' => 'flags/fr.png',
            'es' => 'flags/es.png',
            'de' => 'flags/de.png',
            'it' => 'flags/it.png',
            'pt' => 'flags/pt.png',
            'ar' => 'flags/ar.png',
            'zh' => 'flags/zh.png',
            'ja' => 'flags/ja.png',
            'ko' => 'flags/ko.png',
            'ru' => 'flags/ru.png',
            'hi' => 'flags/hi.png',
        ];

        $flagPath = $flagMap[$languageCode] ?? 'flags/default.png';

        return asset('images/' . $flagPath);
    }
}

if (! function_exists('format_date')) {
    function format_date($date, $format = true)
    {
        if (empty($date)) {
            return '';
        }

        if (! $format) {
            return $date;
        }

        $settings = settings();
        $date_format = $settings->default_date_format;

        if ($date instanceof Illuminate\Support\Carbon) {
            return $date->format($date_format);
        }

        return date($date_format, strtotime($date));
    }
}

if (! function_exists('make_reference_id')) {
    function make_reference_id($prefix, $number)
    {
        return $prefix . '-' . str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('array_merge_numeric_values')) {
    function array_merge_numeric_values()
    {
        $arrays = func_get_args();
        $merged = [];

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (! is_numeric($value)) {
                    continue;
                }

                if (! isset($merged[$key])) {
                    $merged[$key] = $value;
                } else {
                    $merged[$key] += $value;
                }
            }
        }

        return $merged;
    }
}

if (! function_exists('db_date_format')) {
    /**
     * Get the database date format string based on the current driver.
     * Supports MySQL (DATE_FORMAT) and SQLite (strftime).
     */
    function db_date_format($column, $format)
    {
        $isSqlite = Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite';

        if ($isSqlite) {
            // Simple mapping for common formats
            $sqliteFormat = str_replace(
                ['%Y', '%m', '%d', '%H', '%i', '%s', '%W'],
                ['%Y', '%m', '%d', '%H', '%M', '%S', '%w'],
                $format
            );

            return "strftime('$sqliteFormat', $column)";
        }

        return "DATE_FORMAT($column, '$format')";
    }
}
