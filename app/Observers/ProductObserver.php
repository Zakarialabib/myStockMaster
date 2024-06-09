<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Models\Redirect;
use App\Enums\RedirectionStatus;

class ProductObserver
{
    /** Handle the Product "updating" event. */
    public function updating(Product $product): void
    {
        // Check if the product's slug has changed
        if ($product->isDirty('slug')) {
            $oldSlug = $product->getOriginal('slug');
            $newSlug = $product->getAttribute('slug');

            // Create a redirection entry for the old slug
            Redirect::create([
                'old_url'          => $oldSlug,
                'new_url'          => $newSlug,
                'http_status_code' => RedirectionStatus::MOVED_PERMANENTLY,
            ]);

            // Update the product's URL to use the new slug
            $product->setAttribute('slug', $newSlug);
        }
    }
}
