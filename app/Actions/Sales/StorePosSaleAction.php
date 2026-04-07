<?php

declare(strict_types=1);

namespace App\Actions\Sales;

use App\Enums\MovementType;
use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;

final class StorePosSaleAction
{
    public function __invoke(array $saleData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): Sale
    {
        return DB::transaction(function () use ($saleData, $cartItems, $cartTax, $cartDiscount): Sale {
            $dueAmount = $saleData['total_amount'] - $saleData['paid_amount'];

            if ($dueAmount === $saleData['total_amount']) {
                $paymentStatus = PaymentStatus::PENDING;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
            } else {
                $paymentStatus = PaymentStatus::PAID;
            }

            $sale = Sale::query()->create([
                'date' => $saleData['date'],
                'customer_id' => $saleData['customer_id'],
                'warehouse_id' => $saleData['warehouse_id'],
                'user_id' => $saleData['user_id'],
                'cash_register_id' => $saleData['cash_register_id'],
                'tax_percentage' => $saleData['tax_percentage'],
                'discount_percentage' => $saleData['discount_percentage'],
                'shipping_amount' => $saleData['shipping_amount'] * 100,
                'paid_amount' => $saleData['paid_amount'] * 100,
                'total_amount' => $saleData['total_amount'] * 100,
                'due_amount' => $dueAmount * 100,
                'status' => SaleStatus::COMPLETED,
                'payment_status' => $paymentStatus,
                'payment_method' => $saleData['payment_method'],
                'note' => $saleData['note'],
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

            foreach ($cartItems as $cartItem) {
                // Handle both object (old format) and array (new CartService format)
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

                $product = Product::query()->findOrFail($productId);

                $productWarehouse = ProductWarehouse::query()->where('product_id', $product->id)
                    ->where('warehouse_id', $saleData['warehouse_id'])
                    ->firstOrFail();

                $productWarehouse->update([
                    'qty' => $productWarehouse->qty - $quantity,
                ]);

                Movement::query()->create([
                    'type' => MovementType::SALE,
                    'quantity' => $quantity,
                    'price' => $price * 100,
                    'date' => date('Y-m-d'),
                    'movable_type' => $product::class,
                    'movable_id' => $product->id,
                    'user_id' => $saleData['user_id'],
                ]);
            }

            if ($saleData['paid_amount'] > 0) {
                SalePayment::query()->create([
                    'date' => date('Y-m-d'),
                    'amount' => $saleData['paid_amount'],
                    'cash_register_id' => $saleData['cash_register_id'],
                    'sale_id' => $sale->id,
                    'payment_method' => $saleData['payment_method'],
                    'user_id' => $saleData['user_id'],
                ]);
            }

            return $sale;
        });
    }
}
