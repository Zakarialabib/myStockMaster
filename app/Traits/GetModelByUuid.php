<?php

declare(strict_types=1);

namespace App\Traits;

trait GetModelByUuid
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
