<?php

declare(strict_types=1);

namespace App\Actions\Purchases;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;

final class StorePurchaseAction
{
    public function __invoke(array $purchaseData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): Purchase
    {
        return DB::transaction(function () use ($purchaseData, $cartItems, $cartTax, $cartDiscount): Purchase {
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

            $purchase = Purchase::create([
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

            foreach ($cartItems as $cartItem) {
                // Determine structure based on whether it's an object or array
                // The new CartService trait passes arrays, old format passed objects
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

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'warehouse_id' => $purchaseData['warehouse_id'],
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

                $product = Product::findOrFail($productId);

                $productWarehouse = ProductWarehouse::firstOrNew([
                    'product_id' => $product->id,
                    'warehouse_id' => $purchaseData['warehouse_id'],
                ], [
                    'price' => $price,
                    'cost' => $unitPrice,
                    'qty' => 0,
                ]);

                $productWarehouse->fill([
                    'qty' => $productWarehouse->qty + $quantity,
                    'cost' => $productWarehouse->cost,
                ]);
                $productWarehouse->save();

                Movement::create([
                    'type' => MovementType::PURCHASE,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'date' => date('Y-m-d'),
                    'movable_type' => get_class($product),
                    'movable_id' => $product->id,
                    'user_id' => $purchaseData['user_id'],
                ]);
            }

            if ($purchaseData['paid_amount'] > 0) {
                PurchasePayment::create([
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
}
