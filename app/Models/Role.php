<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'name',
        'guard_name',

    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
