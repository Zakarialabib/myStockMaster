<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Printer
 *
 * @property int $id
 * @property string $name
 * @property string $connection_type
 * @property string $capability_profile
 * @property string|null $char_per_line
 * @property string|null $ip_address
 * @property string|null $port
 * @property string|null $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Printer advancedFilter($data)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCapabilityProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCharPerLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereConnectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Printer extends Model
{
    use HasAdvancedFilter;

   /** 
     * @var string[] 
    */
    public $orderable = [
        'id',
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

   /** 
     * @var string[] 
    */
    public $filterable = [
        'id',
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

    public $fillable = [
        'name',
        'connection_type',
        'capability_profile',
        'char_per_line',
        'ip_address',
        'port',
        'path',
    ];

    public static function capability_profiles()
    {
        $profiles = [
            'default'  => 'Default',
            'simple'   => 'Simple',
            'SP2000'   => 'Star Branded',
            'TEP-200M' => 'Espon Tep',
            'P822D'    => 'P822D',
        ];

        return $profiles;
    }

    public static function capability_profile_srt($profile)
    {
        $profiles = Printer::capability_profiles();

        return isset($profiles[$profile]) ? $profiles[$profile] : '';
    }

    public static function connection_types()
    {
        $types = [
            'network' => 'Network',
            'windows' => 'Windows',
            'linux'   => 'Linux',
        ];

        return $types;
    }

    public static function connection_type_str($type)
    {
        $types = Printer::connection_types();

        return isset($types[$type]) ? $types[$type] : '';
    }
}
