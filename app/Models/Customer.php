<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Support\HasAdvancedFilter;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * @property string                          $id
 * @property string                          $name
 * @property string|null                     $email
 * @property string|null                     $phone
 * @property string|null                     $city
 * @property string|null                     $country
 * @property string|null                     $address
 * @property string|null                     $tax_number
 * @property string|null                     $user_id
 * @property Status                          $status
 * @property string|null                     $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null                        $customer_group_id
 * @property-read CustomerGroup|null $customerGroup
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read int|null $sales_count
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer advancedFilter($data)
 * @method static \Database\Factories\CustomerFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer searchByName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Customer extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use HasUuid;
    use Notifiable;

    protected const ATTRIBUTES = [
        'id',
        'name',
        'email',
        'phone',
        'customer_group_id',
        'user_id',
        'city',
        'country',
        'created_at',
        'updated_at',
    ];

    public array $orderable = self::ATTRIBUTES;

    public array $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'phone', 'email', 'city', 'country',
        'address', 'tax_number', 'password', 'status',
        'customer_group_id', 'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }

    private function customerSum(mixed $column, mixed $model)
    {
        return $model::where('customer_id', $this->id)->sum($column);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => ($this->first_name ?? '') . ' ' . ($this->last_name ?? ''),
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value): ?string => preg_replace('/[^0-9]/', '', (string) $value),
        );
    }

    protected function scopeActive(mixed $query)
    {
        return $query->where('active', true);
    }

    protected function scopeSearchByName(mixed $query, mixed $name)
    {
        return $query->when(filled($name), fn($query) => $query->where('name', 'like', '%' . $name . '%'));
    }

    public function getTotalSalesAmount(): float
    {
        return $this->sales()->sum('total_amount');
    }
}
