<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Storage Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default storage driver that will be used
    | to store cart data. Supported drivers: "session", "database", "cache"
    |
    */

    'storage' => env('CART_STORAGE', 'session'),

    /*
    |--------------------------------------------------------------------------
    | Default tax rate
    |--------------------------------------------------------------------------
    |
    | This default tax rate will be used when calculating taxes for cart items.
    |
    */

    'tax' => 0,

    /*
    |--------------------------------------------------------------------------
    | Cart database settings
    |--------------------------------------------------------------------------
    |
    | Here you can set the connection that the cart should use when
    | storing and restoring cart data in the database.
    |
    */

    'database' => [

        'connection' => null,

        'carts_table' => 'carts',

        'cart_items_table' => 'cart_items',

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache settings
    |--------------------------------------------------------------------------
    |
    | Configuration for cache-based cart storage.
    |
    */

    'cache' => [

        'prefix' => 'cart_',

        'ttl' => 60 * 24 * 7, // 7 days in minutes

    ],

    /*
    |--------------------------------------------------------------------------
    | Destroy the cart on user logout
    |--------------------------------------------------------------------------
    |
    | When this option is set to 'true' the cart will automatically
    | destroy all cart instances when the user logs out.
    |
    */

    'destroy_on_logout' => true,

    /*
    |--------------------------------------------------------------------------
    | Default number format
    |--------------------------------------------------------------------------
    |
    | This defaults will be used for the formatted numbers if you don't
    | set them in the method call.
    |
    */

    'format' => [

        'decimals' => 2,

        'decimal_point' => '.',

        'thousand_separator' => '',

    ],

];
