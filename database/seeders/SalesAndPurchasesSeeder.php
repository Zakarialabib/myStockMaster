<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use App\Enums\SaleStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesAndPurchasesSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Get existing data
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        $warehouses = Warehouse::all();
        $users = User::all();

        if ($customers->isEmpty() || $suppliers->isEmpty() || $products->isEmpty() || $warehouses->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run other seeders first (customers, suppliers, products, warehouses, users)');

            return;
        }

        // Create Sales Data for the last 12 months
        $this->createSalesData($customers, $products, $warehouses, $users);

        // Create Purchase Data for the last 12 months
        $this->createPurchaseData($suppliers, $products);

        $this->command->info('Sales and Purchases data seeded successfully!');
    }

    private function createSalesData(mixed $customers, mixed $products, mixed $warehouses, mixed $users): void
    {
        $startDate = \Illuminate\Support\Facades\Date::now()->subMonths(12);
        $endDate = \Illuminate\Support\Facades\Date::now()->subDays(1); // Ensure we don't use future dates

        // Create 200 sales records
        for ($i = 0; $i < 200; $i++) {
            $date = \Illuminate\Support\Facades\Date::createFromTimestamp(
                random_int($startDate->timestamp, $endDate->timestamp)
            )->startOfDay(); // Normalize to start of day

            $customer = $customers->random();
            $warehouse = $warehouses->random();
            $user = $users->random();

            // Calculate amounts
            $taxPercentage = random_int(0, 20);
            $discountPercentage = random_int(0, 15);
            $subtotal = random_int(100, 5000);
            $discountAmount = ($subtotal * $discountPercentage) / 100;
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = ($taxableAmount * $taxPercentage) / 100;
            $shippingAmount = random_int(0, 100);
            $totalAmount = $taxableAmount + $taxAmount + $shippingAmount;

            // Random payment status
            $paymentStatuses = [PaymentStatus::PAID, PaymentStatus::PARTIAL, PaymentStatus::PENDING];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            $paidAmount = match ($paymentStatus) {
                PaymentStatus::PAID => $totalAmount,
                PaymentStatus::PARTIAL => random_int(1, (int) ($totalAmount * 0.8)),
                PaymentStatus::PENDING => 0,
            };

            $dueAmount = $totalAmount - $paidAmount;

            $sale = Sale::query()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'date' => $date->format('Y-m-d'),
                'reference' => 'SL-' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'warehouse_id' => $warehouse->id,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => SaleStatus::COMPLETED,
                'payment_status' => $paymentStatus,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Create sale details (products)
            $numProducts = random_int(1, 5);
            $selectedProducts = $products->random($numProducts);

            foreach ($selectedProducts as $selectedProduct) {
                $quantity = random_int(1, 10);
                $price = $selectedProduct->price ?? random_int(10, 500);
                DB::table('sale_details')->insertOrIgnore([
                    'sale_id' => $sale->id,
                    'product_id' => $selectedProduct->id,
                    'name' => $selectedProduct->name,
                    'code' => $selectedProduct->code,
                    'quantity' => $quantity,
                    'price' => $price,
                    'unit_price' => $price,
                    'sub_total' => $price * $quantity,
                    'product_discount_amount' => 0,
                    'product_tax_amount' => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }

    private function createPurchaseData(mixed $suppliers, mixed $products): void
    {
        $startDate = \Illuminate\Support\Facades\Date::now()->subMonths(12);
        $endDate = \Illuminate\Support\Facades\Date::now()->subDays(1); // Ensure we don't use future dates

        // Create 150 purchase records
        for ($i = 0; $i < 150; $i++) {
            $date = \Illuminate\Support\Facades\Date::createFromTimestamp(
                random_int($startDate->timestamp, $endDate->timestamp)
            )->startOfDay(); // Normalize to start of day

            $supplier = $suppliers->random();

            // Calculate amounts
            $taxPercentage = random_int(0, 20);
            $discountPercentage = random_int(0, 10);
            $subtotal = random_int(200, 8000);
            $discountAmount = ($subtotal * $discountPercentage) / 100;
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = ($taxableAmount * $taxPercentage) / 100;
            $shippingAmount = random_int(0, 200);
            $totalAmount = $taxableAmount + $taxAmount + $shippingAmount;

            // Random payment status
            $paymentStatuses = [PaymentStatus::PAID, PaymentStatus::PARTIAL, PaymentStatus::PENDING];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            $paidAmount = match ($paymentStatus) {
                PaymentStatus::PAID => $totalAmount,
                PaymentStatus::PARTIAL => random_int(1, (int) ($totalAmount * 0.7)),
                PaymentStatus::PENDING => 0,
            };

            $dueAmount = $totalAmount - $paidAmount;

            $purchase = Purchase::query()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'date' => $date->format('Y-m-d'),
                'reference' => 'PU-' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => PurchaseStatus::COMPLETED,
                'payment_status' => $paymentStatus,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Create purchase details (products)
            $numProducts = random_int(1, 4);
            $selectedProducts = $products->random($numProducts);

            foreach ($selectedProducts as $selectedProduct) {
                $quantity = random_int(5, 50);
                $price = $selectedProduct->cost ?? random_int(5, 300);
                DB::table('purchase_details')->insertOrIgnore([
                    'purchase_id' => $purchase->id,
                    'product_id' => $selectedProduct->id,
                    'name' => $selectedProduct->name,
                    'code' => $selectedProduct->code,
                    'quantity' => $quantity,
                    'price' => $price,
                    'unit_price' => $price,
                    'sub_total' => $price * $quantity,
                    'product_discount_amount' => 0,
                    'product_tax_amount' => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
