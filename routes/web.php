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
use App\Livewire\Adjustment\Create as CreateAdjustment;
use App\Livewire\Adjustment\Edit as EditAdjustment;
use App\Livewire\Adjustment\Index as AdjustmentIndex;
use App\Livewire\Backup\Index as BackupIndex;
use App\Livewire\Brands\Index as BrandIndex;
use App\Livewire\CashRegister\Index as CashRegisterIndex;
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\Currency\Index as CurrencyIndex;
use App\Livewire\CustomerGroup\Index as CustomerGroupIndex;
use App\Livewire\Customers\Details as CustomerDetails;
use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Dashboard;
use App\Livewire\Email\Index as EmailIndex;
use App\Livewire\Expense\Index as ExpensesIndex;
use App\Livewire\ExpenseCategories\Index as ExpenseCategoriesIndex;
use App\Livewire\Installation\StepManager;
use App\Livewire\Language\EditTranslation;
use App\Livewire\Language\Index as LanguageIndex;
use App\Livewire\Notification\Index as NotificationIndex;
use App\Livewire\Permission\Index as PermissionsIndex;
use App\Livewire\Pos\Index as PosIndex;
use App\Livewire\Printer\Index as PrinterIndex;
use App\Livewire\Products\Barcode as BarcodeIndex;
use App\Livewire\Products\Edit as EditProduct;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Purchase\Create as CreatePurchase;
use App\Livewire\Purchase\Edit as EditPurchase;
use App\Livewire\Purchase\Index as PurchasesIndex;
use App\Livewire\Purchase\Invoice as PurchaseInvoice;
use App\Livewire\PurchaseReturn\Create as CreatePurchaseReturn;
use App\Livewire\PurchaseReturn\Edit as EditPurchaseReturn;
use App\Livewire\Quotations\Create as CreateQuotation;
use App\Livewire\Quotations\Edit as EditQuotation;
use App\Livewire\Quotations\Index as QuotationsIndex;
use App\Livewire\Reports\CustomersReport;
use App\Livewire\Reports\PaymentsReport;
use App\Livewire\Reports\ProfitLossReport;
use App\Livewire\Reports\PurchasesReport;
use App\Livewire\Reports\PurchasesReturnReport;
use App\Livewire\Reports\SalesReport;
use App\Livewire\Reports\SalesReturnReport;
use App\Livewire\Reports\StockAlertReport;
use App\Livewire\Role\Index as RolesIndex;
use App\Livewire\SaleReturn\Create as CreateSaleReturn;
use App\Livewire\SaleReturn\Edit as EditSaleReturn;
use App\Livewire\Sales\Create as CreateSale;
use App\Livewire\Sales\Edit as EditSale;
use App\Livewire\Sales\Index as SalesIndex;
use App\Livewire\Sales\Invoice as SaleInvoice;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Settings\InvoiceTheme;
use App\Livewire\Shipping\Index as ShippingIndex;
use App\Livewire\Suppliers\Details as SupplierDetails;
use App\Livewire\Suppliers\Index as SuppliersIndex;
use App\Livewire\Transfer\Index as TransferIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Profile as ProfileIndex;
use App\Livewire\Warehouses\Index as WarehouseIndex;
use Illuminate\Support\Facades\Route;
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

require __DIR__ . '/auth.php';

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

// Installation Routes
Route::prefix('install')->name('installation.')->group(function () {
    Route::livewire('/', StepManager::class)->name('index');
});

Route::get('/', fn () => redirect()->route('dashboard'));

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'auth.session', 'role:admin']], function () {
    // Change lang
    Route::get('/lang/{lang}', [HomeController::class, 'changeLanguage'])->name('changelanguage');

    Route::livewire('/dashboard', Dashboard::class)->name('dashboard');

    // POS
    Route::livewire('/pos', PosIndex::class)->name('pos.index');

    // Product Adjustment
    Route::prefix('adjustments')->name('adjustments.')->group(function(){
        Route::livewire('/', AdjustmentIndex::class)->name('index');
        Route::livewire('/create', CreateAdjustment::class)->name('create');
        Route::livewire('/update/{id}', EditAdjustment::class)->name('edit');
    });

    // Database Sync (Desktop Mode)
    Route::livewire('/database-sync', App\Livewire\Admin\DatabaseSync::class)->name('admin.database-sync');
});

// Desktop-specific routes (only available in desktop mode)
Route::prefix('desktop')->name('desktop.')->group(function () {
    Route::prefix('shortcut')->name('shortcut.')->group(function(){
        Route::post('/execute', [App\Native\Controllers\DesktopController::class, 'executeShortcut'])->name('execute');
        Route::post('/check', [App\Native\Controllers\DesktopController::class, 'checkShortcut'])->name('check');
    });
    Route::prefix('shortcuts')->name('shortcuts.')->group(function(){
        Route::get('/', [App\Native\Controllers\DesktopController::class, 'getShortcuts'])->name('index');
        Route::post('/execute', [App\Native\Controllers\DesktopController::class, 'executeShortcut'])->name('execute');
        Route::post('/register', [App\Native\Controllers\DesktopController::class, 'registerShortcuts'])->name('register');
    });
    Route::get('/status', [App\Native\Controllers\DesktopController::class, 'getDesktopStatus'])->name('status');
    Route::post('/action', [App\Native\Controllers\DesktopController::class, 'handleAction'])->name('action');
    Route::post('/actions', [App\Native\Controllers\DesktopController::class, 'handleAction'])->name('actions');

    // Error logging routes
    Route::livewire('/errors', App\Livewire\Admin\DesktopErrorLog::class)->name('errors.index');
    Route::post('/errors/js', [App\Native\Controllers\DesktopController::class, 'handleJavaScriptError'])->name('errors.js');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'auth.session', 'role:admin']], function () {
    // Currencies
    Route::livewire('/currencies', CurrencyIndex::class)->name('currencies.index');

    // Charts
    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])->name('sales-purchases.chart');
    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])->name('current-month.chart');
    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])->name('payment-flow.chart');

    // Expense Category
    Route::livewire('/expense-categories', ExpenseCategoriesIndex::class)->name('expense-categories.index');

    // Expense
    Route::livewire('/expenses', ExpensesIndex::class)->name('expenses.index');

    // Customers
    Route::livewire('/customers', CustomersIndex::class)->name('customers.index');
    Route::livewire('/customer/details/{id}', CustomerDetails::class)->name('customer.details');
    Route::livewire('/customergroup', CustomerGroupIndex::class)->name('customer-group.index');

    // Suppliers
    Route::livewire('/suppliers', SuppliersIndex::class)->name('suppliers.index');
    Route::livewire('/supplier/details/{id}', SupplierDetails::class)->name('supplier.details');

    // Warehouses
    Route::livewire('/warehouses', WarehouseIndex::class)->name('warehouses.index');

    // Quotations
    Route::prefix('quotations')->name('quotations.')->group(function(){
        Route::livewire('/', QuotationsIndex::class)->name('index');
        Route::get('/pdf/{id}', [ExportController::class, 'quotation'])->name('pdf');
    });
     Route::prefix('quotation')->name('quotation.')->group(function(){
        Route::livewire('/create', CreateQuotation::class)->name('create');
        Route::livewire('/update/{id}', EditQuotation::class)->name('edit');
        Route::get('/mail/{quotation}', SendQuotationEmailController::class)->name('email');
    });

    // Purchases
    Route::prefix('purchases')->name('purchases.')->group(function(){
        Route::livewire('/purchases', PurchasesIndex::class)->name('index');
        Route::get('/purchases/pdf/{id}', [ExportController::class, 'purchase'])->name('pdf');
    }); 
    Route::prefix('purchase')->name('purchase.')->group(function(){
        Route::livewire('/purchase/create', CreatePurchase::class)->name('create');
        Route::livewire('/purchase/edit/{id}', EditPurchase::class)->name('edit');
        Route::livewire('/purchase/update/{id}', EditPurchase::class)->name('edit');
        Route::livewire('/purchase/print/{id}', PurchaseInvoice::class)->name('invoice');
    });

    // Sales Form Quotation
    Route::get('/quotation-sales/{quotation}', QuotationSalesController::class)->name('quotation-sales.create');

    // Purchase Returns Payments
    // Route::get('/purchase-return-payments/{purchaseReturn_id}', [PurchaseReturnPaymentsController::class, 'index'])->name('purchase-return-payments.index');
    // Route::get('/purchase-return-payments/{purchase_return_id}/create', [PurchaseReturnPaymentsController::class, 'create'])->name('purchase-return-payments.create');
    // Route::post('/purchase-return-payments/store', [PurchaseReturnPaymentsController::class, 'store'])->name('purchase-return-payments.store');
    // Route::get('/purchase-return-payments/{purchase_return_id}/edit/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'edit'])->name('purchase-return-payments.edit');
    // Route::patch('/purchase-return-payments/update/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'update'])->name('purchase-return-payments.update');
    // Route::delete('/purchase-return-payments/destroy/{purchaseReturnPayment}', [PurchaseReturnPaymentsController::class, 'destroy'])->name('purchase-return-payments.destroy');

    // Reports
    Route::livewire('/customers-report', CustomersReport::class)->name('customers-report.index');
    Route::livewire('/profit-loss-report', ProfitLossReport::class)->name('profit-loss-report.index');
    Route::livewire('/sales-report', SalesReport::class)->name('sales-report.index');
    Route::livewire('/sales-return-report', SalesReturnReport::class)->name('sales-return-report.index');
    Route::livewire('/purchases-report', PurchasesReport::class)->name('purchases-report.index');
    Route::livewire('/purchases-return-report', PurchasesReturnReport::class)->name('purchases-return-report.index');
    Route::livewire('/payments-report', PaymentsReport::class)->name('payments-report.index');
    Route::livewire('/stock-alert-report', StockAlertReport::class)->name('stock-alert-report.index');

    // Sales
    Route::prefix('sales')->name('sales.')->group(function(){
        Route::livewire('/', SalesIndex::class)->name('index');
        Route::get('/pdf/{id}', [ExportController::class, 'sale'])->name('pdf');
        Route::get('/pos/pdf/{id}', [ExportController::class, 'salePos'])->name('pos.pdf');
    });

    Route::prefix('sale')->name('sale.')->group(function(){
        Route::livewire('/print/{id}', SaleInvoice::class)->name('invoice');
        Route::livewire('/create', CreateSale::class)->name('create');
        Route::livewire('/update/{id}', EditSale::class)->name('edit');
    });

    // User Profile
    Route::livewire('/user/profile', ProfileIndex::class)->name('profile.index');

    // Users
    Route::livewire('/users', UsersIndex::class)->name('users.index');

    // Roles
    Route::livewire('/roles', RolesIndex::class)->name('roles.index');

    // Permissions
    Route::livewire('/permissions', PermissionsIndex::class)->name('permissions.index');

    // Logs
    // Route::livewire('/logs', 'utils.system-logs')->name('logs.index');

    // Integrations
    Route::get('/integrations', IntegrationController::class)->name('integrations.index');

    // Cash Register
    Route::livewire('/cash-registers', CashRegisterIndex::class)->name('cash-register.index');

    // Brands
    Route::livewire('/brands', BrandIndex::class)->name('brands.index');

    // Product Categories
    Route::livewire('/product-categories', CategoryIndex::class)->name('product-categories.index');

    // Products
    Route::prefix('products')->name('products.')->group(function(){
        Route::livewire('/', ProductsIndex::class)->name('index');
        Route::livewire('/print-barcode', BarcodeIndex::class)->name('barcode-print');
    });
    Route::livewire('/product/edit/{id}', EditProduct::class)->name('product.edit');

    // Purchase Payments
    // Route::get('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'index'])->name('purchase-payments.index');
    // Route::get('/purchase-payments/{purchase_id}/create', [PurchasePaymentsController::class, 'create'])->name('purchase-payments.create');
    // Route::post('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'store'])->name('purchase-payments.store');
    // Route::get('/purchase-payments/{purchase_id}/edit/{purchasePayment}', [PurchasePaymentsController::class, 'edit'])->name('purchase-payments.edit');
    // Route::patch('/purchase-payments/update/{purchasePayment}', [PurchasePaymentsController::class, 'update'])->name('purchase-payments.update');
    // Route::delete('/purchase-payments/destroy/{purchasePayment}', [PurchasePaymentsController::class, 'destroy'])->name('purchase-payments.destroy');

    // Purchase Returns
    Route::prefix('purchase-returns')->name('purchase-returns.')->group(function(){
        Route::resource('/', PurchasesReturnController::class)->except(['create', 'edit', 'store', 'update']);
        Route::livewire('/create', CreatePurchaseReturn::class)->name('create');
        Route::livewire('/{id}/edit', EditPurchaseReturn::class)->name('edit');
        Route::get('/pdf/{id}', [ExportController::class, 'purchaseReturns'])->name('pdf');
    });    

    // Generate Sale Returns PDF
    Route::prefix('sale-returns')->name('sale-returns.')->group(function(){
        Route::resource('/', SalesReturnController::class)->except(['create', 'edit', 'store', 'update']);
        Route::livewire('/create', CreateSaleReturn::class)->name('create');
        Route::livewire('/{id}/edit', EditSaleReturn::class)->name('edit');
        Route::get('/pdf/{id}', [ExportController::class, 'saleReturns'])->name('pdf');
    });  

    // Transfers
    Route::livewire('/transfers', TransferIndex::class)->name('tranfers.index');

    // Language Settings
    Route::livewire('/languages', LanguageIndex::class)->name('languages.index');
    Route::livewire('/translation/{id}', EditTranslation::class)->name('languages.translation');

    // General Settings
    Route::livewire('/settings', SettingsIndex::class)->name('settings.index');

    // Invoices Theme
    Route::livewire('/invoices-theme', InvoiceTheme::class)->name('invoices.index');

    // Backup
    Route::livewire('/backup', BackupIndex::class)->name('backup.index');

    // Shipping
    Route::livewire('/shipping', ShippingIndex::class)->name('shipping.index');

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function(){
        Route::livewire('/dashboard', App\Livewire\Analytics\AnalyticsDashboard::class)->name('dashboard');
        Route::livewire('/product', App\Livewire\Analytics\ProductAnalytics::class)->name('product');
        Route::livewire('/revenue', App\Livewire\Analytics\RevenueReports::class)->name('revenue');
    });

    // Finance
    Route::prefix('finance')->name('finance.')->group(function(){
        Route::livewire('/dashboard', App\Livewire\Finance\FinancialDashboard::class)->name('dashboard');
        Route::livewire('/kpi', App\Livewire\Finance\KpiTracking::class)->name('kpi');
        Route::livewire('/breakeven', App\Livewire\Finance\BreakEvenAnalysis::class)->name('breakeven');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function(){
        Route::livewire('/bell', App\Livewire\Notifications\NotificationBell::class)->name('bell');
        Route::livewire('/manager', App\Livewire\Notifications\NotificationManager::class)->name('manager');
    });

    // Notification (Legacy)
    Route::livewire('/notification', NotificationIndex::class)->name('notification');

    // Email Settings
    Route::livewire('/email-settings', EmailIndex::class)->name('email-settings');

    // Printers
    Route::livewire('/printers', PrinterIndex::class)->name('printers.index');
});

Livewire::setUpdateRoute(function ($handle, $path) {
    return Route::post($path, $handle);
});
