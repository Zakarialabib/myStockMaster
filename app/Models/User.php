<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable 
{
    use HasAdvancedFilter, HasApiTokens, 
    Notifiable, HasRoles;

    public $orderable = [
        'id','name','email', 'password' , 'avatar',
        'phone' ,'role_id','statut' ,'is_all_warehouses',
        'created_at','updated_at',
    ];

    public $filterable = [
        'id','name','email', 'password' , 'avatar',
        'phone' ,'role_id','statut' ,'is_all_warehouses',
        'created_at','updated_at','wallet_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id','name','email', 'password' , 'avatar',
        'phone' ,'role_id','statut' ,'is_all_warehouses',
        'created_at','updated_at','wallet_id'
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
     * The attributes that should be mutated to dates.
     *
     * @var array<string, string>
     */

    public function scopeIsActive(Builder $builder) {
        return $builder->where('is_active', 1);
    }

    public function assignedWarehouses()
    {
        return $this->belongsToMany('App\Models\Warehouse');
    }

    # User hasRole method
    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }
        
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
    
    
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('name', $permission);
    }

    
    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet');
    }


}
