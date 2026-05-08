<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Skip Installation
    |--------------------------------------------------------------------------
    |
    | This option allows you to skip the installation process entirely.
    | When set to true, the installation middleware will be bypassed.
    | This is useful for development environments or when deploying
    | to production with pre-configured settings.
    |
    */

    'skip' => filter_var(env('SKIP_INSTALLATION', false), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Installation Completed
    |--------------------------------------------------------------------------
    |
    | This setting is automatically managed by the installation process.
    | It indicates whether the installation has been completed successfully.
    | Do not modify this manually unless you know what you're doing.
    |
    */

    'completed' => filter_var(env('INSTALLATION_COMPLETED', false), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Force Installation
    |--------------------------------------------------------------------------
    |
    | When set to true, this will force the installation process to run
    | even if it has been completed before. This is useful for resetting
    | the installation state during development.
    |
    */

    'force' => filter_var(env('FORCE_INSTALLATION', false), FILTER_VALIDATE_BOOLEAN),

];
