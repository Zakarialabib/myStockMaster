<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application ID
    |--------------------------------------------------------------------------
    |
    | This is the unique identifier for your application. It should be in
    | reverse domain notation format (e.g., com.company.app).
    |
    */
    'app_id' => env('NATIVEPHP_APP_ID', 'com.mystockmaster.app'),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | The name of your application as it will appear to users.
    |
    */
    'app_name' => env('NATIVEPHP_APP_NAME', 'MyStockMaster'),

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | The version of your application.
    |
    */
    'app_version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Window Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the main application window properties.
    |
    */
    'window' => [
        'width' => 1200,
        'height' => 800,
        'min_width' => 800,
        'min_height' => 600,
        'resizable' => true,
        'fullscreen' => false,
        'show' => true,
        'frame' => true,
        'transparent' => false,
        'always_on_top' => false,
        'skip_taskbar' => false,
        'title_bar_style' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the application menu.
    |
    */
    'menu' => [
        'enabled' => true,
        'custom' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Updater Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic updates for your application.
    |
    */
    'updater' => [
        'enabled' => env('NATIVEPHP_UPDATER_ENABLED', true),
        'default' => env('NATIVEPHP_UPDATER_PROVIDER', 'github'),
        'providers' => [
            'github' => [
                'driver' => 'github',
                'repository' => env('NATIVEPHP_UPDATER_GITHUB_REPOSITORY'),
                'token' => env('NATIVEPHP_UPDATER_GITHUB_TOKEN'),
            ],
        ],
        'url' => env('NATIVEPHP_UPDATER_URL'),
        'check_interval' => 3600, // 1 hour
        'auto_download' => true,
        'auto_install' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for development mode.
    |
    */
    'development' => [
        'show_dev_tools' => env('APP_DEBUG', false),
        'reload_on_change' => env('APP_DEBUG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Build Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for building the application.
    |
    */
    'build' => [
        'icon' => 'resources/images/icon.png',
        'output_dir' => 'dist',
        'compression' => true,
    ],
];
