<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
            'data'    => 'array',
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
        if ( ! is_null($this->read_at)) {
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
