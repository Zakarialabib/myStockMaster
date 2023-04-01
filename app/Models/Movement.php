<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'quantity',
        'price',
        'date',
        'movable_id',
        'movable_type',
        'user_id',
    ];

    public function movable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
