<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasApiTokens;
    use HasAdvancedFilter;

    public $orderable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'statut', 'is_all_warehouses',
        'created_at', 'updated_at',
    ];

    public $filterable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'statut', 'is_all_warehouses',
        'created_at', 'updated_at', 'wallet_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'avatar',
        'phone', 'role_id', 'statut', 'is_all_warehouses',
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

    public function scopeIsActive(Builder $builder)
    {
        return $builder->whereIsActive(true);
    }

    public function assignedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Warehouse');
    }

    // User hasRole method
    public function hasRole($roles)
    {
        if ($this->roles()->whereName($roles)->first()) {
            return true;
        }

        return false;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('name', $permission);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne('App\Models\Wallet');
    }
}
