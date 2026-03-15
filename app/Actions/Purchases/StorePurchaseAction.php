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
                'date'                => $purchaseData['date'],
                'supplier_id'         => $purchaseData['supplier_id'],
                'warehouse_id'        => $purchaseData['warehouse_id'],
                'user_id'             => $purchaseData['user_id'],
                'tax_percentage'      => $purchaseData['tax_percentage'],
                'discount_percentage' => $purchaseData['discount_percentage'],
                'shipping_amount'     => $purchaseData['shipping_amount'] * 100,
                'paid_amount'         => $purchaseData['paid_amount'] * 100,
                'total_amount'        => $purchaseData['total_amount'] * 100,
                'due_amount'          => $dueAmount * 100,
                'status'              => $status,
                'payment_status'      => $paymentStatus,
                'payment_method'      => $purchaseData['payment_method'],
                'note'                => $purchaseData['note'],
                'tax_amount'          => (int) ($cartTax * 100),
                'discount_amount'     => (int) ($cartDiscount * 100),
            ]);

            foreach ($cartItems as $cartItem) {
                PurchaseDetail::create([
                    'purchase_id'             => $purchase->id,
                    'product_id'              => $cartItem->id,
                    'warehouse_id'            => $purchaseData['warehouse_id'],
                    'name'                    => $cartItem->name,
                    'code'                    => $cartItem->options->code,
                    'quantity'                => $cartItem->qty,
                    'price'                   => $cartItem->price * 100,
                    'unit_price'              => $cartItem->options->unit_price * 100,
                    'sub_total'               => $cartItem->options->sub_total * 100,
                    'product_discount_amount' => $cartItem->options->product_discount * 100,
                    'product_discount_type'   => $cartItem->options->product_discount_type,
                    'product_tax_amount'      => $cartItem->options->product_tax * 100,
                ]);

                $product = Product::findOrFail($cartItem->id);

                $productWarehouse = ProductWarehouse::firstOrNew([
                    'product_id'   => $product->id,
                    'warehouse_id' => $purchaseData['warehouse_id'],
                ], [
                    'price' => $cartItem->price,
                    'cost'  => $cartItem->options->unit_price,
                    'qty'   => 0,
                ]);

                $productWarehouse->fill([
                    'qty'  => $productWarehouse->qty + $cartItem->qty,
                    'cost' => $productWarehouse->cost,
                ]);
                $productWarehouse->save();

                Movement::create([
                    'type'         => MovementType::PURCHASE,
                    'quantity'     => $cartItem->qty,
                    'price'        => $cartItem->price * 100,
                    'date'         => date('Y-m-d'),
                    'movable_type' => Product::class,
                    'movable_id'   => $product->id,
                    'user_id'      => $purchaseData['user_id'],
                ]);
            }

            if ($purchaseData['paid_amount'] > 0) {
                PurchasePayment::create([
                    'date'           => date('Y-m-d'),
                    'user_id'        => $purchaseData['user_id'],
                    'amount'         => $purchase->paid_amount,
                    'purchase_id'    => $purchase->id,
                    'payment_method' => $purchaseData['payment_method'],
                ]);
            }

            return $purchase;
        });
    }
}
