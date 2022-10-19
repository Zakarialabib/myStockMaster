<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentsController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SendQuotationEmailController;
use App\Http\Controllers\QuotationSalesController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\SalePaymentsController;
use App\Http\Controllers\WarehouseController;
use Barryvdh\DomPDF\Facade\Pdf;

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

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::group(['middleware' => 'auth'], function () {
   
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])->name('sales-purchases.chart');
    
    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])->name('current-month.chart');
    
    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])->name('payment-flow.chart');

    //Product Adjustment
    Route::resource('adjustments', AdjustmentController::class);

    //Currencies
    Route::resource('currencies', CurrencyController::class)->except('show'); 
    
    //Expense Category
    Route::resource('expense-categories', ExpenseCategoriesController::class)->except('show', 'create');
    
    //Expense
    Route::resource('expenses', ExpenseController::class);

    //Customers
    Route::resource('customers', CustomersController::class);
    
    //Suppliers
    Route::resource('suppliers', SuppliersController::class);

    //Warehouses
    Route::resource('warehouses', WarehouseController::class);

    //Brands
    Route::resource('brands', BrandsController::class);

     //Print Barcode
     Route::get('/products/print-barcode', [BarcodeController::class, 'printBarcode'])->name('barcode.print');
     
     //Product
    Route::resource('products', ProductController::class);
     
     //Product Category
    Route::resource('product-categories', CategoriesController::class)->except('show', 'create');

    //Generate PDF
    Route::get('/quotations/pdf/{id}', function ($id) {
        $quotation = \App\Models\Quotation::findOrFail($id);
        $customer = \App\Models\Customer::findOrFail($quotation->customer_id);

        $pdf = PDF::loadView('admin.quotation.print', [
            'quotation' => $quotation,
            'customer' => $customer,
        ])->setPaper('a4');

        return $pdf->stream('quotation-'. $quotation->reference .'.pdf');
    })->name('quotations.pdf');

    //Send Quotation Mail
    Route::get('/quotation/mail/{quotation}', SendQuotationEmailController::class)->name('quotation.email');

    //Sales Form Quotation
    Route::get('quotation-sales/{quotation]', QuotationSalesController::class)->name('quotation-sales.create');

    //quotations
    Route::resource('quotations', QuotationController::class);

     //Generate PDF
    Route::get('/purchases/pdf/{id}', function ($id) {
        $purchase = \App\Models\Purchase::findOrFail($id);
        $supplier = \App\Models\Supplier::findOrFail($purchase->supplier_id);

        $pdf = PDF::loadView('admin.purchases.print', [
            'purchase' => $purchase,
            'supplier' => $supplier,
        ])->setPaper('a4');

        return $pdf->stream('purchase-'. $purchase->reference .'.pdf');
    })->name('purchases.pdf');

    //Purchases
    Route::resource('purchases', PurchaseController::class);

    //Purchase Payments
    Route::get('/purchase-payments/{purchase_id}',[PurchasePaymentsController::class, 'index'])->name('purchase-payments.index');
    Route::get('/purchase-payments/{purchase_id}/create', [PurchasePaymentsController::class, 'create'])->name('purchase-payments.create');
    Route::post('/purchase-payments/{purchase_id}',[PurchasePaymentsController::class, 'store'])->name('purchase-payments.store');
    Route::get('/purchase-payments/{purchase_id}/edit/{purchasePayment}', [PurchasePaymentsController::class, 'edit'])->name('purchase-payments.edit');
    Route::patch('/purchase-payments/update/{purchasePayment}', [PurchasePaymentsController::class, 'update'])->name('purchase-payments.update');
    Route::delete('/purchase-payments/destroy/{purchasePayment}', [PurchasePaymentsController::class, 'destroy'])->name('purchase-payments.destroy');

    //Generate PDF
    Route::get('/purchase-returns/pdf/{id}', function ($id) {
        $purchaseReturn = \App\Models\PurchaseReturn::findOrFail($id);
        $supplier = \App\Models\Supplier::findOrFail($purchaseReturn->supplier_id);

        $pdf = PDF::loadView('admin.purchasesreturn.print', [
            'purchase_return' => $purchaseReturn,
            'supplier' => $supplier,
        ])->setPaper('a4');

        return $pdf->stream('purchase-return-'. $purchaseReturn->reference .'.pdf');
    })->name('purchase-returns.pdf');

    //Purchase Returns
    Route::resource('purchase-returns', PurchaseReturnController::class);

    //Purchase Returns Payments
    Route::get('/purchase-return-payments/{purchase_return_id}', 'PurchaseReturnPaymentsController@index')
        ->name('purchase-return-payments.index');
    Route::get('/purchase-return-payments/{purchase_return_id}/create', 'PurchaseReturnPaymentsController@create')
        ->name('purchase-return-payments.create');
    Route::post('/purchase-return-payments/store', 'PurchaseReturnPaymentsController@store')
        ->name('purchase-return-payments.store');
    Route::get('/purchase-return-payments/{purchase_return_id}/edit/{purchaseReturnPayment}', 'PurchaseReturnPaymentsController@edit')
        ->name('purchase-return-payments.edit');
    Route::patch('/purchase-return-payments/update/{purchaseReturnPayment}', 'PurchaseReturnPaymentsController@update')
        ->name('purchase-return-payments.update');
    Route::delete('/purchase-return-payments/destroy/{purchaseReturnPayment}', 'PurchaseReturnPaymentsController@destroy')
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

    //Generate PDF
    Route::get('/sales/pdf/{id}', function ($id) {
        $sale = \App\Models\Sale::findOrFail($id);
        $customer = \App\Models\Customer::findOrFail($sale->customer_id);

        $pdf = PDF::loadView('admin.sale.print', [
            'sale' => $sale,
            'customer' => $customer,
        ])->setPaper('a4');

        return $pdf->stream('sale-'. $sale->reference .'.pdf');
    })->name('sales.pdf');

    Route::get('/sales/pos/pdf/{id}', function ($id) {
        $sale = \App\Models\Sale::findOrFail($id);

        $pdf = PDF::loadView('admin.sale.print-pos', [
            'sale' => $sale,
        ])->setPaper('a7')
            ->setOption('margin-top', 8)
            ->setOption('margin-bottom', 8)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5);

        return $pdf->stream('sale-'. $sale->reference .'.pdf');
    })->name('sales.pos.pdf');

    //Sales
    Route::resource('sales', SaleController::class);
    
    //Payments
    Route::get('/sale-payments/{sale_id}', [SalePaymentsController::class, 'index'])->name('sale-payments.index');
    Route::get('/sale-payments/{sale_id}/create', [SalePaymentsController::class, 'create'])->name('sale-payments.create');
    Route::post('/sale-payments/store', [SalePaymentsController::class, 'store'])->name('sale-payments.store');
    Route::get('/sale-payments/{sale_id}/edit/{salePayment}', [SalePaymentsController::class, 'edit'])->name('sale-payments.edit');
    Route::patch('/sale-payments/update/{salePayment}', [SalePaymentsController::class, 'update'])->name('sale-payments.update');
    Route::delete('/sale-payments/destroy/{salePayment}', [SalePaymentsController::class, 'destroy'])->name('sale-payments.destroy');

     //Generate PDF
     Route::get('/sale-returns/pdf/{id}', function ($id) {
        $saleReturn = \App\Models\SaleReturn::findOrFail($id);
        $customer = \App\Models\Customer::findOrFail($saleReturn->customer_id);

        $pdf = PDF::loadView('admin.salesreturn.print', [
            'sale_return' => $saleReturn,
            'customer' => $customer,
        ])->setPaper('a4');

        return $pdf->stream('sale-return-'. $saleReturn->reference .'.pdf');
    })->name('sale-returns.pdf');

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
     Route::get('/user/profile', 'ProfileController@edit')->name('profile.edit');
     Route::patch('/user/profile', 'ProfileController@update')->name('profile.update');
     Route::patch('/user/password', 'ProfileController@updatePassword')->name('profile.update.password');
 
     //Users
     Route::resource('users', UsersController::class);
 
     //Roles
     Route::resource('roles', RoleController::class)->except(['show']);

     // Permissions
    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy']]);

    //Mail Settings
    Route::patch('/settings/smtp', [SettingController::class ,'updateSmtp'] )->name('settings.smtp.update');
    
    //General Settings
    Route::get('/settings', [SettingController::class ,'index'] )->name('settings.index');
    Route::patch('/settings', [SettingController::class ,'update'] )->name('settings.update');
 
});

require __DIR__.'/auth.php';
