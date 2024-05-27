<?php

use App\Http\Controllers\Backend\Client\CustomerController;
use App\Http\Controllers\Backend\Client\GiftCardController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Finance\BankController;
use App\Http\Controllers\Backend\Finance\BankTransactionController;
use App\Http\Controllers\Backend\Finance\ExpenseCategoryController;
use App\Http\Controllers\Backend\Finance\ExpenseController;
use App\Http\Controllers\Backend\Finance\IncomeCategoryController;
use App\Http\Controllers\Backend\Finance\IncomeController;
use App\Http\Controllers\Backend\Finance\PurchaseController;
use App\Http\Controllers\Backend\Food\AddonController;
use App\Http\Controllers\Backend\Food\AllergyController;
use App\Http\Controllers\Backend\Food\CategoryController;
use App\Http\Controllers\Backend\Food\FoodController;
use App\Http\Controllers\Backend\Food\MealPeriodController;
use App\Http\Controllers\Backend\Food\PrintLabelController;
use App\Http\Controllers\Backend\Food\VariantController;
use App\Http\Controllers\Backend\Frontend\AdController;
use App\Http\Controllers\Backend\Frontend\AskedQuestionController;
use App\Http\Controllers\Backend\Frontend\CouponController;
use App\Http\Controllers\Backend\Frontend\PageController;
use App\Http\Controllers\Backend\Frontend\SettingController as FrontendSettingController;
use App\Http\Controllers\Backend\Frontend\SubscriberController;
use App\Http\Controllers\Backend\HR\AttendanceController;
use App\Http\Controllers\Backend\HR\ClockInController;
use App\Http\Controllers\Backend\HR\RoleController;
use App\Http\Controllers\Backend\HR\StaffController;
use App\Http\Controllers\Backend\Inventory\StockAdjustmentController;
use App\Http\Controllers\Backend\Inventory\StockController;
use App\Http\Controllers\Backend\Inventory\WasteController;
use App\Http\Controllers\Backend\KitchenController;
use App\Http\Controllers\Backend\Master\IngredientCategoryController;
use App\Http\Controllers\Backend\Master\IngredientController;
use App\Http\Controllers\Backend\Master\IngredientUnitController;
use App\Http\Controllers\Backend\Master\SupplierController;
use App\Http\Controllers\Backend\Order\OrderController as OrderOrderController;
use App\Http\Controllers\Backend\Order\OrderReviewController;
use App\Http\Controllers\Backend\Payroll\CallBackController;
use App\Http\Controllers\Backend\Payroll\EmployeeController;
use App\Http\Controllers\Backend\Payroll\LeaveController;
use App\Http\Controllers\Backend\Payroll\SalaryController;
use App\Http\Controllers\Backend\POS\CartController;
use App\Http\Controllers\Backend\POS\CategoryController as POSCategoryController;
use App\Http\Controllers\Backend\POS\CustomerController as POSCustomerController;
use App\Http\Controllers\Backend\POS\MenuController;
use App\Http\Controllers\Backend\POS\OrderController;
use App\Http\Controllers\Backend\POS\PaymentController;
use App\Http\Controllers\Backend\POS\POSController;
use App\Http\Controllers\Backend\POS\TableController;
use App\Http\Controllers\Backend\Production\ProductionUnitController;
use App\Http\Controllers\Backend\Accounting\DepositController;
use App\Http\Controllers\Backend\Accounting\WithdrawController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\Restaurant\ReservationController;
use App\Http\Controllers\Backend\Restaurant\TableLayoutController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\SystemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductReturnController;
use App\Http\Controllers\Backend\UserLedgerController;
use App\Http\Controllers\Backend\SupplierLedgerController;

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

Route::get('/dashboard', DashboardController::class)->name('dashboard');

// POS management routes define here...
Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
    Route::resource('sales', OrderOrderController::class);
    Route::get('/', POSController::class)->name('index');
    Route::get('/category', POSCategoryController::class)->name('category');

    Route::controller(MenuController::class)->prefix('menu')->as('menu.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('details/{food}', 'details')->name('details');
        Route::get('details/{food}/{variant}', 'variant')->name('variant');
    });

    Route::controller(CartController::class)->prefix('cart')->as('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('discount', 'showDiscountForm')->name('discount');
        Route::post('discount', 'discountStore')->name('discount');
        Route::put('{id}', 'update')->name('update');
        Route::delete('/{id?}', 'destroy')->name('destroy');
    });

    Route::controller(TableController::class)->prefix('table')->as('table.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('available-or-not/{id}', 'seeAvailability')->name('seeAvailability');
    });

    Route::resource('customer', POSCustomerController::class)->except('destroy');

    Route::controller(OrderController::class)->prefix('order')->as('order.')->group(function () {
        Route::post('{order}/cancel', 'cancel')->name('cancel');
        Route::put('{order}/accept', 'accept')->name('accept');
        Route::get('online', 'online')->name('online');
        Route::get('{order}/print', 'print')->name('print');
        Route::get('{order}/print-kot', 'printKOT')->name('print-kot');
        Route::get('customer-wallet/{id}', 'wallet')->name('customer-wallet');
        Route::get('order/invoice/{id}', 'invoice')->name('invoice');
        Route::get('order/download-invoice/{id}', 'downloadInvoice')->name('download-invoice');
        Route::get('order/due-collection/{id}', 'dueCollection')->name('due-collection');
        Route::post('order/due-collection-store/{id}', 'storeDueCollection')->name('due-collection.store');
    });

    Route::resource('order', OrderController::class)->except('destroy');

    Route::controller(PaymentController::class)->prefix('payment')->as('payment.')->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });
});

// Kitchen management routes define here...
Route::group(['prefix' => 'kitchen', 'as' => 'kitchen.'], function () {
    Route::get('panel', [KitchenController::class, 'index'])->name('index');
    Route::get('order', [KitchenController::class, 'order'])->name('order');
    Route::put('order/{order_id}/update', [KitchenController::class, 'orderUpdate'])->name('order.update');
});

// Order management routes define here...
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
    Route::resource('order', OrderOrderController::class);
    Route::resource('review', OrderReviewController::class);
    Route::resource('reservations', ReservationController::class);
});

Route::group(['prefix' => 'returns', 'as' => 'returns.'], function () {
    Route::resource('return', ProductReturnController::class);
});

Route::group(['prefix' => 'ledgers', 'as' => 'ledgers.'], function () {
    Route::get('print', [UserLedgerController::class, 'printLedger'])->name('ledger.print');
});

// Food management routes define here...
Route::group(['prefix' => 'food', 'as' => 'food.'], function () {
    Route::resource('meal-period', MealPeriodController::class)->except('show');
    Route::controller(FoodController::class)->prefix('menu')->as('menu.')->group(function () {
        Route::get('category/check', 'categoryCheck')->name('category.check');
        Route::get('{id}/ingredient', 'ingredientPrice')->name('ingredient');
        Route::get('upload', 'showUploadForm')->name('upload');
        Route::post('upload', 'upload')->name('upload');
    });
    Route::resource('menu', FoodController::class);

    Route::get('print-label', [PrintLabelController::class, 'index'])->name('printlabel.index');
    Route::get('print-label/menu', [PrintLabelController::class, 'menu'])->name('printlabel.menu');
    Route::get('print-label/print', [PrintLabelController::class, 'preview'])->name('printlabel.print');

    Route::group(['prefix' => 'setting'], function () {
        Route::resource('category', CategoryController::class)->except('show');
        Route::resource('allergy', AllergyController::class)->except('show');
        Route::resource('addon', AddonController::class)->except('show');
        Route::resource('variant', VariantController::class)->except('show');
    });
});

Route::group(['prefix' => 'expenses'], function() {
    Route::resource('expense-category', ExpenseCategoryController::class)->except('show');

    Route::get('expense/category-data', [ExpenseController::class, 'category'])->name('expense.category');
    Route::resource('expense', ExpenseController::class)->except('show');
});

// Finance management routes define here...
Route::group(['prefix' => 'finance'], function () {

    Route::controller(PurchaseController::class)->prefix('purchase')->as('purchase.')->group(function () {
        Route::get('items', 'items')->name('items');
        Route::get('supplier', 'supplier')->name('supplier');
        Route::get('supplier-advance-amount/{id}', 'advanceAmount')->name('supplier.advance');
        Route::get('{ingredient}/ingredient', 'ingredient')->name('ingredient');
        Route::delete('{purchaseItem}/delete', 'itemDestroy')->name('item.destroy');
    });
    Route::resource('purchase', PurchaseController::class);
    Route::get('purchase/invoice/{id}', [PurchaseController::class, 'showInvoice'])->name('purchase.invoice');
    Route::get('purchase/download-invoice/{id}', [PurchaseController::class, 'downloadInvoice'])->name('purchase.download-invoice');
    Route::get('purchase/due-collect/{id}', [PurchaseController::class, 'dueCollection'])->name('purchase.due-collect');
    Route::post('purchase/store-due-collection/{id}', [PurchaseController::class, 'storeDueCollection'])->name('purchase.store-due-collection');


    Route::resource('income-category', IncomeCategoryController::class)->except('show');

    Route::get('income/category-data', [IncomeController::class, 'category'])->name('income.category');
    Route::resource('income', IncomeController::class)->except('show');

    
    Route::resource('bank', BankController::class)->except('show');
    Route::resource('bank-transaction', BankTransactionController::class)->except('show');
    
});

// Restaurant management routes define here...
Route::group(['prefix' => 'restaurant'], function () {
    Route::resource('table-layout', TableLayoutController::class);
    Route::get('table-setting', [TableLayoutController::class, 'setting'])->name('table-layout.setting');
    Route::get('get-category-foods/{category}', [TableLayoutController::class, 'getCategoryFood'])->name('get-category-food');
    Route::get('generate-qr-form/{table}', [TableLayoutController::class, 'generateQR'])->name('generate-qr-code');
    Route::get('generate-qr-image/{table}/{category}', [TablelayoutController::class, 'generateQrImage'])->name('generate-qr-image');
});

// Inventory management routes define here...
Route::group(['prefix' => 'inventory'], function () {
    Route::get('stock', StockController::class)->name('stock.index');
    Route::delete('stock-adjustment/{stockAdjustmentItem}/delete', [StockAdjustmentController::class, 'itemDestroy'])->name('stock-adjustment.item.destroy');
    Route::resource('stock-adjustment', StockAdjustmentController::class);

    Route::get('waste/{id}/food', [WasteController::class, 'food'])->name('waste.food');
    Route::resource('waste', WasteController::class);
});

// Client/Profile management routes define here...
Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('user-ledger', UserLedgerController::class);
    Route::get('get-ledger-customer', [UserLedgerController::class, 'customer'])->name('ledger.customer');

    // Route::resource('gift-card', GiftCardController::class)->only('index', 'create', 'store', 'destroy');
});

// HR management routes define here...
Route::group(['prefix' => 'hr'], function () {
    Route::resource('role', RoleController::class)->except('show');
    Route::resource('staff', StaffController::class);

    Route::group(['controller' => AttendanceController::class, 'prefix' => 'attendance', 'as' => 'attendance.'], function () {
        Route::get('log', 'log')->name('log');
        Route::get('upload', 'showUploadForm')->name('upload');
        Route::post('upload', 'upload')->name('upload');
    });
    Route::get('clock-in', [ClockInController::class, 'index'])->name('clock-in.index');
    Route::post('clock-in/check', [ClockInController::class, 'check'])->name('clock-in.check');
    Route::post('clock-in', [ClockInController::class, 'store'])->name('clock-in.store');

    Route::resource('attendance', AttendanceController::class);
});

// Payroll management routes define here...
Route::group(['prefix' => 'payroll', 'as' => 'payroll.'], function () {
    // Route::middleware('xero')->group(function () {
    //     Route::resource('employee', EmployeeController::class);
    //     Route::resource('leave', LeaveController::class);
    //     Route::resource('salary', SalaryController::class);
    // });
    Route::resource('employee', EmployeeController::class);
    Route::resource('leave', LeaveController::class);
    Route::resource('salary', SalaryController::class);
    Route::get('callback', CallBackController::class)->name('callback');
});

Route::group(['prefix' => 'suppliers'], function() {
    Route::get('supplier/upload', [SupplierController::class, 'showUploadForm'])->name('supplier.upload');
    Route::post('supplier/upload', [SupplierController::class, 'upload'])->name('supplier.upload');
    Route::resource('supplier', SupplierController::class);

    Route::get('get-ledger-supplier', [SupplierLedgerController::class, 'supplier'])->name('ledger.supplier');
    Route::resource('ledger-supplier', SupplierLedgerController::class);
});

Route::group(['prefix' => 'accounting'], function () {
    Route::resource('deposit', DepositController::class);
    Route::resource('withdraw', WithdrawController::class);
});

// Master management routes define here...
Route::group(['prefix' => 'master'], function () {
    Route::resource('ingredient-category', IngredientCategoryController::class)->except('show');
    Route::resource('ingredient-unit', IngredientUnitController::class)->except('show');

   

    Route::get('ingredient/upload', [IngredientController::class, 'showUploadForm'])->name('ingredient.upload');
    Route::post('ingredient/upload', [IngredientController::class, 'upload'])->name('ingredient.upload');
    Route::resource('ingredient', IngredientController::class);
    Route::get('ingredient/{id}/history', [IngredientController::class, 'fetchHistory'])->name('ingredient.history');

    Route::get('production/{food}/variant', [ProductionUnitController::class, 'foodVariant'])->name('production.variant');
    Route::get('production/ingredient', [ProductionUnitController::class, 'ingredient'])->name('production.ingredient');
    Route::delete('production-item/{id}/remove', [ProductionUnitController::class, 'itemDestroy'])->name('production.item.destroy');
    Route::resource('production-unit', ProductionUnitController::class);
});

// Report management routes define here...
Route::controller(ReportController::class)->prefix('report')->as('report.')->group(function () {
    Route::get('/purchase', 'purchaseReport')->name('purchase');
    Route::get('/expense', 'expenseReport')->name('expense');
    Route::get('/bank-transaction', 'bankTransactionReport')->name('bank-transaction');
    Route::get('/waste', 'wasteReport')->name('waste');
    Route::get('/ingredient', 'ingredientReport')->name('ingredient');
    Route::get('/stock', 'stockReport')->name('stock');
    Route::get('/sale', 'saleReport')->name('sale');
    Route::get('/profit-loss', 'profitLossReport')->name('profit.loss');
    Route::get('/profit-loss/view', 'showLossProfitView')->name('profit.loss.view');
    Route::get('/profit-loss/gross/{type}', 'showGrossProfit')->name('profit.loss.gross');
});

// Frontend routes define here...
Route::group(['prefix' => 'frontend', 'as' => 'frontend.'], function () {
    Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber.index');
    Route::delete('subscriber/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscriber.destroy');
    Route::resource('asked-question', AskedQuestionController::class)->except('show');
    Route::resource('coupon', CouponController::class)->except('show');
    Route::resource('page', PageController::class);
    Route::resource('ad', AdController::class);
    Route::get('setting', [FrontendSettingController::class, 'index'])->name('setting.index');
    Route::put('setting', [FrontendSettingController::class, 'update'])->name('setting.update');
});

// Frontend routes define here...
Route::group(['prefix' => 'system', 'as' => 'system.'], function () {
    Route::get('cache', [SystemController::class, 'cacheFormShow'])->name('cache');
    Route::post('cache', [SystemController::class, 'cacheClear'])->name('cache');
});

// Setting routes define here...
Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::put('/', [SettingController::class, 'update'])->name('update');
});

// Profile routes define here...
Route::controller(ProfileController::class)->as('staff.')->group(function () {
    Route::get('profile', 'index')->name('profile.index');
    Route::put('profile', 'update')->name('profile.update');
    Route::get('password-update', 'showUpdatePasswordForm')->name('password.update');
    Route::put('password-update', 'updatePassword')->name('password.update');
});
