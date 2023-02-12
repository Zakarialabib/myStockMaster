<?php

namespace App\Traits;

trait GetModelByUuid
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}