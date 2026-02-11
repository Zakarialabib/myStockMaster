<?php

declare(strict_types=1);

namespace App\Helpers;

class Helpers
{
    /**
     * Get the flag image URL for a given language code
     *
     * @param string $languageCode
     * @return string
     */
    public static function flagImageUrl(string $languageCode): string
    {
        // Map language codes to flag image paths
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

        return asset('images/'.$flagPath);
    }

    /**
     * Get settings value (wrapper for the global settings helper)
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public static function settings($key = null, $default = null)
    {
        return settings($key, $default);
    }
}
