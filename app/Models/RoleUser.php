<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasAdvancedFilter;

    protected $table = 'role_user';

    public $orderable = [
        'id',
        'user_id',
        'role_id',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'user_id',
        'role_id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
