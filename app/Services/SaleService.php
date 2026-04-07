<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Jobs\SyncInventoryJob;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function create(array $saleData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount, bool $isDraft = false): Sale
    {
        return DB::transaction(function () use ($saleData, $cartItems, $cartTax, $cartDiscount, $isDraft): Sale {
            $dueAmount = $saleData['total_amount'] - $saleData['paid_amount'];

            if ($dueAmount === $saleData['total_amount']) {
                $paymentStatus = PaymentStatus::PENDING;
                $status = SaleStatus::PENDING;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
                $status = SaleStatus::PENDING;
            } else {
                $paymentStatus = PaymentStatus::PAID;
                $status = SaleStatus::COMPLETED;
            }

            if ($isDraft) {
                $status = SaleStatus::PENDING;
                $paymentStatus = PaymentStatus::PENDING;
            }

            $sale = Sale::query()->create([
                'date' => $saleData['date'],
                'customer_id' => $saleData['customer_id'],
                'warehouse_id' => $saleData['warehouse_id'],
                'user_id' => $saleData['user_id'],
                'cash_register_id' => $saleData['cash_register_id'] ?? null,
                'tax_percentage' => $saleData['tax_percentage'],
                'discount_percentage' => $saleData['discount_percentage'],
                'shipping_amount' => $saleData['shipping_amount'] * 100,
                'paid_amount' => $saleData['paid_amount'] * 100,
                'total_amount' => $saleData['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $saleData['payment_method'],
                'note' => $saleData['note'],
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

            $inventoryItems = [];

            foreach ($cartItems as $cartItem) {
                $isObject = is_object($cartItem);

                $productId = $isObject ? $cartItem->id : $cartItem['id'];
                $productName = $isObject ? $cartItem->name : $cartItem['name'];
                $productCode = $isObject ? $cartItem->options->code : $cartItem['attributes']['code'];
                $quantity = $isObject ? $cartItem->qty : $cartItem['quantity'];
                $price = $isObject ? $cartItem->price : $cartItem['price'];
                $unitPrice = $isObject ? $cartItem->options->unit_price : $cartItem['attributes']['unit_price'];
                $subTotal = $isObject ? $cartItem->options->sub_total : $cartItem['attributes']['sub_total'];
                $discountAmount = $isObject ? $cartItem->options->product_discount : $cartItem['attributes']['product_discount'];
                $discountType = $isObject ? $cartItem->options->product_discount_type : $cartItem['attributes']['product_discount_type'];
                $taxAmount = $isObject ? $cartItem->options->product_tax : $cartItem['attributes']['product_tax'];

                SaleDetails::query()->create([
                    'sale_id' => $sale->id,
                    'warehouse_id' => $saleData['warehouse_id'],
                    'product_id' => $productId,
                    'name' => $productName,
                    'code' => $productCode,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'unit_price' => $unitPrice * 100,
                    'sub_total' => $subTotal * 100,
                    'product_discount_amount' => $discountAmount * 100,
                    'product_discount_type' => $discountType,
                    'product_tax_amount' => $taxAmount * 100,
                ]);

                if (! $isDraft) {
                    $inventoryItems[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price * 100,
                        'unit_price' => $unitPrice * 100,
                    ];
                }
            }

            if ($inventoryItems !== []) {
                dispatch(new \App\Jobs\SyncInventoryJob($inventoryItems, $saleData['warehouse_id'], $saleData['user_id'], 'sale'));
            }

            if ($saleData['paid_amount'] > 0 && ! $isDraft) {
                SalePayment::query()->create([
                    'date' => date('Y-m-d'),
                    'amount' => $saleData['paid_amount'] * 100,
                    'sale_id' => $sale->id,
                    'payment_method' => $saleData['payment_method'],
                    'cash_register_id' => $saleData['cash_register_id'] ?? null,
                    'user_id' => $saleData['user_id'],
                ]);
            }

            return $sale;
        });
    }

    public function update(Sale $sale, array $saleData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): Sale
    {
        return DB::transaction(function () use ($sale, $saleData, $cartItems, $cartTax, $cartDiscount): Sale {
            $dueAmount = $saleData['total_amount'] - $saleData['paid_amount'];

            if ($dueAmount === $saleData['total_amount']) {
                $paymentStatus = PaymentStatus::PENDING;
                $status = SaleStatus::PENDING;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
                $status = SaleStatus::PENDING;
            } else {
                $paymentStatus = PaymentStatus::PAID;
                $status = SaleStatus::COMPLETED;
            }

            // Clean up old details
            foreach ($sale->saleDetails as $detail) {
                $detail->delete();
            }

            $sale->update([
                'date' => $saleData['date'],
                'customer_id' => $saleData['customer_id'],
                'warehouse_id' => $saleData['warehouse_id'],
                'tax_percentage' => $saleData['tax_percentage'],
                'discount_percentage' => $saleData['discount_percentage'],
                'shipping_amount' => $saleData['shipping_amount'] * 100,
                'paid_amount' => $saleData['paid_amount'] * 100,
                'total_amount' => $saleData['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $saleData['payment_method'],
                'note' => $saleData['note'],
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

            $inventoryItems = [];

            foreach ($cartItems as $cartItem) {
                $isObject = is_object($cartItem);

                $productId = $isObject ? $cartItem->id : $cartItem['id'];
                $productName = $isObject ? $cartItem->name : $cartItem['name'];
                $productCode = $isObject ? $cartItem->options->code : $cartItem['attributes']['code'];
                $quantity = $isObject ? $cartItem->qty : $cartItem['quantity'];
                $price = $isObject ? $cartItem->price : $cartItem['price'];
                $unitPrice = $isObject ? $cartItem->options->unit_price : $cartItem['attributes']['unit_price'];
                $subTotal = $isObject ? $cartItem->options->sub_total : $cartItem['attributes']['sub_total'];
                $discountAmount = $isObject ? $cartItem->options->product_discount : $cartItem['attributes']['product_discount'];
                $discountType = $isObject ? $cartItem->options->product_discount_type : $cartItem['attributes']['product_discount_type'];
                $taxAmount = $isObject ? $cartItem->options->product_tax : $cartItem['attributes']['product_tax'];

                SaleDetails::query()->create([
                    'sale_id' => $sale->id,
                    'warehouse_id' => $saleData['warehouse_id'],
                    'product_id' => $productId,
                    'name' => $productName,
                    'code' => $productCode,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'unit_price' => $unitPrice * 100,
                    'sub_total' => $subTotal * 100,
                    'product_discount_amount' => $discountAmount * 100,
                    'product_discount_type' => $discountType,
                    'product_tax_amount' => $taxAmount * 100,
                ]);

                // We assume inventory should be updated on edit as well if needed.
                // For simplicity, we just dispatch the job for new items. (Ideally, we'd adjust for previous quantities, but we follow the old edit logic which just added new quantities).
                $inventoryItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'unit_price' => $unitPrice * 100,
                ];
            }

            if ($inventoryItems !== []) {
                dispatch(new \App\Jobs\SyncInventoryJob($inventoryItems, $saleData['warehouse_id'], auth()->id(), 'sale'));
            }

            return $sale;
        });
    }

    public function delete(Sale $sale): void
    {
        DB::transaction(function () use ($sale): void {
            foreach ($sale->saleDetails as $detail) {
                $detail->delete();
            }

            $sale->delete();
        });
    }
}
