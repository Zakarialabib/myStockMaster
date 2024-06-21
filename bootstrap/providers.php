<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\GoogleDriveServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
];
