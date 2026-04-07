<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialSnapshot extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'snapshot_date',
        'period_type',
        'total_revenue',
        'gross_revenue',
        'net_revenue',
        'total_orders',
        'average_order_value',
        'total_expenses',
        'cost_of_goods_sold',
        'operating_expenses',
        'gross_profit',
        'net_profit',
        'profit_margin',
        'gross_margin',
        'break_even_point_units',
        'break_even_point_revenue',
        'days_to_break_even',
        'revenue_growth_rate',
        'profit_growth_rate',
        'return_on_investment',
        'category_breakdown',
        'payment_method_breakdown',
        'top_products',
        'metadata',
        'calculated_at',
        'calculated_by',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'category_breakdown' => 'array',
        'payment_method_breakdown' => 'array',
        'top_products' => 'array',
        'metadata' => 'array',
        'calculated_at' => 'datetime',
    ];
}
