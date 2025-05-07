<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CapabilityProfile;
use App\Enums\ConnectionType;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Printer extends Model
{
    use HasAdvancedFilter;
    use HasFactory;

    protected $fillable = [
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

    protected $casts = [
        'connection_type' => ConnectionType::class,
        'capability_profile' => CapabilityProfile::class,
    ];

    public $orderable = [
        'id',
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
    ];

    public $filterable = [
        'id',
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
    ];

    public static function connectionTypes(): array
    {
        return array_column(ConnectionType::cases(), 'value', 'name');
    }

    public static function capabilityProfiles(): array
    {
        return array_column(CapabilityProfile::cases(), 'value', 'name');
    }

    public function getConnectionTypeStrAttribute(): string
    {
        return $this->connection_type->name;
    }

    public function getCapabilityProfileStrAttribute(): string
    {
        return $this->capability_profile->name;
    }
}