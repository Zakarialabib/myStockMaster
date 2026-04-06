<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Quotation;
use App\Models\QuotationDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationService
{
    public function create(array $data, $cartContent, $cartTax, $cartDiscount): Quotation
    {
        return DB::transaction(function () use ($data, $cartContent, $cartTax, $cartDiscount) {
            $quotation = Quotation::create([
                'date' => $data['date'],
                'customer_id' => $data['customer_id'],
                'warehouse_id' => $data['warehouse_id'],
                'user_id' => Auth::id(),
                'tax_percentage' => $data['tax_percentage'] ?? 0,
                'discount_percentage' => $data['discount_percentage'] ?? 0,
                'shipping_amount' => ($data['shipping_amount'] ?? 0) * 100,
                'total_amount' => ($data['total_amount'] ?? 0) * 100,
                'status' => $data['status'],
                'note' => $data['note'] ?? null,
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

            foreach ($cartContent as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cart_item->id,
                    'name' => $cart_item->name,
                    'code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                ]);
            }

            return $quotation;
        });
    }

    public function update(Quotation $quotation, array $data, $cartContent, $cartTax, $cartDiscount): Quotation
    {
        return DB::transaction(function () use ($quotation, $data, $cartContent, $cartTax, $cartDiscount) {
            foreach ($quotation->quotationDetails as $quotation_detail) {
                $quotation_detail->delete();
            }

            $quotation->update([
                'date' => $data['date'],
                'reference' => $data['reference'] ?? $quotation->reference,
                'customer_id' => $data['customer_id'],
                'user_id' => Auth::id(),
                'warehouse_id' => $data['warehouse_id'],
                'tax_percentage' => $data['tax_percentage'] ?? 0,
                'discount_percentage' => $data['discount_percentage'] ?? 0,
                'shipping_amount' => ($data['shipping_amount'] ?? 0) * 100,
                'total_amount' => ($data['total_amount'] ?? 0) * 100,
                'status' => $data['status'],
                'note' => $data['note'] ?? null,
                'tax_amount' => (int) ($cartTax * 100),
                'discount_amount' => (int) ($cartDiscount * 100),
            ]);

            foreach ($cartContent as $cart_item) {
                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $cart_item->id,
                    'name' => $cart_item->name,
                    'code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                ]);
            }

            return $quotation;
        });
    }

    public function delete(Quotation $quotation): void
    {
        DB::transaction(function () use ($quotation) {
            foreach ($quotation->quotationDetails as $quotation_detail) {
                $quotation_detail->delete();
            }
            $quotation->delete();
        });
    }
}
