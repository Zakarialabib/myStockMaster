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
    function format_percentage(int|float $value, int $decimals = 2)
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
        if (blank($date)) {
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

        return date($date_format, strtotime((string) $date));
    }
}

if (! function_exists('make_reference_id')) {
    /**
     * Generate a sequential reference ID for a given model.
     * Extracts the trailing digits from the last created record safely using regex.
     *
     * @param string $prefix
     * @param string $modelClass
     * @return string
     */
    function make_reference_id(string $prefix, string $modelClass): string
    {
        $latest = $modelClass::query()->latest('created_at')->first();

        $number = 1;
        if ($latest && $latest->reference) {
            // Extract trailing digits safely (e.g., SL-1000 -> 1000)
            if (preg_match('/(\d+)$/', (string) $latest->reference, $matches)) {
                $number = (int)$matches[1] + 1;
            }
        }

        return $prefix . '-' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('array_merge_numeric_values')) {
    /**
     * @return float[]|int[]|numeric-string[]
     */
    function array_merge_numeric_values(...$arrays): array
    {
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
    function db_date_format($column, $format = null): string
    {
        if ($format === null) {
            $phpFormat = settings('default_date_format', 'Y-m-d');
            // Map PHP format to SQL format
            $format = str_replace(
                ['Y', 'm', 'd', 'H', 'i', 's'],
                ['%Y', '%m', '%d', '%H', '%i', '%s'],
                $phpFormat
            );
        }

        $isSqlite = Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite';

        if ($isSqlite) {
            // Simple mapping for common formats
            $sqliteFormat = str_replace(
                ['%Y', '%m', '%d', '%H', '%i', '%s', '%W'],
                ['%Y', '%m', '%d', '%H', '%M', '%S', '%w'],
                $format
            );

            return sprintf("strftime('%s', %s)", $sqliteFormat, $column);
        }

        return sprintf("DATE_FORMAT(%s, '%s')", $column, $format);
    }
}
