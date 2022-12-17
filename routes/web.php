<?php

declare(strict_types=1);

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasesReturnController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationSalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentsController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Controllers\PurchaseReturnPaymentsController;
use App\Http\Controllers\SendQuotationEmailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::group(['middleware' => 'auth'], function () {
    // change lang
    Route::get('/lang/{lang}', [HomeController::class, 'changeLanguage'])->name('changelanguage');

    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // Charts
    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])->name('sales-purchases.chart');
    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])->name('current-month.chart');
    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])->name('payment-flow.chart');

    //Product Adjustment
    Route::resource('adjustments', AdjustmentController::class);

    //Currencies
    Route::get('currencies', CurrencyController::class)->name('currencies.index');

    //Expense Category
    Route::get('expense-categories', ExpenseCategoriesController::class)->name('expense-categories.index');

    //Expense
    Route::get('expenses', ExpenseController::class)->name('expenses.index');

    //Customers
    Route::get('customers', CustomersController::class)->name('customers.index');
    Route::get('customer/details/{customer}', [CustomersController::class, 'details'])->name('customer.details');

    //Suppliers
    Route::get('suppliers', SuppliersController::class)->name('suppliers.index');
    Route::get('supplier/details/{supplier}', [SuppliersController::class, 'details'])->name('supplier.details');

    //Warehouses
    Route::get('warehouses', WarehouseController::class)->name('warehouses.index');

    //Brands
    Route::get('brands', BrandsController::class)->name('brands.index');

    //Print Barcode
    Route::get('/products/print-barcode', [BarcodeController::class, 'printBarcode'])->name('barcode.print');

    //Product
    Route::get('products', ProductController::class)->name('products.index');

    //Product Category
    Route::get('product-categories', CategoriesController::class)->name('product-categories.index');

    //Generate Quotation PDF
    Route::get('/quotations/pdf/{id}', [ExportController::class, 'quotation'])->name('quotations.pdf');

    //Send Quotation Mail
    Route::get('/quotation/mail/{quotation}', SendQuotationEmailController::class)->name('quotation.email');

    //Sales Form Quotation
    Route::get('quotation-sales/{quotation]', QuotationSalesController::class)->name('quotation-sales.create');

    //Quotations
    Route::resource('quotations', QuotationController::class);

    //Generate Purchase PDF
    Route::get('/purchases/pdf/{id}', [ExportController::class, 'purchase'])->name('purchases.pdf');

    //Purchases
    Route::resource('purchases', PurchaseController::class);

    //Purchase Payments
    Route::get('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'index'])->name('purchase-payments.index');
    Route::get('/purchase-payments/{purchase_id}/create', [PurchasePaymentsController::class, 'create'])->name('purchase-payments.create');
    Route::post('/purchase-payments/{purchase_id}', [PurchasePaymentsController::class, 'store'])->name('purchase-payments.store');
    Route::get('/purchase-payments/{purchase_id}/edit/{purchasePayment}', [PurchasePaymentsController::class, 'edit'])->name('purchase-payments.edit');
    Route::patch('/purchase-payments/update/{purchasePayment}', [PurchasePaymentsController::class, 'update'])->name('purchase-payments.update');
    Route::delete('/purchase-payments/destroy/{purchasePayment}', [PurchasePaymentsController::class, 'destroy'])->name('purchase-payments.destroy');

    //Generate Purchase Return PDF
    Route::get('/purchase-returns/pdf/{id}', [ExportController::class, 'purchaseReturns'])->name('purchase-returns.pdf');

    //Purchase Returns
    Route::resource('purchase-returns', PurchasesReturnController::class);

    //Purchase Returns Payments
    Route::get('/purchase-return-payments/{purchaseReturn_id}', [PurchaseReturnPaymentsController::class, 'index'])->name('purchase-return-payments.index');

    Route::get('/purchase-return-payments/{purchase_return_id}/create',  [PurchaseReturnPaymentsController::class, 'create'])
        ->name('purchase-return-payments.create');
    Route::post('/purchase-return-payments/store',  [PurchaseReturnPaymentsController::class, 'store'])
        ->name('purchase-return-payments.store');
    Route::get('/purchase-return-payments/{purchase_return_id}/edit/{purchaseReturnPayment}',  [PurchaseReturnPaymentsController::class, 'edit'])
        ->name('purchase-return-payments.edit');
    Route::patch('/purchase-return-payments/update/{purchaseReturnPayment}',  [PurchaseReturnPaymentsController::class, 'update'])
        ->name('purchase-return-payments.update');
    Route::delete('/purchase-return-payments/destroy/{purchaseReturnPayment}',  [PurchaseReturnPaymentsController::class, 'destroy'])
        ->name('purchase-return-payments.destroy');

    //Profit Loss Report
    Route::get('/profit-loss-report', [ReportsController::class, 'profitLossReport'])->name('profit-loss-report.index');
    //Payments Report
    Route::get('/payments-report', [ReportsController::class, 'paymentsReport'])->name('payments-report.index');
    //Sales Report
    Route::get('/sales-report', [ReportsController::class, 'salesReport'])->name('sales-report.index');
    //Purchases Report
    Route::get('/purchases-report', [ReportsController::class, 'purchasesReport'])->name('purchases-report.index');
    //Sales Return Report
    Route::get('/sales-return-report', [ReportsController::class, 'salesReturnReport'])->name('sales-return-report.index');
    //Purchases Return Report
    Route::get('/purchases-return-report', [ReportsController::class, 'purchasesReturnReport'])->name('purchases-return-report.index');

    //POS
    Route::get('/pos', [PosController::class, 'index'])->name('app.pos.index');
    Route::post('/app/pos', [PosController::class, 'store'])->name('app.pos.store');

    //Generate Sale PDF
    Route::get('/sales/pdf/{id}', [ExportController::class, 'sale'])->name('sales.pdf');
    Route::get('/sales/pos/pdf/{id}', [ExportController::class, 'salePos'])->name('sales.pos.pdf');

    //Sales
    Route::resource('sales', SaleController::class);

    //Payments
    Route::get('/sale-payments/{sale_id}', [SalePaymentsController::class, 'index'])->name('sale-payments.index');
    Route::get('/sale-payments/{sale_id}/create', [SalePaymentsController::class, 'create'])->name('sale-payments.create');
    Route::post('/sale-payments/store', [SalePaymentsController::class, 'store'])->name('sale-payments.store');
    Route::get('/sale-payments/{sale_id}/edit/{salePayment}', [SalePaymentsController::class, 'edit'])->name('sale-payments.edit');
    Route::patch('/sale-payments/update/{salePayment}', [SalePaymentsController::class, 'update'])->name('sale-payments.update');
    Route::delete('/sale-payments/destroy/{salePayment}', [SalePaymentsController::class, 'destroy'])->name('sale-payments.destroy');

    //Generate Sale Returns PDF
    Route::get('/sale-returns/pdf/{id}', [ExportController::class, 'saleReturns'])->name('sale-returns.pdf');

    //Sale Returns
    Route::resource('sale-returns', SalesReturnController::class);

    //Payments
    Route::get('/sale-return-payments/{sale_return_id}', 'SaleReturnPaymentsController@index')
        ->name('sale-return-payments.index');
    Route::get('/sale-return-payments/{sale_return_id}/create', 'SaleReturnPaymentsController@create')
        ->name('sale-return-payments.create');
    Route::post('/sale-return-payments/store', 'SaleReturnPaymentsController@store')
        ->name('sale-return-payments.store');
    Route::get('/sale-return-payments/{sale_return_id}/edit/{saleReturnPayment}', 'SaleReturnPaymentsController@edit')
        ->name('sale-return-payments.edit');
    Route::patch('/sale-return-payments/update/{saleReturnPayment}', 'SaleReturnPaymentsController@update')
        ->name('sale-return-payments.update');
    Route::delete('/sale-return-payments/destroy/{saleReturnPayment}', 'SaleReturnPaymentsController@destroy')
        ->name('sale-return-payments.destroy');

    //User Profile
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('profile.index');

    //Users
    Route::resource('users', UsersController::class);

    //Roles
    Route::resource('roles', RoleController::class)->except(['show']);

    // Permissions
    Route::resource('permissions', PermissionController::class)->except(['store', 'update', 'destroy']);

    //Mail Settings
    Route::patch('/settings/smtp', [SettingController::class, 'updateSmtp'])->name('settings.smtp.update');

    //General Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
});
