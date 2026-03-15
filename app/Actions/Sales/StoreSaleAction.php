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

final class StoreSaleAction
{
    public function __invoke(array $saleData, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): Sale
    {
        return DB::transaction(function () use ($saleData, $cartItems, $cartTax, $cartDiscount): Sale {
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

            $sale = Sale::create([
                'date'                => $saleData['date'],
                'customer_id'         => $saleData['customer_id'],
                'warehouse_id'        => $saleData['warehouse_id'],
                'user_id'             => $saleData['user_id'],
                'cash_register_id'    => $saleData['cash_register_id'],
                'tax_percentage'      => $saleData['tax_percentage'],
                'discount_percentage' => $saleData['discount_percentage'],
                'shipping_amount'     => $saleData['shipping_amount'] * 100,
                'paid_amount'         => $saleData['paid_amount'] * 100,
                'total_amount'        => $saleData['total_amount'] * 100,
                'due_amount'          => $dueAmount * 100,
                'status'              => $status,
                'payment_status'      => $paymentStatus,
                'payment_method'      => $saleData['payment_method'],
                'note'                => $saleData['note'],
                'tax_amount'          => (int) ($cartTax * 100),
                'discount_amount'     => (int) ($cartDiscount * 100),
            ]);

            foreach ($cartItems as $cartItem) {
                SaleDetails::create([
                    'sale_id'                 => $sale->id,
                    'warehouse_id'            => $saleData['warehouse_id'],
                    'product_id'              => $cartItem->id,
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

                $productWarehouse = ProductWarehouse::where('product_id', $product->id)
                    ->where('warehouse_id', $saleData['warehouse_id'])
                    ->firstOrFail();

                $productWarehouse->update([
                    'qty' => $productWarehouse->qty - $cartItem->qty,
                ]);

                Movement::create([
                    'type'         => MovementType::SALE,
                    'quantity'     => $cartItem->qty,
                    'price'        => $cartItem->price * 100,
                    'date'         => date('Y-m-d'),
                    'movable_type' => Product::class,
                    'movable_id'   => $product->id,
                    'user_id'      => $saleData['user_id'],
                ]);
            }

            if ($saleData['paid_amount'] > 0) {
                SalePayment::create([
                    'date'             => date('Y-m-d'),
                    'amount'           => $saleData['paid_amount'] * 100,
                    'sale_id'          => $sale->id,
                    'payment_method'   => $saleData['payment_method'],
                    'cash_register_id' => $saleData['cash_register_id'],
                    'user_id'          => $saleData['user_id'],
                ]);
            }

            return $sale;
        });
    }
}
