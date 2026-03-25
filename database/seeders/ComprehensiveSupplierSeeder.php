<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComprehensiveSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create different types of suppliers
        $this->createManufacturingSuppliers();
        $this->createDistributionSuppliers();
        $this->createServiceSuppliers();
        $this->createInternationalSuppliers();
        $this->createLocalSuppliers();
    }

    private function createManufacturingSuppliers()
    {
        $manufacturingSuppliers = [
            [
                'name' => 'TechManufacturing Corp',
                'email' => 'sales@techmanufacturing.com',
                'phone' => '+1-555-1001',
                'city' => 'San Jose',
                'country' => 'USA',
                'address' => '1000 Silicon Valley Blvd',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Electronics & Technology',
            ],
            [
                'name' => 'Fashion Forward Industries',
                'email' => 'orders@fashionforward.com',
                'phone' => '+1-555-1002',
                'city' => 'New York',
                'country' => 'USA',
                'address' => '500 Garment District Ave',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Clothing & Textiles',
            ],
            [
                'name' => 'Premium Food Processing',
                'email' => 'wholesale@premiumfood.com',
                'phone' => '+1-555-1003',
                'city' => 'Chicago',
                'country' => 'USA',
                'address' => '2000 Food Processing Park',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Food & Beverages',
            ],
            [
                'name' => 'Home Goods Manufacturing',
                'email' => 'sales@homegoods.com',
                'phone' => '+1-555-1004',
                'city' => 'Grand Rapids',
                'country' => 'USA',
                'address' => '750 Furniture Row',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Home & Garden',
            ],
            [
                'name' => 'Sports Equipment Makers',
                'email' => 'orders@sportsequip.com',
                'phone' => '+1-555-1005',
                'city' => 'Portland',
                'country' => 'USA',
                'address' => '300 Athletic Way',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Sports & Outdoors',
            ],
        ];

        foreach ($manufacturingSuppliers as $supplier) {
            $this->createSupplier($supplier);
        }
    }

    private function createDistributionSuppliers()
    {
        $distributionSuppliers = [
            [
                'name' => 'National Distribution Network',
                'email' => 'sales@nationaldist.com',
                'phone' => '+1-555-2001',
                'city' => 'Atlanta',
                'country' => 'USA',
                'address' => '1500 Distribution Center Dr',
                'supplier_type' => 'distributor',
                'specialization' => 'Multi-Category Distribution',
            ],
            [
                'name' => 'Regional Wholesale Hub',
                'email' => 'orders@regionalwholesale.com',
                'phone' => '+1-555-2002',
                'city' => 'Dallas',
                'country' => 'USA',
                'address' => '2500 Wholesale Blvd',
                'supplier_type' => 'distributor',
                'specialization' => 'Regional Distribution',
            ],
            [
                'name' => 'Express Logistics Partners',
                'email' => 'sales@expresslogistics.com',
                'phone' => '+1-555-2003',
                'city' => 'Memphis',
                'country' => 'USA',
                'address' => '3000 Logistics Pkwy',
                'supplier_type' => 'distributor',
                'specialization' => 'Fast Distribution',
            ],
            [
                'name' => 'Bulk Supply Solutions',
                'email' => 'wholesale@bulksupply.com',
                'phone' => '+1-555-2004',
                'city' => 'Kansas City',
                'country' => 'USA',
                'address' => '1800 Bulk Storage Way',
                'supplier_type' => 'distributor',
                'specialization' => 'Bulk & Volume Sales',
            ],
        ];

        foreach ($distributionSuppliers as $supplier) {
            $this->createSupplier($supplier);
        }
    }

    private function createServiceSuppliers()
    {
        $serviceSuppliers = [
            [
                'name' => 'Professional Services Group',
                'email' => 'contact@proservices.com',
                'phone' => '+1-555-3001',
                'city' => 'Boston',
                'country' => 'USA',
                'address' => '100 Professional Plaza',
                'supplier_type' => 'service',
                'specialization' => 'Business Services',
            ],
            [
                'name' => 'Technical Support Solutions',
                'email' => 'support@techsupport.com',
                'phone' => '+1-555-3002',
                'city' => 'Austin',
                'country' => 'USA',
                'address' => '200 Tech Support Center',
                'supplier_type' => 'service',
                'specialization' => 'Technical Services',
            ],
            [
                'name' => 'Maintenance & Repair Co',
                'email' => 'service@maintenancerepair.com',
                'phone' => '+1-555-3003',
                'city' => 'Phoenix',
                'country' => 'USA',
                'address' => '300 Service Center Rd',
                'supplier_type' => 'service',
                'specialization' => 'Maintenance Services',
            ],
        ];

        foreach ($serviceSuppliers as $supplier) {
            $this->createSupplier($supplier);
        }
    }

    private function createInternationalSuppliers()
    {
        $internationalSuppliers = [
            [
                'name' => 'Global Electronics Ltd',
                'email' => 'export@globalelectronics.com',
                'phone' => '+86-21-5555-0001',
                'city' => 'Shanghai',
                'country' => 'China',
                'address' => '888 Electronics Industrial Park',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Consumer Electronics',
            ],
            [
                'name' => 'European Fashion House',
                'email' => 'sales@europeanfashion.com',
                'phone' => '+39-02-5555-0001',
                'city' => 'Milan',
                'country' => 'Italy',
                'address' => 'Via della Moda 123',
                'supplier_type' => 'manufacturer',
                'specialization' => 'High-End Fashion',
            ],
            [
                'name' => 'Nordic Home Design',
                'email' => 'orders@nordichome.com',
                'phone' => '+46-8-5555-0001',
                'city' => 'Stockholm',
                'country' => 'Sweden',
                'address' => 'Designgatan 45',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Scandinavian Furniture',
            ],
            [
                'name' => 'Japanese Precision Tools',
                'email' => 'export@japantools.com',
                'phone' => '+81-3-5555-0001',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'address' => '1-2-3 Precision District',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Precision Instruments',
            ],
            [
                'name' => 'German Engineering Solutions',
                'email' => 'sales@germanengineering.com',
                'phone' => '+49-89-5555-0001',
                'city' => 'Munich',
                'country' => 'Germany',
                'address' => 'Ingenieurstraße 100',
                'supplier_type' => 'manufacturer',
                'specialization' => 'Industrial Equipment',
            ],
        ];

        foreach ($internationalSuppliers as $supplier) {
            $this->createSupplier($supplier);
        }
    }

    private function createLocalSuppliers()
    {
        $localSuppliers = [
            [
                'name' => 'Local Farm Fresh Produce',
                'email' => 'orders@localfarm.com',
                'phone' => '+1-555-4001',
                'city' => 'Fresno',
                'country' => 'USA',
                'address' => '500 Farm Road',
                'supplier_type' => 'local',
                'specialization' => 'Fresh Produce',
            ],
            [
                'name' => 'Artisan Craft Supplies',
                'email' => 'sales@artisancraft.com',
                'phone' => '+1-555-4002',
                'city' => 'Santa Fe',
                'country' => 'USA',
                'address' => '123 Artisan Way',
                'supplier_type' => 'local',
                'specialization' => 'Handmade Crafts',
            ],
            [
                'name' => 'Community Bakery Supplies',
                'email' => 'wholesale@communitybakery.com',
                'phone' => '+1-555-4003',
                'city' => 'Burlington',
                'country' => 'USA',
                'address' => '789 Baker Street',
                'supplier_type' => 'local',
                'specialization' => 'Baked Goods',
            ],
            [
                'name' => 'Regional Auto Parts',
                'email' => 'sales@regionalauto.com',
                'phone' => '+1-555-4004',
                'city' => 'Detroit',
                'country' => 'USA',
                'address' => '1000 Auto Mile',
                'supplier_type' => 'local',
                'specialization' => 'Automotive Parts',
            ],
        ];

        foreach ($localSuppliers as $supplier) {
            $this->createSupplier($supplier);
        }

        // Create additional random local suppliers
        for ($i = 0; $i < 8; $i++) {
            $this->createSupplier([
                'name' => fake()->company() . ' Supplies',
                'email' => fake()->unique()->companyEmail(),
                'phone' => fake()->phoneNumber(),
                'city' => fake()->city(),
                'country' => fake()->country(),
                'address' => fake()->streetAddress(),
                'supplier_type' => 'local',
                'specialization' => fake()->randomElement([
                    'General Supplies',
                    'Specialty Items',
                    'Custom Products',
                    'Seasonal Goods',
                ]),
            ]);
        }
    }

    private function createSupplier($supplierData)
    {
        $supplierId = (string) Str::uuid();

        $supplier = [
            'id' => $supplierId,
            'name' => $supplierData['name'],
            'email' => $supplierData['email'],
            'phone' => $supplierData['phone'],
            'city' => $supplierData['city'],
            'country' => $supplierData['country'],
            'address' => $supplierData['address'],
            'tax_number' => $this->generateTaxNumber($supplierData['country']),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('suppliers')->insert($supplier);
    }

    private function generateTaxNumber($country)
    {
        $formats = [
            'USA' => 'US-' . fake()->numerify('##-#######'),
            'China' => 'CN-' . fake()->numerify('############'),
            'Italy' => 'IT-' . fake()->numerify('###########'),
            'Sweden' => 'SE-' . fake()->numerify('############'),
            'Japan' => 'JP-' . fake()->numerify('##########'),
            'Germany' => 'DE-' . fake()->numerify('###########'),
        ];

        return $formats[$country] ?? 'TAX-' . fake()->numerify('##########');
    }
}
