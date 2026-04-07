<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                             $id
 * @property string                          $name
 * @property string                          $connection_type
 * @property string                          $capability_profile
 * @property string|null                     $char_per_line
 * @property string|null                     $ip_address
 * @property string|null                     $port
 * @property string|null                     $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereCapabilityProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereCharPerLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereConnectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Printer extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'name',
        'connection_type',

    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

    public static function capabilityProfiles(): array
    {
        return [
            'default' => 'Default',
            'simple' => 'Simple',
            'SP2000' => 'Star Branded',
            'TEP-200M' => 'Espon Tep',
            'P822D' => 'P822D',
        ];
    }

    public static function capabilityProfileSrt(mixed $profile)
    {
        $profiles = Printer::capabilityProfiles();

        return $profiles[$profile] ?? '';
    }

    public static function connectionTypes(): array
    {
        return [
            'network' => 'Network',
            'windows' => 'Windows',
            'linux' => 'Linux',
        ];
    }

    public static function connectionTypeStr(mixed $type)
    {
        $types = Printer::connectionTypes();

        return $types[$type] ?? '';
    }
}
