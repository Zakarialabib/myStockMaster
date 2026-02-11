<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ComprehensiveProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure we have warehouses, categories, and brands
        $this->ensureBasicData();

        // Get existing data
        $warehouses = Warehouse::all();
        $categories = Category::all();
        $brands = Brand::all();

        // Create comprehensive product categories if needed
        $this->createProductCategories();

        // Create realistic brands if needed
        $this->createRealisticBrands();

        // Refresh collections after creation
        $categories = Category::all();
        $brands = Brand::all();

        // Get selected business line from settings
        $selectedBusinessLine = settings('selected_business_line', 'all');

        // Create products based on selected business line
        $this->createProductsForBusinessLine($selectedBusinessLine, $categories, $brands, $warehouses);
    }

    private function createProductsForBusinessLine($businessLine, $categories, $brands, $warehouses)
    {
        switch ($businessLine) {
            case 'electronics':
                $this->createElectronicsProducts($categories, $brands, $warehouses);

                break;
            case 'sports':
                $this->createSportsOutdoorProducts($categories, $brands, $warehouses);

                break;
            case 'fashion':
                $this->createClothingProducts($categories, $brands, $warehouses);

                break;
            case 'restaurant':
            case 'grocery':
                $this->createFoodBeverageProducts($categories, $brands, $warehouses);

                break;
            case 'automotive':
                $this->createAutomotiveProducts($categories, $brands, $warehouses);

                break;
            case 'books':
                $this->createBooksMediaProducts($categories, $brands, $warehouses);

                break;
            case 'pharmacy':
                $this->createHealthBeautyProducts($categories, $brands, $warehouses);

                break;
            case 'jewelry':
                $this->createJewelryProducts($categories, $brands, $warehouses);

                break;
            case 'furniture':
                $this->createHomeGardenProducts($categories, $brands, $warehouses);

                break;
            default:
                // Create all product lines if no specific business line is selected
                $this->createElectronicsProducts($categories, $brands, $warehouses);
                $this->createClothingProducts($categories, $brands, $warehouses);
                $this->createFoodBeverageProducts($categories, $brands, $warehouses);
                $this->createHomeGardenProducts($categories, $brands, $warehouses);
                $this->createSportsOutdoorProducts($categories, $brands, $warehouses);
                $this->createHealthBeautyProducts($categories, $brands, $warehouses);
                $this->createAutomotiveProducts($categories, $brands, $warehouses);
                $this->createOfficeSuppliesProducts($categories, $brands, $warehouses);

                break;
        }
    }

    private function ensureBasicData()
    {
        // Ensure we have at least one warehouse
        if (Warehouse::count() === 0) {
            Warehouse::create([
                'name'    => 'Main Distribution Center',
                'city'    => 'Central City',
                'address' => '123 Industrial Blvd',
                'phone'   => '+1-555-0100',
                'email'   => 'main@warehouse.com',
                'country' => 'USA',
            ]);
        }
    }

    private function createProductCategories()
    {
        $categories = [
            ['name' => 'Electronics', 'code' => 'ELEC', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Clothing & Fashion', 'code' => 'CLTH', 'description' => 'Apparel and fashion items'],
            ['name' => 'Food & Beverages', 'code' => 'FOOD', 'description' => 'Food items and beverages'],
            ['name' => 'Home & Garden', 'code' => 'HOME', 'description' => 'Home improvement and garden supplies'],
            ['name' => 'Sports & Outdoors', 'code' => 'SPRT', 'description' => 'Sports equipment and outdoor gear'],
            ['name' => 'Health & Beauty', 'code' => 'HLTH', 'description' => 'Health and beauty products'],
            ['name' => 'Automotive', 'code' => 'AUTO', 'description' => 'Automotive parts and accessories'],
            ['name' => 'Office Supplies', 'code' => 'OFFC', 'description' => 'Office and business supplies'],
            ['name' => 'Books & Media', 'code' => 'BOOK', 'description' => 'Books, movies, and media'],
            ['name' => 'Toys & Games', 'code' => 'TOYS', 'description' => 'Toys and gaming products'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['code' => $categoryData['code']],
                $categoryData
            );
        }
    }

    private function createRealisticBrands()
    {
        $brands = [
            ['name' => 'TechPro', 'description' => 'Professional technology solutions', 'origin' => 'USA'],
            ['name' => 'StyleMax', 'description' => 'Fashion forward clothing brand', 'origin' => 'Italy'],
            ['name' => 'FreshChoice', 'description' => 'Premium food products', 'origin' => 'France'],
            ['name' => 'HomeComfort', 'description' => 'Quality home furnishings', 'origin' => 'Sweden'],
            ['name' => 'ActiveLife', 'description' => 'Sports and fitness equipment', 'origin' => 'Germany'],
            ['name' => 'PureWell', 'description' => 'Natural health and beauty', 'origin' => 'Canada'],
            ['name' => 'AutoMax', 'description' => 'Automotive excellence', 'origin' => 'Japan'],
            ['name' => 'OfficeElite', 'description' => 'Professional office solutions', 'origin' => 'USA'],
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate(
                ['name' => $brandData['name']],
                $brandData
            );
        }
    }

    private function createElectronicsProducts($categories, $brands, $warehouses)
    {
        $electronicsCategory = $categories->where('code', 'ELEC')->first();
        $techBrand = $brands->where('name', 'TechPro')->first();

        if ( ! $electronicsCategory || ! $techBrand) {
            return;
        }

        $products = [
            ['name' => 'Wireless Bluetooth Headphones', 'unit' => 'pcs', 'base_price' => 79.99, 'base_cost' => 45.00],
            ['name' => 'Smartphone Case Premium', 'unit' => 'pcs', 'base_price' => 24.99, 'base_cost' => 12.50],
            ['name' => 'USB-C Charging Cable 6ft', 'unit' => 'pcs', 'base_price' => 15.99, 'base_cost' => 8.00],
            ['name' => 'Wireless Mouse Ergonomic', 'unit' => 'pcs', 'base_price' => 34.99, 'base_cost' => 18.00],
            ['name' => 'Portable Power Bank 10000mAh', 'unit' => 'pcs', 'base_price' => 49.99, 'base_cost' => 25.00],
            ['name' => 'LED Desk Lamp Adjustable', 'unit' => 'pcs', 'base_price' => 39.99, 'base_cost' => 20.00],
            ['name' => 'Bluetooth Speaker Waterproof', 'unit' => 'pcs', 'base_price' => 59.99, 'base_cost' => 30.00],
            ['name' => 'Webcam HD 1080p', 'unit' => 'pcs', 'base_price' => 89.99, 'base_cost' => 50.00],
        ];

        $this->createProductsWithVariations($products, $electronicsCategory, $techBrand, $warehouses);
    }

    private function createClothingProducts($categories, $brands, $warehouses)
    {
        $clothingCategory = $categories->where('code', 'CLTH')->first();
        $styleBrand = $brands->where('name', 'StyleMax')->first();

        if ( ! $clothingCategory || ! $styleBrand) {
            return;
        }

        $products = [
            ['name' => 'Cotton T-Shirt Classic', 'unit' => 'pcs', 'base_price' => 19.99, 'base_cost' => 8.00],
            ['name' => 'Denim Jeans Slim Fit', 'unit' => 'pcs', 'base_price' => 59.99, 'base_cost' => 25.00],
            ['name' => 'Hoodie Pullover Unisex', 'unit' => 'pcs', 'base_price' => 39.99, 'base_cost' => 18.00],
            ['name' => 'Running Shoes Athletic', 'unit' => 'pair', 'base_price' => 89.99, 'base_cost' => 40.00],
            ['name' => 'Baseball Cap Adjustable', 'unit' => 'pcs', 'base_price' => 24.99, 'base_cost' => 10.00],
            ['name' => 'Winter Jacket Insulated', 'unit' => 'pcs', 'base_price' => 129.99, 'base_cost' => 60.00],
        ];

        $this->createProductsWithVariations($products, $clothingCategory, $styleBrand, $warehouses);
    }

    private function createFoodBeverageProducts($categories, $brands, $warehouses)
    {
        $foodCategory = $categories->where('code', 'FOOD')->first();
        $freshBrand = $brands->where('name', 'FreshChoice')->first();

        if ( ! $foodCategory || ! $freshBrand) {
            return;
        }

        $products = [
            ['name' => 'Organic Olive Oil Extra Virgin', 'unit' => 'bottle', 'base_price' => 12.99, 'base_cost' => 6.50],
            ['name' => 'Artisan Pasta Whole Wheat', 'unit' => 'box', 'base_price' => 4.99, 'base_cost' => 2.25],
            ['name' => 'Premium Coffee Beans', 'unit' => 'bag', 'base_price' => 18.99, 'base_cost' => 9.00],
            ['name' => 'Himalayan Pink Salt', 'unit' => 'jar', 'base_price' => 8.99, 'base_cost' => 4.00],
            ['name' => 'Organic Honey Raw', 'unit' => 'jar', 'base_price' => 15.99, 'base_cost' => 7.50],
            ['name' => 'Green Tea Premium Blend', 'unit' => 'box', 'base_price' => 9.99, 'base_cost' => 4.50],
        ];

        $this->createProductsWithVariations($products, $foodCategory, $freshBrand, $warehouses);
    }

    private function createHomeGardenProducts($categories, $brands, $warehouses)
    {
        $homeCategory = $categories->where('code', 'HOME')->first();
        $homeBrand = $brands->where('name', 'HomeComfort')->first();

        if ( ! $homeCategory || ! $homeBrand) {
            return;
        }

        $products = [
            ['name' => 'Ceramic Plant Pot Large', 'unit' => 'pcs', 'base_price' => 29.99, 'base_cost' => 15.00],
            ['name' => 'Garden Tool Set 5-Piece', 'unit' => 'set', 'base_price' => 49.99, 'base_cost' => 25.00],
            ['name' => 'LED String Lights Outdoor', 'unit' => 'set', 'base_price' => 34.99, 'base_cost' => 18.00],
            ['name' => 'Bamboo Cutting Board Set', 'unit' => 'set', 'base_price' => 39.99, 'base_cost' => 20.00],
            ['name' => 'Throw Pillow Decorative', 'unit' => 'pcs', 'base_price' => 19.99, 'base_cost' => 8.00],
            ['name' => 'Storage Basket Woven', 'unit' => 'pcs', 'base_price' => 24.99, 'base_cost' => 12.00],
        ];

        $this->createProductsWithVariations($products, $homeCategory, $homeBrand, $warehouses);
    }

    private function createSportsOutdoorProducts($categories, $brands, $warehouses)
    {
        $sportsCategory = $categories->where('code', 'SPRT')->first();
        $activeBrand = $brands->where('name', 'ActiveLife')->first();

        if ( ! $sportsCategory || ! $activeBrand) {
            return;
        }

        $products = [
            ['name' => 'Yoga Mat Non-Slip Premium', 'unit' => 'pcs', 'base_price' => 39.99, 'base_cost' => 18.00],
            ['name' => 'Resistance Bands Set', 'unit' => 'set', 'base_price' => 24.99, 'base_cost' => 12.00],
            ['name' => 'Water Bottle Insulated 32oz', 'unit' => 'pcs', 'base_price' => 29.99, 'base_cost' => 15.00],
            ['name' => 'Camping Tent 2-Person', 'unit' => 'pcs', 'base_price' => 149.99, 'base_cost' => 75.00],
            ['name' => 'Hiking Backpack 40L', 'unit' => 'pcs', 'base_price' => 89.99, 'base_cost' => 45.00],
            ['name' => 'Fitness Tracker Smart', 'unit' => 'pcs', 'base_price' => 79.99, 'base_cost' => 40.00],
        ];

        $this->createProductsWithVariations($products, $sportsCategory, $activeBrand, $warehouses);
    }

    private function createHealthBeautyProducts($categories, $brands, $warehouses)
    {
        $healthCategory = $categories->where('code', 'HLTH')->first();
        $pureBrand = $brands->where('name', 'PureWell')->first();

        if ( ! $healthCategory || ! $pureBrand) {
            return;
        }

        $products = [
            ['name' => 'Vitamin C Serum Organic', 'unit' => 'bottle', 'base_price' => 24.99, 'base_cost' => 12.00],
            ['name' => 'Face Moisturizer Daily', 'unit' => 'jar', 'base_price' => 19.99, 'base_cost' => 9.00],
            ['name' => 'Shampoo Sulfate-Free', 'unit' => 'bottle', 'base_price' => 14.99, 'base_cost' => 7.00],
            ['name' => 'Essential Oil Lavender', 'unit' => 'bottle', 'base_price' => 16.99, 'base_cost' => 8.00],
            ['name' => 'Multivitamin Gummies', 'unit' => 'bottle', 'base_price' => 22.99, 'base_cost' => 11.00],
            ['name' => 'Sunscreen SPF 50', 'unit' => 'tube', 'base_price' => 12.99, 'base_cost' => 6.00],
        ];

        $this->createProductsWithVariations($products, $healthCategory, $pureBrand, $warehouses);
    }

    private function createAutomotiveProducts($categories, $brands, $warehouses)
    {
        $autoCategory = $categories->where('code', 'AUTO')->first();
        $autoBrand = $brands->where('name', 'AutoMax')->first();

        if ( ! $autoCategory || ! $autoBrand) {
            return;
        }

        $products = [
            ['name' => 'Car Phone Mount Magnetic', 'unit' => 'pcs', 'base_price' => 19.99, 'base_cost' => 8.00],
            ['name' => 'Tire Pressure Gauge Digital', 'unit' => 'pcs', 'base_price' => 24.99, 'base_cost' => 12.00],
            ['name' => 'Car Charger Dual USB', 'unit' => 'pcs', 'base_price' => 14.99, 'base_cost' => 7.00],
            ['name' => 'Emergency Kit Roadside', 'unit' => 'kit', 'base_price' => 49.99, 'base_cost' => 25.00],
            ['name' => 'Car Wash Soap Premium', 'unit' => 'bottle', 'base_price' => 12.99, 'base_cost' => 6.00],
            ['name' => 'Floor Mats All-Weather', 'unit' => 'set', 'base_price' => 39.99, 'base_cost' => 20.00],
        ];

        $this->createProductsWithVariations($products, $autoCategory, $autoBrand, $warehouses);
    }

    private function createOfficeSuppliesProducts($categories, $brands, $warehouses)
    {
        $officeCategory = $categories->where('code', 'OFFC')->first();
        $officeBrand = $brands->where('name', 'OfficeElite')->first();

        if ( ! $officeCategory || ! $officeBrand) {
            return;
        }

        $products = [
            ['name' => 'Notebook Spiral Bound A4', 'unit' => 'pcs', 'base_price' => 4.99, 'base_cost' => 2.00],
            ['name' => 'Pen Set Ballpoint 10-Pack', 'unit' => 'pack', 'base_price' => 9.99, 'base_cost' => 4.50],
            ['name' => 'Stapler Heavy Duty', 'unit' => 'pcs', 'base_price' => 24.99, 'base_cost' => 12.00],
            ['name' => 'File Folders Manila 25-Pack', 'unit' => 'pack', 'base_price' => 14.99, 'base_cost' => 7.00],
            ['name' => 'Desk Organizer Bamboo', 'unit' => 'pcs', 'base_price' => 29.99, 'base_cost' => 15.00],
            ['name' => 'Whiteboard Markers 8-Pack', 'unit' => 'pack', 'base_price' => 12.99, 'base_cost' => 6.00],
        ];

        $this->createProductsWithVariations($products, $officeCategory, $officeBrand, $warehouses);
    }

    private function createJewelryProducts($categories, $brands, $warehouses)
    {
        $jewelryCategory = $categories->firstWhere('code', 'JWLR') ??
            Category::create(['name' => 'Jewelry & Accessories', 'code' => 'JWLR', 'description' => 'Jewelry and fashion accessories']);

        $jewelryBrand = $brands->firstWhere('name', 'LuxeGems') ??
            Brand::create(['name' => 'LuxeGems', 'description' => 'Premium jewelry and accessories', 'origin' => 'Switzerland']);

        $products = [
            ['name' => 'Sterling Silver Necklace', 'unit' => 'pcs', 'base_price' => 89.99, 'base_cost' => 35.00],
            ['name' => 'Gold Plated Earrings', 'unit' => 'pair', 'base_price' => 45.99, 'base_cost' => 18.00],
            ['name' => 'Diamond Ring 14K', 'unit' => 'pcs', 'base_price' => 299.99, 'base_cost' => 120.00],
            ['name' => 'Pearl Bracelet', 'unit' => 'pcs', 'base_price' => 79.99, 'base_cost' => 32.00],
            ['name' => 'Luxury Watch', 'unit' => 'pcs', 'base_price' => 199.99, 'base_cost' => 80.00],
            ['name' => 'Silver Cufflinks', 'unit' => 'pair', 'base_price' => 59.99, 'base_cost' => 24.00],
            ['name' => 'Gemstone Pendant', 'unit' => 'pcs', 'base_price' => 129.99, 'base_cost' => 52.00],
            ['name' => 'Wedding Band Set', 'unit' => 'set', 'base_price' => 399.99, 'base_cost' => 160.00],
        ];

        $this->createProductsWithVariations($products, $jewelryCategory, $jewelryBrand, $warehouses);
    }

    private function createBooksMediaProducts($categories, $brands, $warehouses)
    {
        $booksCategory = $categories->firstWhere('code', 'BOOK') ??
            Category::create(['name' => 'Books & Media', 'code' => 'BOOK', 'description' => 'Books, movies, and media']);

        $booksBrand = $brands->firstWhere('name', 'MediaMax') ??
            Brand::create(['name' => 'MediaMax', 'description' => 'Books and entertainment media', 'origin' => 'USA']);

        $products = [
            ['name' => 'Business Strategy Handbook', 'unit' => 'pcs', 'base_price' => 29.99, 'base_cost' => 12.00],
            ['name' => 'Programming Guide Python', 'unit' => 'pcs', 'base_price' => 39.99, 'base_cost' => 16.00],
            ['name' => 'Fiction Novel Bestseller', 'unit' => 'pcs', 'base_price' => 19.99, 'base_cost' => 8.00],
            ['name' => 'Educational DVD Set', 'unit' => 'set', 'base_price' => 49.99, 'base_cost' => 20.00],
            ['name' => 'Audio Book Collection', 'unit' => 'set', 'base_price' => 59.99, 'base_cost' => 24.00],
            ['name' => 'Magazine Subscription', 'unit' => 'year', 'base_price' => 24.99, 'base_cost' => 10.00],
            ['name' => 'Art & Design Book', 'unit' => 'pcs', 'base_price' => 34.99, 'base_cost' => 14.00],
            ['name' => 'Children Story Books', 'unit' => 'set', 'base_price' => 44.99, 'base_cost' => 18.00],
        ];

        $this->createProductsWithVariations($products, $booksCategory, $booksBrand, $warehouses);
    }

    private function createProductsWithVariations($products, $category, $brand, $warehouses)
    {
        foreach ($products as $index => $productData) {
            $productId = (string) Str::uuid();
            $code = strtoupper($category->code).str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

            // Create the product
            $product = [
                'id'                => $productId,
                'name'              => $productData['name'],
                'code'              => $code,
                'category_id'       => $category->id,
                'brand_id'          => $brand->id,
                'unit'              => $productData['unit'],
                'slug'              => Str::slug($productData['name']),
                'barcode_symbology' => 'C128',
                'tax_amount'        => fake()->randomElement([0, 5, 10, 15]), // Tax percentage
                'tax_type'          => fake()->randomElement([0, 1]), // 0 = percentage, 1 = fixed
                'status'            => true,
                'featured'          => fake()->boolean(25),
                'best'              => fake()->boolean(15),
                'hot'               => fake()->boolean(20),
                'description'       => fake()->sentence(10),
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            DB::table('products')->insert($product);

            // Create warehouse relationships with realistic pricing variations
            foreach ($warehouses as $warehouseIndex => $warehouse) {
                $priceVariation = 1 + (($warehouseIndex * 0.05) - 0.1); // ±10% price variation
                $price = $productData['base_price'] * $priceVariation;
                $cost = $productData['base_cost'] * $priceVariation;

                // Ensure prices don't exceed decimal(8,2) limit (999999.99)
                $price = min($price / 100, 99999.99); // Convert from cents and limit
                $cost = min($cost / 100, 99999.99); // Convert from cents and limit
                $oldPrice = min($price * 1.15, 99999.99);

                ProductWarehouse::create([
                    'product_id'    => $productId,
                    'warehouse_id'  => $warehouse->id,
                    'qty'           => fake()->numberBetween(20, 150),
                    'price'         => round($price, 2), // Store as decimal
                    'cost'          => round($cost, 2), // Store as decimal
                    'old_price'     => round($oldPrice, 2), // Store as decimal
                    'stock_alert'   => fake()->numberBetween(10, 25),
                    'is_discount'   => fake()->boolean(20),
                    'discount_date' => fake()->boolean(20) ? fake()->dateTimeBetween('now', '+30 days') : null,
                    'is_ecommerce'  => fake()->boolean(60), // 60% chance for e-commerce
                ]);
            }
        }
    }
}
