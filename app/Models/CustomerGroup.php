<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerGroup extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    public $orderable = [
        'id', 'name', 'percentage', 'status',
    ];

    public $filterable = [
        'id', 'name', 'percentage', 'status',
    ];

    protected $fillable = [
        'name', 'percentage', 'status',
    ];
}
