<?php

declare(strict_types=1);

use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Controllers\PurchaseReturnPaymentsController;
use App\Http\Controllers\PurchasesReturnController;
use App\Http\Controllers\QuotationSalesController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SendQuotationEmailController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Users\Profile as ProfileIndex;
use App\Livewire\Adjustment\Index as AdjustmentIndex;
use App\Livewire\Adjustment\Create as CreateAdjustment;
use App\Livewire\Adjustment\Edit as EditAdjustment;
use App\Livewire\Backup\Index as BackupIndex;
use App\Livewire\Brands\Index as BrandIndex;
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\CashRegister\Index as CashRegisterIndex;
use App\Livewire\Currency\Index as CurrencyIndex;
use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Customers\Details as CustomerDetails;
use App\Livewire\CustomerGroup\Index  as CustomerGroupIndex;
use App\Livewire\Email\Index as EmailIndex;
use App\Livewire\Expense\Index as ExpensesIndex;
use App\Livewire\ExpenseCategories\Index as ExpenseCategoriesIndex;
use App\Livewire\Language\Index as LanguageIndex;
use App\Livewire\Language\EditTranslation;
use App\Livewire\Permission\Index as PermissionsIndex;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Products\Edit as EditProduct;
use App\Livewire\Products\Barcode as BarcodeIndex;
use App\Livewire\Role\Index as RolesIndex;
use App\Livewire\Suppliers\Index  as SuppliersIndex;
use App\Livewire\Suppliers\Details  as SupplierDetails;
use App\Livewire\Warehouses\Index as WarehouseIndex;
use App\Livewire\Shipping\Index as ShippingIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Notification\Index as NotificationIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Printer\Index as PrinterIndex;
use App\Livewire\Purchase\Index as PurchasesIndex;
use App\Livewire\Purchase\Create as CreatePurchase;
use App\Livewire\Purchase\Edit as EditPurchase;
use App\Livewire\Purchase\Invoice as PurchaseInvoice;
use App\Livewire\Quotations\Index as QuotationsIndex;
use App\Livewire\Quotations\Create as CreateQuotation;
use App\Livewire\Quotations\Edit as EditQuotation;
use App\Livewire\Transfer\Index as TransferIndex;
use App\Livewire\Sales\Index as SalesIndex;
use App\Livewire\Sales\Invoice as SaleInvoice;
use App\Livewire\Sales\Create as CreateSale;
use App\Livewire\Sales\Edit as EditSale;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Settings\InvoiceTheme;
use App\Livewire\Utils\Logs as LogIndex;
use App\Livewire\Reports\CustomersReport;
use App\Livewire\Reports\ProfitLossReport;
use App\Livewire\Reports\PaymentsReport;
use App\Livewire\Reports\SalesReport;
use App\Livewire\Reports\PurchasesReport;
use App\Livewire\Reports\SalesReturnReport;
use App\Livewire\Reports\PurchasesReturnReport;
use App\Livewire\Reports\StockAlertReport;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::view('profile', 'profile')
    ->middleware(['auth.basic'])
    ->name('profile');

// Route::get('/docs/{file?}', [DocsController::class, 'index'])->name('docs.index');

// Route::get('/docs', function() {
//     View::addExtension('html', 'php'); // allows .html
//     return view('docs.index'); // loads /public/docs/index.html
// });

// Route::get('/docs', function () {
//     if ($file != 'index') {
//         $file = $file.'/index';
//     }

//     return File::get(public_path().'/docs/'.$file.'.html');
// });

// admin prefix group

Route::get('/', fn() => redirect()->route('dashboard'));

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'auth.session', 'role:admin']], function () {
    // Change lang
    Route::get('/lang/{lang}', [HomeController::class, 'changeLanguage'])->name('changelanguage');

    Route::get('/', Dashboard::class)->name('dashboard');

    // POS
    Route::get('/pos', PosIndex::class)->name('pos.index');

    // Product Adjustment
    Route::get('/adjustments', AdjustmentIndex::class)->name('adjustments.index');
    Route::get('/adjustment/create', CreateAdjustment::class)->name('adjustments.create');
    Route::get('/adjustment/update/{id}', EditAdjustment::class)->name('adjustments.edit');

    // Currencies
    Route::get('/currencies', CurrencyIndex::class)->name('currencies.index');

    // Charts
    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])->name('sales-purchases.chart');
    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])->name('current-month.chart');
    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])->name('payment-flow.chart');

    // Expense Category
    Route::get('/expense-categories', ExpenseCategoriesIndex::class)->name('expense-categories.index');

    // Expense
    Route::get('/expenses', ExpensesIndex::class)->name('expenses.index');

    // Customers
    Route::get('/customers', CustomersIndex::class)->name('customers.index');
    Route::get('/customer/details/{id}', CustomerDetails::class)->name('customer.details');
    Route::get('/customergroup', CustomerGroupIndex::class)->name('customer-group.index');

    // Suppliers
    Route::get('/suppliers', SuppliersIndex::class)->name('suppliers.index');
    Route::get('/supplier/details/{id}', SupplierDetails::class)->name('supplier.details');

    // Warehouses
    Route::get('/warehouses', WarehouseIndex::class)->name('warehouses.index');

    // Quotations
    Route::get('/quotations', QuotationsIndex::class)->name('quotations.index');
    Route::get('/quotation/create', CreateQuotation::class)->name('quotation.create');
    Route::get('/quotation/update/{id}', EditQuotation::class)->name('quotation.edit');
    Route::get('/quotations/pdf/{id}', [ExportController::class, 'quotation'])->name('quotations.pdf');
    Route::get('/quotation/mail/{quotation}', SendQuotationEmailController::class)->name('quotation.email');

    // Purchases
    Route::get('/purchases', PurchasesIndex::class)->name('purchases.index');
    Route::get('/purchase/create', CreatePurchase::class)->name('purchase.create');
    Route::get('/purchase/update/{id}', EditPurchase::class)->name('purchase.edit');
    Route::get('/purchase/print/{id}', PurchaseInvoice::class)->name('purchase.invoice');
    Route::get('/purchases/pdf/{id}', [ExportController::class, 'purchase'])->name('purchases.pdf');

    // Sales Form Quotation
    Route::get('/quotation-sales/{quotation}', QuotationSalesController::class)->name('quotation-sales.create');

    // Purchase Returns Payments
    Route::get('/purchase-return-payments/{purchaseReturn_id}', [PurchaseReturnPaymentsController::class, 'index'])->name('purchase-return-payments.index');
    Route::get('/purchase-return-payments/{purchase_return_id}/create', [PurchaseReturnPaymentsController::class, 'create'])->name('purchase-return-payments.create');
    Route::post('/purchase-return-payments/store', [PurchaseReturnPaymentsController::class, 'store'])->name('purchase-return-payments.store');
    Route::get('/purchase-return-payments/{purchase_return_id}/edit/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'edit'])->name('purchase-return-payments.edit');
    Route::patch('/purchase-return-payments/update/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'update'])->name('purchase-return-payments.update');
    Route::delete('/purchase-return-payments/destroy/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'destroy'])->name('purchase-return-payments.destroy');

    // Reports
    Route::get('/customers-report', CustomersReport::class)->name('customers-report.index');
    Route::get('/profit-loss-report', ProfitLossReport::class)->name('profit-loss-report.index');
    Route::get('/sales-report', SalesReport::class)->name('sales-report.index');
    Route::get('/sales-return-report', SalesReturnReport::class)->name('sales-return-report.index');
    Route::get('/purchases-report', PurchasesReport::class)->name('purchases-report.index');
    Route::get('/purchases-return-report', PurchasesReturnReport::class)->name('purchases-return-report.index');
    Route::get('/payments-report', PaymentsReport::class)->name('payments-report.index');
    Route::get('/stock-alert-report', StockAlertReport::class)->name('stock-alert-report.index');

    // Sales
    Route::get('/sales', SalesIndex::class)->name('sales.index');
    Route::get('/sale/print/{id}', SaleInvoice::class)->name('sale.invoice');
    Route::get('/sale/create', CreateSale::class)->name('sale.create');
    Route::get('/sale/update/{id}', EditSale::class)->name('sale.edit');
    Route::get('/sales/pdf/{id}', [ExportController::class, 'sale'])->name('sales.pdf');
    Route::get('/sales/pos/pdf/{id}', [ExportController::class, 'salePos'])->name('sales.pos.pdf');

    // User Profile
    Route::get('/user/profile', ProfileIndex::class)->name('profile.index');

    // Users
    Route::get('/users', UsersIndex::class)->name('users.index');

    // Roles
    Route::get('/roles', RolesIndex::class)->name('roles.index');

    // Permissions
    Route::get('/permissions', PermissionsIndex::class)->name('permissions.index');

    // Logs
    Route::get('/logs', LogIndex::class)->name('logs.index');

    // Integrations
    Route::get('/integrations', IntegrationController::class)->name('integrations.index');

    // Cash Register
    Route::get('/cash-registers', CashRegisterIndex::class)->name('cash-register.index');

    // Brands
    Route::get('/brands', BrandIndex::class)->name('brands.index');

    // Product Categories
    Route::get('/product-categories', CategoryIndex::class)->name('product-categories.index');

    // Products
    Route::get('/products', ProductsIndex::class)->name('products.index');
    Route::get('/product/edit/{id}', EditProduct::class)->name('product.edit');
    Route::get('/products/print-barcode', BarcodeIndex::class)->name('products.barcode-print');

    // Purchase Payments
    Route::get('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'index'])->name('purchase-payments.index');
    Route::get('/purchase-payments/{purchase_id}/create', [PurchasePaymentsController::class, 'create'])->name('purchase-payments.create');
    Route::post('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'store'])->name('purchase-payments.store');
    Route::get('/purchase-payments/{purchase_id}/edit/{purchasePayment}', [PurchasePaymentsController::class, 'edit'])->name('purchase-payments.edit');
    Route::patch('/purchase-payments/update/{purchasePayment}', [PurchasePaymentsController::class, 'update'])->name('purchase-payments.update');
    Route::delete('/purchase-payments/destroy/{purchasePayment}', [PurchasePaymentsController::class, 'destroy'])->name('purchase-payments.destroy');

    // Purchase Returns
    Route::resource('/purchase-returns', PurchasesReturnController::class);
    Route::get('/purchase-returns/pdf/{id}', [ExportController::class, 'purchaseReturns'])->name('purchase-returns.pdf');

    // Generate Sale Returns PDF
    Route::resource('/sale-returns', SalesReturnController::class);
    Route::get('/sale-returns/pdf/{id}', [ExportController::class, 'saleReturns'])->name('sale-returns.pdf');

    // Transfers
    Route::get('/transfers', TransferIndex::class)->name('tranfers.index');

    // Language Settings
    Route::get('/languages', LanguageIndex::class)->name('languages.index');
    Route::get('/translation/{id}', EditTranslation::class)->name('languages.translation');

    // General Settings
    Route::get('/settings', SettingsIndex::class)->name('settings.index');

    // Invoices Theme
    Route::get('/invoices-theme', InvoiceTheme::class)->name('invoices.index');

    // Backup
    Route::get('/backup', BackupIndex::class)->name('backup.index');

    // Shipping
    Route::get('/shipping', ShippingIndex::class)->name('shipping.index');

    // Notification
    Route::get('/notification', NotificationIndex::class)->name('notification');

    // Email Settings
    Route::get('/email-settings', EmailIndex::class)->name('email-settings');

    // Printers
    Route::get('/printers', PrinterIndex::class)->name('printers.index');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});
