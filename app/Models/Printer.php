<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasAdvancedFilter;

    public const ATTRIBUTES = [
        'id',
        'name',
        'connection_type',

    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    protected $fillable = [
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

    public static function capabilityProfiles()
    {
        return [
            'default'  => 'Default',
            'simple'   => 'Simple',
            'SP2000'   => 'Star Branded',
            'TEP-200M' => 'Espon Tep',
            'P822D'    => 'P822D',
        ];
    }

    public static function capabilityProfileSrt($profile)
    {
        $profiles = Printer::capabilityProfiles();

        return $profiles[$profile] ?? '';
    }

    public static function connectionTypes()
    {
        return [
            'network' => 'Network',
            'windows' => 'Windows',
            'linux'   => 'Linux',
        ];
    }

    public static function connectionTypeStr($type)
    {
        $types = Printer::connectionTypes();

        return $types[$type] ?? '';
    }
}
