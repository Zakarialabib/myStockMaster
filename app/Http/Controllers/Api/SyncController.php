<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function pull(Request $request): JsonResponse
    {
        $lastSyncedAt = $request->input('last_synced_at');

        $query = function ($model) use ($lastSyncedAt) {
            return $lastSyncedAt
                ? $model::where('updated_at', '>', $lastSyncedAt)->get()
                : $model::all();
        };

        return response()->json([
            'products' => $query(Product::class),
            'customers' => $query(Customer::class),
            // Add other models here
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function push(Request $request): JsonResponse
    {
        // TODO: Handle incoming changes from desktop
        return response()->json(['message' => 'Push received']);
    }
}
