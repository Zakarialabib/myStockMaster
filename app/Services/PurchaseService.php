<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Jobs\SyncInventoryJob;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function create(array $purchaseData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount, bool $isDraft = false): Purchase
    {
        return DB::transaction(function () use ($purchaseData, $cartItems, $cartTax, $cartDiscount, $isDraft): Purchase {
            $dueAmount = $purchaseData['total_amount'] - $purchaseData['paid_amount'];

            if ($dueAmount === $purchaseData['total_amount']) {
                $paymentStatus = PaymentStatus::PENDING;
                $status = PurchaseStatus::PENDING;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
                $status = PurchaseStatus::PENDING;
            } else {
                $paymentStatus = PaymentStatus::PAID;
                $status = PurchaseStatus::COMPLETED;
            }

            if ($isDraft) {
                $status = PurchaseStatus::PENDING;
                $paymentStatus = PaymentStatus::PENDING;
            }

            $purchase = Purchase::query()->create([
                'date' => $purchaseData['date'],
                'supplier_id' => $purchaseData['supplier_id'],
                'warehouse_id' => $purchaseData['warehouse_id'],
                'user_id' => $purchaseData['user_id'],
                'tax_percentage' => $purchaseData['tax_percentage'],
                'discount_percentage' => $purchaseData['discount_percentage'],
                'shipping_amount' => $purchaseData['shipping_amount'] * 100,
                'paid_amount' => $purchaseData['paid_amount'] * 100,
                'total_amount' => $purchaseData['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $purchaseData['payment_method'],
                'note' => $purchaseData['note'],
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

                PurchaseDetail::query()->create([
                    'purchase_id' => $purchase->id,
                    'warehouse_id' => $purchaseData['warehouse_id'],
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
                dispatch(new \App\Jobs\SyncInventoryJob($inventoryItems, $purchaseData['warehouse_id'], $purchaseData['user_id'], 'purchase'));
            }

            if ($purchaseData['paid_amount'] > 0 && ! $isDraft) {
                PurchasePayment::query()->create([
                    'date' => date('Y-m-d'),
                    'user_id' => $purchaseData['user_id'],
                    'amount' => $purchase->paid_amount,
                    'purchase_id' => $purchase->id,
                    'payment_method' => $purchaseData['payment_method'],
                ]);
            }

            return $purchase;
        });
    }

    public function update(Purchase $purchase, array $purchaseData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): Purchase
    {
        return DB::transaction(function () use ($purchase, $purchaseData, $cartItems, $cartTax, $cartDiscount): Purchase {
            $dueAmount = $purchaseData['total_amount'] - $purchaseData['paid_amount'];

            if ($dueAmount === $purchaseData['total_amount']) {
                $paymentStatus = PaymentStatus::PENDING;
                $status = PurchaseStatus::PENDING;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
                $status = PurchaseStatus::PENDING;
            } else {
                $paymentStatus = PaymentStatus::PAID;
                $status = PurchaseStatus::COMPLETED;
            }

            // Delete previous purchase details
            foreach ($purchase->purchaseDetails as $detail) {
                $detail->delete();
            }

            $purchase->update([
                'date' => $purchaseData['date'],
                'supplier_id' => $purchaseData['supplier_id'],
                'warehouse_id' => $purchaseData['warehouse_id'],
                'tax_percentage' => $purchaseData['tax_percentage'],
                'discount_percentage' => $purchaseData['discount_percentage'],
                'shipping_amount' => $purchaseData['shipping_amount'] * 100,
                'paid_amount' => $purchaseData['paid_amount'] * 100,
                'total_amount' => $purchaseData['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $purchaseData['payment_method'],
                'note' => $purchaseData['note'],
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

                PurchaseDetail::query()->create([
                    'purchase_id' => $purchase->id,
                    'warehouse_id' => $purchaseData['warehouse_id'],
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

                $inventoryItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'unit_price' => $unitPrice * 100,
                ];
            }

            if ($inventoryItems !== []) {
                dispatch(new \App\Jobs\SyncInventoryJob($inventoryItems, $purchaseData['warehouse_id'], auth()->id(), 'purchase'));
            }

            return $purchase;
        });
    }

    public function delete(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase): void {
            foreach ($purchase->purchaseDetails as $detail) {
                $detail->delete();
            }

            $purchase->delete();
        });
    }
}
