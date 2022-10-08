<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasAdvancedFilter;

class Warehouse extends Model
{
    use HasAdvancedFilter;
    
    public $orderable = [
        'id',
        'name',
        'city',
        'mobile',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'city',
        'mobile',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name', 'mobile', 'country', 'city', 'email',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany('App\Models\User');
    }

}
