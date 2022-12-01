<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    use HasAdvancedFilter;

    public $orderable = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    public $filterable = [
        'id',
        'name',
        'city',
        'phone',
        'email',
        'country',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name', 'phone', 'country', 'city', 'email',
    ];

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
