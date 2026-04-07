<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                             $id
 * @property string                          $name
 * @property string|null                     $description
 * @property string|null                     $message
 * @property string|null                     $default
 * @property string|null                     $placeholders
 * @property string|null                     $type
 * @property string|null                     $subject
 * @property string                          $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static>|EmailTemplate active()
 * @method static Builder<static>|EmailTemplate advancedFilter($data)
 * @method static Builder<static>|EmailTemplate default()
 * @method static Builder<static>|EmailTemplate newModelQuery()
 * @method static Builder<static>|EmailTemplate newQuery()
 * @method static Builder<static>|EmailTemplate query()
 * @method static Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static Builder<static>|EmailTemplate whereDefault($value)
 * @method static Builder<static>|EmailTemplate whereDescription($value)
 * @method static Builder<static>|EmailTemplate whereId($value)
 * @method static Builder<static>|EmailTemplate whereMessage($value)
 * @method static Builder<static>|EmailTemplate whereName($value)
 * @method static Builder<static>|EmailTemplate wherePlaceholders($value)
 * @method static Builder<static>|EmailTemplate whereStatus($value)
 * @method static Builder<static>|EmailTemplate whereSubject($value)
 * @method static Builder<static>|EmailTemplate whereType($value)
 * @method static Builder<static>|EmailTemplate whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
