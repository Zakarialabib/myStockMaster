<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use  Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|array<SpatiePermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @mixin \Eloquent
 */
class Permission extends SpatiePermission
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'label', 'guard_name', 'name', 'description'];

    /** @return BelongsToMany<Role> */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

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
