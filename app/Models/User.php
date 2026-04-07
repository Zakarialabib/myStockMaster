<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string                          $id
 * @property string                          $name
 * @property string                          $email
 * @property string                          $password
 * @property string|null                     $avatar
 * @property string|null                     $phone
 * @property string|null                     $city
 * @property string|null                     $address
 * @property string|null                     $country
 * @property int|null                        $role_id
 * @property Status                          $status
 * @property bool                            $is_all_warehouses
 * @property int|null                        $default_client_id
 * @property int|null                        $default_warehouse_id
 * @property int|null                        $provider_id
 * @property string|null                     $deleted_at
 * @property string|null                     $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warehouse> $assignedWarehouses
 * @property-read int|null $assigned_warehouses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 *
 * @method static Builder<static>|User            advancedFilter($data)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User            isActive()
 * @method static Builder<static>|User            newModelQuery()
 * @method static Builder<static>|User            newQuery()
 * @method static Builder<static>|User            permission($permissions, $without = false)
 * @method static Builder<static>|User            query()
 * @method static Builder<static>|User            role($roles, $guard = null, $without = false)
 * @method static Builder<static>|User            whereAddress($value)
 * @method static Builder<static>|User            whereAvatar($value)
 * @method static Builder<static>|User            whereCity($value)
 * @method static Builder<static>|User            whereCountry($value)
 * @method static Builder<static>|User            whereCreatedAt($value)
 * @method static Builder<static>|User            whereDefaultClientId($value)
 * @method static Builder<static>|User            whereDefaultWarehouseId($value)
 * @method static Builder<static>|User            whereDeletedAt($value)
 * @method static Builder<static>|User            whereEmail($value)
 * @method static Builder<static>|User            whereId($value)
 * @method static Builder<static>|User            whereIsAllWarehouses($value)
 * @method static Builder<static>|User            whereName($value)
 * @method static Builder<static>|User            wherePassword($value)
 * @method static Builder<static>|User            wherePhone($value)
 * @method static Builder<static>|User            whereProviderId($value)
 * @method static Builder<static>|User            whereRememberToken($value)
 * @method static Builder<static>|User            whereRoleId($value)
 * @method static Builder<static>|User            whereStatus($value)
 * @method static Builder<static>|User            whereUpdatedAt($value)
 * @method static Builder<static>|User            withoutPermission($permissions)
 * @method static Builder<static>|User            withoutRole($roles, $guard = null)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class User extends Authenticatable
{
    use HasAdvancedFilter;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasUuid;
    use Notifiable;

    protected const ATTRIBUTES = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'status', 'is_all_warehouses',
        'created_at', 'updated_at', 'provider_id', 'provider_name',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'status', 'is_all_warehouses',
        'default_client_id', 'default_warehouse_id',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'is_all_warehouses' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /** @return mixed */
    protected function scopeIsActive(Builder $builder)
    {
        return $builder->whereIsActive(true);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Warehouse, $this, \Illuminate\Database\Eloquent\Relations\Pivot> */
    public function assignedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Warehouse, $this, \Illuminate\Database\Eloquent\Relations\Pivot> */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'user_warehouse', 'user_id', 'warehouse_id')
            ->withPivot('user_id', 'warehouse_id');
    }

    /**
     * Get the warehouses associated with the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Warehouse>
     */
    public function getWarehouses()
    {
        return $this->warehouses()->get();
    }
}
