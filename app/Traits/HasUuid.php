<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(static function (Model $model): void {
            if (Schema::hasColumn($model->getTable(), 'id')) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
