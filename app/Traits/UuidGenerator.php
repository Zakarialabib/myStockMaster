<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait UuidGenerator
{
    public static function bootUuidGenerator(): void
    {
        static::creating(function (Model $model) {
            if (Schema::hasColumn($model->getTable(), 'uuid')) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
