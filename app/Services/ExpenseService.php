<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function create(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['document']) && ! is_string($data['document'])) {
                $data['document'] = $data['document']->store('expenses', 'public');
            }

            $expense = Expense::query()->create($data);

            if (! isset($data['user_id']) || blank($data['user_id'])) {
                $expense->user()->associate(auth()->user());
            }

            return $expense;
        });
    }

    public function update(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data): \App\Models\Expense {
            if (isset($data['document']) && ! is_string($data['document'])) {
                $data['document'] = $data['document']->store('expenses', 'public');
            } else {
                unset($data['document']);
            }

            $expense->update($data);

            return $expense;
        });
    }
}
