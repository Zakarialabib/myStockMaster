<?php

declare(strict_types=1);

namespace App\Actions\Sales;

use App\Enums\PaymentStatus;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnPayment;
use Illuminate\Support\Facades\DB;

final class StoreSaleReturnAction
{
    public function __invoke(array $data, iterable $cartItems, float|int $cartTax, float|int $cartDiscount): SaleReturn
    {
        return DB::transaction(function () use ($data, $cartItems, $cartTax, $cartDiscount): SaleReturn {
            $dueAmount = $data['total_amount'] - $data['paid_amount'];

            if ($dueAmount === $data['total_amount']) {
                $paymentStatus = PaymentStatus::DUE;
            } elseif ($dueAmount > 0) {
                $paymentStatus = PaymentStatus::PARTIAL;
            } else {
                $paymentStatus = PaymentStatus::PAID;
            }

            $saleReturn = SaleReturn::create([
                'date'                => $data['date'],
                'reference'           => $data['reference'] ?? 'SLRN-'.date('YmdHis'),
                'customer_id'         => $data['customer_id'],
                'user_id'             => $data['user_id'],
                'tax_percentage'      => $data['tax_percentage'],
                'discount_percentage' => $data['discount_percentage'],
                'shipping_amount'     => $data['shipping_amount'] * 100,
                'paid_amount'         => $data['paid_amount'] * 100,
                'total_amount'        => $data['total_amount'] * 100,
                'due_amount'          => $dueAmount * 100,
                'status'              => $data['status'],
                'payment_status'      => $paymentStatus,
                'payment_method'      => $data['payment_method'],
                'note'                => $data['note'],
                'tax_amount'          => (int) ($cartTax * 100),
                'discount_amount'     => (int) ($cartDiscount * 100),
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

                SaleReturnDetail::create([
                    'sale_return_id'  => $saleReturn->id,
                    'product_id'      => $productId,
                    'name'            => $productName,
                    'code'            => $productCode,
                    'quantity'        => $quantity,
                    'price'           => $price * 100,
                    'unit_price'      => $unitPrice * 100,
                    'sub_total'       => $subTotal * 100,
                    'discount_amount' => $discountAmount * 100,
                    'discount_type'   => $discountType,
                    'tax_amount'      => $taxAmount * 100,
                ]);

                if ($data['status'] === 'Completed') {
                    $product = Product::findOrFail($productId);
                    $product->update([
                        'quantity' => $product->quantity + $quantity,
                    ]);
                }
            }

            if ($data['paid_amount'] > 0) {
                SaleReturnPayment::create([
                    'date'           => $data['date'],
                    'reference'      => 'INV/'.$saleReturn->reference,
                    'amount'         => $data['paid_amount'] * 100,
                    'sale_return_id' => $saleReturn->id,
                    'payment_method' => $data['payment_method'],
                ]);
            }

            return $saleReturn;
        });
    }
}
