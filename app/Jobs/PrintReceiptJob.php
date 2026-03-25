<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrintReceiptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public string|int $saleId) {}

    public function handle(): void
    {
        $sale = Sale::with(['customer', 'saleDetails.product'])->find($this->saleId);

        if (! $sale) {
            return;
        }

        // Generate receipt PDF or send to local printer
        // This is a placeholder for NativePHP/ESC-POS or Cloud Print integration
        // Example: LocalPrinter::print(Receipt::make($sale));
    }
}
