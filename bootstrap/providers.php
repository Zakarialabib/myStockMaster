<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\CartServiceProvider::class,
    App\Providers\EnvironmentServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Native\Providers\DesktopServiceProvider::class,
    App\Native\Providers\NativeAppServiceProvider::class,
];
