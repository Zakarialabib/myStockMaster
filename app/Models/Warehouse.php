<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'mobile', 'country', 'city', 'email', 'zip',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany('App\Models\User');
    }

}
