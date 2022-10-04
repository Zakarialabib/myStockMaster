<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWarehouse extends Model
{
    protected $table ="user_warehouse";

   protected $fillable = [
    'user_id', 'warehouse_id',
];

protected $casts = [
    'user_id' => 'integer',
    'warehouse_id' => 'integer',
];

    public function assignedWarehouses()
    {
        return $this->hasMany('App\Models\Warehouse', 'id', 'warehouse_id');
    }
}
