<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'roles';

    public $orderable = [
        'id',
        'title',
    ];

    public $filterable = [
        'id',
        'title',
        'permissions.name',
    ];

    protected $fillable = [
        'title',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    
    
}
