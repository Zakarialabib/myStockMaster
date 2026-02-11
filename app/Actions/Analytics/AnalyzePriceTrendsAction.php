<?php

declare(strict_types=1);

namespace App\Actions\Analytics;

use App\Models\PriceHistory;
use App\Models\Product;
use Illuminate\Support\Collection;
use InvalidArgumentException;

final class AnalyzePriceTrendsAction
{
    /**
     * Analyze price trends for a product.
     *
     * @param  Product  $model  The model to analyze
     * @param  int  $days  Number of days to analyze (default: 30)
     * @param  bool  $useCache  Whether to use cached results
     * @return array Price trend analysis
     *
     * @throws InvalidArgumentException
     */
    public function __invoke($model, int $days = 30, bool $useCache = true): array
    {
        $this->validateModel($model);
        $this->validateDays($days);

        $cacheKey = $this->getCacheKey($model, $days);

        if ($useCache && cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $analysis = $this->performAnalysis($model, $days);

        if ($useCache) {
            cache()->put($cacheKey, $analysis, now()->addHours(6));
        }

        return $analysis;
    }

    /**
     * Get price history for a model.
     *
     * @param  Product  $model  The model to get history for
     * @return Collection Price history collection
     */
    public function getPriceHistory($model): Collection
    {
        $this->validateModel($model);

        return $model->priceHistory()
            ->orderByDesc('effective_date')
            ->get()
            ->map(fn (PriceHistory $priceHistory): array => [
                'price'          => $priceHistory->price,
                'cost'           => $priceHistory->cost,
                'previous_price' => null, // PriceHistory model doesn't have this field
                'previous_cost'  => null, // PriceHistory model doesn't have this field
                'reason'         => null, // PriceHistory model doesn't have this field
                'entry_date'     => $priceHistory->effective_date,
                'metadata'       => null, // PriceHistory model doesn't have this field
                'profit_margin'  => $priceHistory->price > 0 ? (($priceHistory->price - $priceHistory->cost) / $priceHistory->price) * 100 : 0,
            ]);
    }

    /**
     * Compare price trends between multiple models.
     *
     * @param  array  $models  Array of Product  models
     * @param  int  $days  Number of days to analyze
     * @return array Comparative analysis
     */
    public function compareModels(array $models, int $days = 30): array
    {
        $this->validateDays($days);
        $comparisons = [];
        $overallStats = [
            'most_volatile'    => null,
            'most_stable'      => null,
            'highest_increase' => null,
            'highest_decrease' => null,
        ];

        $volatilities = [];
        $priceChanges = [];

        foreach ($models as $model) {
            $this->validateModel($model);
            $analysis = $this->performAnalysis($model, $days);

            $modelData = [
                'model_id'   => $model->id,
                'model_name' => $model->name,
                'model_type' => class_basename($model),
                'analysis'   => $analysis,
            ];

            $comparisons[] = $modelData;
            $volatilities[$model->id] = $analysis['volatility'];
            $priceChanges[$model->id] = $analysis['price_change_percentage'];
        }

        // Find extremes
        if ( ! empty($volatilities)) {
            $mostVolatileId = array_keys($volatilities, max($volatilities))[0];
            $mostStableId = array_keys($volatilities, min($volatilities))[0];
            $highestIncreaseId = array_keys($priceChanges, max($priceChanges))[0];
            $highestDecreaseId = array_keys($priceChanges, min($priceChanges))[0];

            $overallStats = [
                'most_volatile'        => $this->findModelInComparisons($comparisons, $mostVolatileId),
                'most_stable'          => $this->findModelInComparisons($comparisons, $mostStableId),
                'highest_increase'     => $this->findModelInComparisons($comparisons, $highestIncreaseId),
                'highest_decrease'     => $this->findModelInComparisons($comparisons, $highestDecreaseId),
                'average_volatility'   => array_sum($volatilities) / count($volatilities),
                'average_price_change' => array_sum($priceChanges) / count($priceChanges),
            ];
        }

        return [
            'comparisons'     => $comparisons,
            'overall_stats'   => $overallStats,
            'analysis_period' => $days,
            'analyzed_at'     => now(),
        ];
    }

    private function performAnalysis($model, int $days): array
    {
        $prices = $model->priceHistory()
            ->where('effective_date', '>=', now()->subDays($days))
            ->orderBy('effective_date')
            ->get();

        if ($prices->isEmpty()) {
            return [
                'trend_direction'         => 'stable',
                'volatility'              => 0,
                'average_price'           => $model->price,
                'average_cost'            => $model->cost ?? 0,
                'price_change'            => 0,
                'price_change_percentage' => 0,
                'cost_change'             => 0,
                'cost_change_percentage'  => 0,
                'profit_margin_change'    => 0,
                'data_points'             => 0,
                'analysis_period'         => $days,
            ];
        }

        $priceValues = $prices->pluck('price')->toArray();
        $costValues = $prices->pluck('cost')->toArray();
        $firstPrice = $prices->first()->price;
        $lastPrice = $prices->last()->price;
        $firstCost = $prices->first()->cost ?? 0;
        $lastCost = $prices->last()->cost ?? 0;

        $averagePrice = array_sum($priceValues) / count($priceValues);
        $averageCost = array_sum($costValues) / count($costValues);

        // Calculate volatility
        $volatility = $this->calculateVolatility($priceValues);

        // Calculate trend direction
        $trendDirection = $this->calculateTrendDirection($firstPrice, $lastPrice, $volatility);

        // Calculate changes
        $priceChange = $lastPrice - $firstPrice;
        $priceChangePercentage = $firstPrice > 0 ? ($priceChange / $firstPrice) * 100 : 0;
        $costChange = $lastCost - $firstCost;
        $costChangePercentage = $firstCost > 0 ? ($costChange / $firstCost) * 100 : 0;

        // Calculate profit margin changes
        $firstMargin = $firstPrice > 0 ? (($firstPrice - $firstCost) / $firstPrice) * 100 : 0;
        $lastMargin = $lastPrice > 0 ? (($lastPrice - $lastCost) / $lastPrice) * 100 : 0;
        $profitMarginChange = $lastMargin - $firstMargin;

        return [
            'trend_direction'         => $trendDirection,
            'volatility'              => $volatility,
            'average_price'           => $averagePrice,
            'average_cost'            => $averageCost,
            'price_change'            => $priceChange,
            'price_change_percentage' => $priceChangePercentage,
            'cost_change'             => $costChange,
            'cost_change_percentage'  => $costChangePercentage,
            'profit_margin_change'    => $profitMarginChange,
            'first_price'             => $firstPrice,
            'last_price'              => $lastPrice,
            'first_cost'              => $firstCost,
            'last_cost'               => $lastCost,
            'data_points'             => $prices->count(),
            'analysis_period'         => $days,
        ];
    }

    private function calculateVolatility(array $prices): float
    {
        if (count($prices) < 2) {
            return 0;
        }

        $mean = array_sum($prices) / count($prices);
        $variance = array_reduce(
            $prices,
            fn ($carry, $price) => $carry + ($price - $mean) ** 2,
            0,
        ) / (count($prices) - 1);

        return sqrt($variance);
    }

    private function calculateTrendDirection(float $firstPrice, float $lastPrice, float $volatility): string
    {
        $priceChange = $lastPrice - $firstPrice;
        $significantChange = abs($priceChange) > $volatility;

        if ( ! $significantChange) {
            return 'stable';
        }

        return $priceChange > 0 ? 'increasing' : 'decreasing';
    }

    private function validateModel($model): void
    {
        if ( ! ($model instanceof Product)) {
            throw new InvalidArgumentException('Model must be an instance of Product');
        }
    }

    private function validateDays(int $days): void
    {
        if ($days <= 0) {
            throw new InvalidArgumentException('Days must be a positive integer');
        }

        if ($days > 365) {
            throw new InvalidArgumentException('Analysis period cannot exceed 365 days');
        }
    }

    private function getCacheKey($model, int $days): string
    {
        return sprintf(
            'price_trends_%s_%d_%d',
            class_basename($model),
            $model->id,
            $days,
        );
    }

    private function findModelInComparisons(array $comparisons, int $modelId): ?array
    {
        foreach ($comparisons as $comparison) {
            if ($comparison['model_id'] === $modelId) {
                return $comparison;
            }
        }

        return null;
    }
}

/*
|--------------------------------------------------------------------------
| USAGE EXAMPLES
|--------------------------------------------------------------------------
|
| How to call from a Livewire component:
| $analyzePriceTrends = resolve(AnalyzePriceTrendsAction::class);
| $analysis = $analyzePriceTrends($product, 30, true);
|
| Get price history:
| $history = $analyzePriceTrends->getPriceHistory($product);
|
| Compare multiple products:
| $comparison = $analyzePriceTrends->compareModels([$product1, $product2], 30);
|
| How to test:
| $product = Product::factory()->create();
| $product->prices()->create([
|     'price' => 10.00,
|     'cost' => 6.00,
|     'is_current' => true,
|     'entry_date' => now()->subDays(10)
| ]);
| $analyzePriceTrends = resolve(AnalyzePriceTrendsAction::class);
| $analysis = $analyzePriceTrends($product, 30, false);
| expect($analysis)->toHaveKey('trend_direction');
| expect($analysis)->toHaveKey('volatility');
*/
