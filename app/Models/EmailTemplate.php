<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasAdvancedFilter;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'message',
        'default',
        'placeholders',
        'type',
        'subject',
        'status',
        'created_at',
        'updated_at',
    ];

    public $orderable = self::ATTRIBUTES;

    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'message',
        'default',
        'placeholders',
        'type',
        'subject',
        'status',
    ];

    /**
     * Scope a query to only include default email templates.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('default', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
