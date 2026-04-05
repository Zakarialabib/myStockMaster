<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string                          $id
 * @property string                          $type
 * @property string                          $notifiable_type
 * @property int                             $notifiable_id
 * @property array<array-key, mixed>         $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|Eloquent $notifiable
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    protected $keyType = 'string';

    /** Get the notifiable entity that the notification belongs to. */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Determine if the notification has been read. */
    public function read(): bool
    {
        return $this->read_at !== null;
    }

    /** Determine if the notification has not been read. */
    public function unread(): bool
    {
        return $this->read_at === null;
    }

    /** Mark the notification as read. */
    public function markAsRead(): bool
    {
        if (is_null($this->read_at)) {
            return $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }

        return false;
    }

    /** Mark the notification as unread. */
    public function markAsUnread(): bool
    {
        if (! is_null($this->read_at)) {
            return $this->forceFill(['read_at' => null])->save();
        }

        return false;
    }

    /** Scope a query to only include unread notifications. */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /** Scope a query to only include read notifications. */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
