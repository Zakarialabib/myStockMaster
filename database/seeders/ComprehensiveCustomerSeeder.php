<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComprehensiveCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create different types of customers
        $this->createRetailCustomers();
        $this->createWholesaleCustomers();
        $this->createCorporateCustomers();
        $this->createOnlineCustomers();
    }

    private function createRetailCustomers(): void
    {
        $retailCustomers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-0101',
                'city' => 'New York',
                'country' => 'USA',
                'address' => '123 Main Street, Apt 4B',
                'customer_type' => 'retail',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@gmail.com',
                'phone' => '+1-555-0102',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'address' => '456 Oak Avenue',
                'customer_type' => 'retail',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@yahoo.com',
                'phone' => '+1-555-0103',
                'city' => 'Chicago',
                'country' => 'USA',
                'address' => '789 Pine Street',
                'customer_type' => 'retail',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@hotmail.com',
                'phone' => '+1-555-0104',
                'city' => 'Houston',
                'country' => 'USA',
                'address' => '321 Elm Drive',
                'customer_type' => 'retail',
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@outlook.com',
                'phone' => '+1-555-0105',
                'city' => 'Phoenix',
                'country' => 'USA',
                'address' => '654 Maple Lane',
                'customer_type' => 'retail',
            ],
        ];

        foreach ($retailCustomers as $retailCustomer) {
            $this->createCustomer($retailCustomer);
        }

        // Create additional random retail customers
        for ($i = 0; $i < 15; $i++) {
            $this->createCustomer([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'city' => fake()->city(),
                'country' => fake()->country(),
                'address' => fake()->streetAddress(),
                'customer_type' => 'retail',
            ]);
        }
    }

    private function createWholesaleCustomers(): void
    {
        $wholesaleCustomers = [
            [
                'name' => 'Metro Retail Chain',
                'email' => 'orders@metroretail.com',
                'phone' => '+1-555-0201',
                'city' => 'Atlanta',
                'country' => 'USA',
                'address' => '1000 Commerce Blvd, Suite 200',
                'customer_type' => 'wholesale',
            ],
            [
                'name' => 'City Market Group',
                'email' => 'purchasing@citymarket.com',
                'phone' => '+1-555-0202',
                'city' => 'Denver',
                'country' => 'USA',
                'address' => '2500 Industrial Way',
                'customer_type' => 'wholesale',
            ],
            [
                'name' => 'Regional Distributors LLC',
                'email' => 'orders@regionaldist.com',
                'phone' => '+1-555-0203',
                'city' => 'Miami',
                'country' => 'USA',
                'address' => '750 Distribution Center Dr',
                'customer_type' => 'wholesale',
            ],
            [
                'name' => 'Bulk Buyers Co-op',
                'email' => 'procurement@bulkbuyers.com',
                'phone' => '+1-555-0204',
                'city' => 'Seattle',
                'country' => 'USA',
                'address' => '1200 Warehouse District',
                'customer_type' => 'wholesale',
            ],
            [
                'name' => 'National Supply Network',
                'email' => 'orders@nationalsupply.com',
                'phone' => '+1-555-0205',
                'city' => 'Dallas',
                'country' => 'USA',
                'address' => '3000 Supply Chain Pkwy',
                'customer_type' => 'wholesale',
            ],
        ];

        foreach ($wholesaleCustomers as $wholesaleCustomer) {
            $this->createCustomer($wholesaleCustomer);
        }
    }

    private function createCorporateCustomers(): void
    {
        $corporateCustomers = [
            [
                'name' => 'TechCorp Industries',
                'email' => 'procurement@techcorp.com',
                'phone' => '+1-555-0301',
                'city' => 'San Francisco',
                'country' => 'USA',
                'address' => '100 Technology Plaza',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Global Manufacturing Inc',
                'email' => 'supplies@globalmanuf.com',
                'phone' => '+1-555-0302',
                'city' => 'Detroit',
                'country' => 'USA',
                'address' => '500 Industrial Complex',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Healthcare Solutions Group',
                'email' => 'purchasing@healthsolutions.com',
                'phone' => '+1-555-0303',
                'city' => 'Boston',
                'country' => 'USA',
                'address' => '200 Medical Center Dr',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Educational Services Corp',
                'email' => 'orders@eduservices.com',
                'phone' => '+1-555-0304',
                'city' => 'Philadelphia',
                'country' => 'USA',
                'address' => '300 Education Blvd',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Hospitality Management LLC',
                'email' => 'procurement@hospitalitymgmt.com',
                'phone' => '+1-555-0305',
                'city' => 'Las Vegas',
                'country' => 'USA',
                'address' => '400 Resort Row',
                'customer_type' => 'corporate',
            ],
        ];

        foreach ($corporateCustomers as $corporateCustomer) {
            $this->createCustomer($corporateCustomer);
        }
    }

    private function createOnlineCustomers(): void
    {
        $onlineCustomers = [
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer.martinez@gmail.com',
                'phone' => '+1-555-0401',
                'city' => 'Portland',
                'country' => 'USA',
                'address' => '123 Digital Ave',
                'customer_type' => 'online',
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert.taylor@yahoo.com',
                'phone' => '+1-555-0402',
                'city' => 'Austin',
                'country' => 'USA',
                'address' => '456 Web Street',
                'customer_type' => 'online',
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@hotmail.com',
                'phone' => '+1-555-0403',
                'city' => 'Nashville',
                'country' => 'USA',
                'address' => '789 E-commerce Blvd',
                'customer_type' => 'online',
            ],
            [
                'name' => 'Christopher Lee',
                'email' => 'christopher.lee@outlook.com',
                'phone' => '+1-555-0404',
                'city' => 'Charlotte',
                'country' => 'USA',
                'address' => '321 Online Plaza',
                'customer_type' => 'online',
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@gmail.com',
                'phone' => '+1-555-0405',
                'city' => 'San Diego',
                'country' => 'USA',
                'address' => '654 Virtual Lane',
                'customer_type' => 'online',
            ],
        ];

        foreach ($onlineCustomers as $onlineCustomer) {
            $this->createCustomer($onlineCustomer);
        }

        // Create additional random online customers
        for ($i = 0; $i < 10; $i++) {
            $this->createCustomer([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'city' => fake()->city(),
                'country' => fake()->country(),
                'address' => fake()->streetAddress(),
                'customer_type' => 'online',
            ]);
        }
    }

    private function createCustomer(array $customerData): void
    {
        $customerId = (string) Str::uuid();

        $customer = [
            'id' => $customerId,
            'name' => $customerData['name'],
            'email' => $customerData['email'],
            'phone' => $customerData['phone'],
            'city' => $customerData['city'],
            'country' => $customerData['country'],
            'address' => $customerData['address'],
            'tax_number' => $this->generateTaxNumber($customerData['customer_type']),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('customers')->insertOrIgnore($customer);
    }

    private function generateTaxNumber(mixed $customerType): string
    {
        return match ($customerType) {
            'corporate', 'wholesale' => 'TAX-' . fake()->numerify('##-#######'),
            'retail', 'online' => fake()->boolean(30) ? 'TAX-' . fake()->numerify('##-#######') : null,
            default => null,
        };
    }
}
