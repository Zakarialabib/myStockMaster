<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property string|null $phone
 * @property int $role_id
 * @property int $status
 * @property int $is_all_warehouses
 * @property int|null $wallet_id
 * @property int|null $default_client_id
 * @property int|null $default_warehouse_id
 * @property string|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Warehouse[] $assignedWarehouses
 * @property-read int|null $assigned_warehouses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Wallet|null $wallet
 * @method static Builder|User advancedFilter($data)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static Builder|User isActive()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDefaultClientId($value)
 * @method static Builder|User whereDefaultWarehouseId($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsAllWarehouses($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereWalletId($value)
 * @mixin \Eloquent
 * @property string $uuid
 * @method static Builder|User whereUuid($value)
 */
class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasApiTokens;
    use HasAdvancedFilter;
    use HasFactory;
    use GetModelByUuid;
    use UuidGenerator;

    /** @var string[] */
    public $orderable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'status', 'is_all_warehouses',
        'created_at', 'updated_at',
    ];

    /** @var string[] */
    public $filterable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'status', 'is_all_warehouses',
        'created_at', 'updated_at', 'wallet_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid', 'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'status', 'is_all_warehouses',
        'created_at', 'updated_at', 'wallet_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return mixed
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->whereIsActive(true);
    }

    /** @return BelongsToMany<Warehouse> */
    public function assignedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class);
    }

    // User hasRole method
    public function hasRole($roles): bool
    {
        return (bool) ($this->roles()->whereName($roles)->first());
    }

    /**
     * @param mixed $permission
     * @return mixed
     */
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('name', $permission);
    }

    /** @return HasOne<Wallet> */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}
