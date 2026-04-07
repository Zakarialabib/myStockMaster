<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnPayment;
use Illuminate\Support\Facades\DB;

class PurchaseReturnService
{
    public function create(array $data, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): PurchaseReturn
    {
        return DB::transaction(function () use ($data, $cartItems, $cartTax, $cartDiscount): PurchaseReturn {
            $dueAmount = $data['total_amount'] - $data['paid_amount'];

            if ($dueAmount === $data['total_amount']) {
                $paymentStatus = PaymentStatus::DUE;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
            } else {
                $paymentStatus = PaymentStatus::PAID;
            }

            $purchaseReturn = PurchaseReturn::query()->create([
                'date' => $data['date'],
                'reference' => $data['reference'] ?? 'PRRN-' . date('YmdHis'),
                'supplier_id' => $data['supplier_id'],
                'user_id' => $data['user_id'],
                'tax_percentage' => $data['tax_percentage'],
                'discount_percentage' => $data['discount_percentage'],
                'shipping_amount' => $data['shipping_amount'] * 100,
                'paid_amount' => $data['paid_amount'] * 100,
                'total_amount' => $data['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $data['status'],
                'payment_status' => $paymentStatus,
                'payment_method' => $data['payment_method'],
                'note' => $data['note'],
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

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

                $itemWarehouseId = ! $isObject && isset($cartItem['attributes']['warehouse_id'])
                    ? $cartItem['attributes']['warehouse_id']
                    : $data['warehouse_id'];

                PurchaseReturnDetail::query()->create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'warehouse_id' => $itemWarehouseId,
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

                if ($data['status'] === 'Shipped' || $data['status'] === 'Completed') {
                    $productWarehouse = ProductWarehouse::query()->where('product_id', $productId)
                        ->where('warehouse_id', $itemWarehouseId)
                        ->first();

                    if ($productWarehouse) {
                        $productWarehouse->update([
                            'qty' => $productWarehouse->qty + $quantity,
                        ]);
                    }
                }
            }

            if ($data['paid_amount'] > 0) {
                PurchaseReturnPayment::query()->create([
                    'date' => $data['date'],
                    'reference' => 'INV/' . $purchaseReturn->reference,
                    'amount' => $data['paid_amount'] * 100,
                    'purchase_return_id' => $purchaseReturn->id,
                    'payment_method' => $data['payment_method'],
                ]);
            }

            $purchaseReturn->syncTotals();

            return $purchaseReturn;
        });
    }

    public function update(PurchaseReturn $purchaseReturn, array $data, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): PurchaseReturn
    {
        return DB::transaction(function () use ($purchaseReturn, $data, $cartItems, $cartTax, $cartDiscount): PurchaseReturn {
            $dueAmount = $data['total_amount'] - $data['paid_amount'];

            if ($dueAmount === $data['total_amount']) {
                $paymentStatus = PaymentStatus::DUE;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
            } else {
                $paymentStatus = PaymentStatus::PAID;
            }

            foreach ($purchaseReturn->purchaseReturnDetails as $detail) {
                if ($purchaseReturn->status === 'Shipped' || $purchaseReturn->status === 'Completed') {
                    $product = Product::query()->findOrFail($detail->product_id);
                    $product->update([
                        'quantity' => $product->quantity + $detail->quantity,
                    ]);
                }

                $detail->delete();
            }

            $purchaseReturn->update([
                'date' => $data['date'],
                'reference' => $data['reference'],
                'supplier_id' => $data['supplier_id'],
                'tax_percentage' => $data['tax_percentage'],
                'discount_percentage' => $data['discount_percentage'],
                'shipping_amount' => $data['shipping_amount'] * 100,
                'paid_amount' => $data['paid_amount'] * 100,
                'total_amount' => $data['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => $data['status'],
                'payment_status' => $paymentStatus,
                'payment_method' => $data['payment_method'],
                'note' => $data['note'],
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

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

                PurchaseReturnDetail::query()->create([
                    'purchase_return_id' => $purchaseReturn->id,
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

                if ($data['status'] === 'Shipped' || $data['status'] === 'Completed') {
                    $product = Product::query()->findOrFail($productId);
                    $product->update([
                        'quantity' => $product->quantity - $quantity,
                    ]);
                }
            }

            return $purchaseReturn;
        });
    }
}
