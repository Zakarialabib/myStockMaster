<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CashRegister;
use Illuminate\Support\Facades\DB;

class CashRegisterService
{
    public function create(array $data): CashRegister
    {
        return DB::transaction(function () use ($data) {
            return CashRegister::create([
                'cash_in_hand' => $data['cash_in_hand'],
                'warehouse_id' => $data['warehouse_id'],
                'user_id' => auth()->user()->id,
                'status' => true,
            ]);
        });
    }
}
