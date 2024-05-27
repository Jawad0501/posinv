<?php

use App\Http\Controllers\Api\Backend\AttendanceController;
use App\Http\Controllers\Api\Backend\AuthController;
use App\Http\Controllers\Api\Backend\Client\CustomerController;
use App\Http\Controllers\Api\Backend\Client\GiftCardController;
use App\Http\Controllers\Api\Backend\Client\RiderController;
use App\Http\Controllers\Api\Backend\DashboardController;
use App\Http\Controllers\Api\Backend\Finance\BankController;
use App\Http\Controllers\Api\Backend\Finance\BankTransactionController;
use App\Http\Controllers\Api\Backend\Finance\ExpenseCategoryController;
use App\Http\Controllers\Api\Backend\Finance\ExpenseController;
use App\Http\Controllers\Api\Backend\Finance\PurchaseController;
use App\Http\Controllers\Api\Backend\Food\AddonController;
use App\Http\Controllers\Api\Backend\Food\AllergyController;
use App\Http\Controllers\Api\Backend\Food\CategoryController;
use App\Http\Controllers\Api\Backend\Food\MealPeriodController;
use App\Http\Controllers\Api\Backend\Food\MenuController;
use App\Http\Controllers\Api\Backend\Food\VariantController;
use App\Http\Controllers\Api\Backend\Inventory\StockAdjustmentController;
use App\Http\Controllers\Api\Backend\Inventory\StockController;
use App\Http\Controllers\Api\Backend\Inventory\WasteController;
use App\Http\Controllers\Api\Backend\KitchenController;
use App\Http\Controllers\Api\Backend\Master\IngredientCategoryController;
use App\Http\Controllers\Api\Backend\Master\IngredientController;
use App\Http\Controllers\Api\Backend\Master\IngredientUnitController;
use App\Http\Controllers\Api\Backend\Master\SupplierController;
use App\Http\Controllers\Api\Backend\Order\OrderController;
use App\Http\Controllers\Api\Backend\POS\CategoryController as POSCategoryController;
use App\Http\Controllers\Api\Backend\POS\CustomerController as POSCustomerController;
use App\Http\Controllers\Api\Backend\POS\MenuController as POSMenuController;
use App\Http\Controllers\Api\Backend\POS\OrderController as POSOrderController;
use App\Http\Controllers\Api\Backend\POS\PaymentController as POSPaymentController;
use App\Http\Controllers\Api\Backend\POS\TableController as POSTableController;
use App\Http\Controllers\Api\Backend\Production\ProductionUnitController;
use App\Http\Controllers\Api\Backend\ReportController;
use App\Http\Controllers\Api\Backend\Restaurant\ReservationController;
use App\Http\Controllers\Api\Backend\Restaurant\TableLayoutController;
use App\Http\Controllers\Api\Backend\SettingController;
use App\Http\Controllers\Api\Backend\V2S\OrderController as V2SOrderController;
use App\Http\Controllers\Api\Frontend\AddressController;
use App\Http\Controllers\Api\Frontend\CartController as FrontendCartController;
use App\Http\Controllers\Api\Frontend\CheckoutController;
use App\Http\Controllers\Api\Frontend\MenuController as FrontendMenuController;
use App\Http\Controllers\Api\Frontend\OrderHistoryController;
use App\Http\Controllers\Api\Frontend\ProfileController;
use App\Http\Controllers\Api\Frontend\ReservationController as FrontendReservationController;
use App\Http\Controllers\Api\Frontend\SettingController as FrontendSettingController;
use App\Http\Controllers\Api\Frontend\UserController;
use App\Http\Controllers\Api\Frontend\WishlistController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* BACKEND ROUTES */
Route::group(['prefix' => 'admin'], function () {

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::group(['middleware' => ['auth:sanctum', 'auth:staff-api']], function () {

        Route::get('dashboard', DashboardController::class);

        Route::get('staff', [AuthController::class, 'staff']);
        Route::get('user', [AuthController::class, 'user']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);

        Route::get('kitchen/order', [KitchenController::class, 'index']);
        Route::put('kitchen/{id}/order', [KitchenController::class, 'update']);

        // Pos routes define here...
        Route::group(['prefix' => 'pos'], function () {
            Route::get('category', [POSCategoryController::class, 'index']);

            Route::group(['prefix' => 'menu'], function () {
                Route::get('/', [POSMenuController::class, 'index']);
                Route::post('/', [POSMenuController::class, 'store']);
                Route::get('/details/{food}', [POSMenuController::class, 'details']);
                Route::get('/details/{food}/{variant}', [POSMenuController::class, 'variant']);

                Route::get('category/check', [POSMenuController::class, 'categoryCheck']);
                Route::get('{id}/ingredient', [POSMenuController::class, 'ingredientPrice']);
            });

            Route::get('table', [POSTableController::class, 'index']);

            Route::get('rider', [POSCustomerController::class, 'getRider']);
            Route::apiResource('customer', POSCustomerController::class)->except('destroy');

            Route::post('order/{order}/cancel', [POSOrderController::class, 'cancel']);
            Route::put('order/{order}/accept', [POSOrderController::class, 'accept']);
            Route::get('order/online', [POSOrderController::class, 'online']);
            Route::get('order/{order}/online', [POSOrderController::class, 'details']);
            Route::apiResource('order', POSOrderController::class)->except('destroy');

            Route::post('payment', POSPaymentController::class);
        });

        // V2s device routes define here...
        Route::group(['prefix' => 'v2s/order', 'controller' => V2SOrderController::class], function () {
            Route::get('/', 'index');
            Route::put('/', 'update');
            Route::get('/{status}', 'orderByStatusWise');
        });

        // Order management routes define here...
        Route::group(['prefix' => 'orders'], function () {
            Route::apiResource('order', OrderController::class)->only('index', 'show');
        });

        Route::group(['prefix' => 'food'], function () {
            Route::apiResource('meal-period', MealPeriodController::class);
            Route::apiResource('category', CategoryController::class);
            Route::apiResource('allergy', AllergyController::class);
            Route::apiResource('addon', AddonController::class);

            Route::controller(MenuController::class)->prefix('menu')->as('menu.')->group(function () {
                Route::get('category/check', 'categoryCheck');
                Route::get('{id}/ingredient', 'ingredientPrice');
            });

            Route::get('menu/partials', [MenuController::class, 'partials']);
            Route::apiResource('menu', MenuController::class);

            Route::apiResource('variant', VariantController::class);
        });

        Route::group(['controller' => ProductionUnitController::class, 'prefix' => 'production'], function () {
            Route::get('{food}/variant', [ProductionUnitController::class, 'foodVariant']);
            Route::get('/create', [ProductionUnitController::class, 'create']);
            Route::delete('production-item/{id}/remove', [ProductionUnitController::class, 'itemDestroy']);
            Route::apiResource('production-unit', ProductionUnitController::class);
        });

        Route::group(['prefix' => 'finance'], function () {
            Route::delete('purchase/{purchaseItem}/delete', [PurchaseController::class, 'itemDestroy']);
            Route::apiResource('purchase', PurchaseController::class);
            Route::apiResource('expense-category', ExpenseCategoryController::class);
            Route::apiResource('expense', ExpenseController::class);
            Route::apiResource('bank', BankController::class);
            Route::apiResource('bank-transaction', BankTransactionController::class);

        });

        // Restaurant management routes define here...
        Route::group(['prefix' => 'restaurant'], function () {
            Route::apiResource('table-layout', TableLayoutController::class);
            Route::post('reservation/checking', [FrontendReservationController::class, 'checking']);
            Route::apiResource('reservation', ReservationController::class);
        });

        Route::controller(ReportController::class)->prefix('report')->group(function () {
            Route::get('/partials/{type}', 'partials');
            Route::get('/purchase', 'purchaseReport');
            Route::get('/expense', 'expenseReport');
            Route::get('/bank-transaction', 'bankTransactionReport');
            Route::get('/waste', 'wasteReport');
            Route::get('/ingredient', 'ingredientReport');
            Route::get('/stock', 'stockReport');
            Route::get('/sale', 'saleReport');
            Route::get('/profit-loss', 'profitLossReport');
            Route::get('/profit-loss/gross/{type?}', 'profitGrossReport');
        });

        Route::group(['prefix' => 'master'], function () {
            Route::apiResource('ingredient-category', IngredientCategoryController::class);
            Route::apiResource('ingredient-unit', IngredientUnitController::class);
            Route::apiResource('supplier', SupplierController::class);
            Route::apiResource('ingredient', IngredientController::class);
        });

        Route::group(['prefix' => 'inventory'], function () {
            Route::get('stock', StockController::class);

            Route::delete('stock-adjustment/{stockAdjustmentItem}/delete', [StockAdjustmentController::class, 'itemDestroy']);
            Route::apiResource('stock-adjustment', StockAdjustmentController::class);
            Route::apiResource('waste', WasteController::class);
        });

        Route::group(['prefix' => 'client'], function () {
            Route::apiResource('customer', CustomerController::class);
            Route::apiResource('rider', RiderController::class);
            Route::apiResource('gift-card', GiftCardController::class)->except('update');
        });

        Route::post('attendance', AttendanceController::class);

        Route::get('setting', [SettingController::class, 'index']);
        Route::put('setting', [SettingController::class, 'update']);
        Route::get('setting/permission', [SettingController::class, 'permission']);
        Route::get('setting/table-layout', [SettingController::class, 'tableLayoutGet']);
        Route::put('setting/table-layout', [SettingController::class, 'tableLayoutUpdate']);
    });
});

/* FRONTEND ROUTES */
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);

Route::group(['middleware' => ['auth:sanctum', 'auth:api']], function () {
    Route::get('user', [UserController::class, 'user']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('verify', [VerifyEmailController::class, '__invoke'])->middleware(['throttle:6,1']);
    Route::post('phone/verification', [UserController::class, 'phoneVerify'])->middleware('throttle:6,1');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1');

    Route::group(['middleware' => ['verified']], function () {
        Route::post('voucher', [CheckoutController::class, 'voucher']);
        Route::post('checkout', [CheckoutController::class, 'store']);

        Route::get('reservation', [FrontendReservationController::class, 'index']);
        Route::get('reservation/{invoice}', [FrontendReservationController::class, 'show']);

        Route::get('profile', [ProfileController::class, 'index']);
        Route::put('profile', [ProfileController::class, 'update']);

        Route::get('order', [OrderHistoryController::class, 'order']);
        Route::get('order-history', [OrderHistoryController::class, 'index']);
        Route::get('order-history/{invoice}', [OrderHistoryController::class, 'show']);
        Route::get('password-change', [ProfileController::class, 'passwordChange']);
        Route::apiResource('address', AddressController::class);
    });
});

Route::get('general-setting', FrontendSettingController::class);
Route::get('menu', [FrontendMenuController::class, 'index']);
Route::get('menu/{slug}', [FrontendMenuController::class, 'show']);
Route::get('popular-item', [FrontendMenuController::class, 'popularItem']);
Route::get('cart', FrontendCartController::class);
Route::get('wishlist', WishlistController::class);

Route::post('reservation/checking', [FrontendReservationController::class, 'checking']);
Route::post('reservation', [FrontendReservationController::class, 'store']);
Route::put('reservation/{invoice}', [FrontendReservationController::class, 'update']);

Route::get('autocomplete/{input}', function ($input) {
    $responseArray = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
        'input' => $input,
        'libraries' => 'places',
        'key' => config('services.google.map_api_key'),
    ])->json();

    return response()->json(collect($responseArray['predictions'])->map(
        fn ($value) => [
            'id' => $value['place_id'],
            'label' => $value['description'],
        ]
    ));
});
