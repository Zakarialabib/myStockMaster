<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use  Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasAdvancedFilter;
    public $orderable = [
        'id',
        'name',
        'guard_name',
    ];

    public $filterable = [
        'id',
        'name',
        'guard_name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['guard_name', 'name', 'description'];

    /**
     * Determine if the permission belongs to the role.
     *
     * @param  mixed  $role
     *
     * @return bool
     */
    public function inRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return (bool) $role->intersect($this->roles)->count();
    }
}
