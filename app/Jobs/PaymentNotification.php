<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Sale;
use App\Models\User;
use App\Notifications\PaymentDue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PaymentNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $sale;

    /**
     * Create a new job instance.
     *
     * @param Sale $sale
     */
    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( ! $this->sale->due_amount || ! $this->sale->payment_date) {
            // $payment_date = Carbon::parse($this->sale->date)->addDays(15);

            // if (now()->gt($payment_date)) {
            $user = User::find(1);

            $user->notify(new PaymentDue($this->sale));
            // }
        }
    }
}
