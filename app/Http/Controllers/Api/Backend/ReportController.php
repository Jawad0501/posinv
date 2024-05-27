<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Finance\BankTransactionCollection;
use App\Http\Resources\Backend\Finance\ExpenseCollection;
use App\Http\Resources\Backend\Finance\PurchaseCollection;
use App\Http\Resources\Backend\Inventory\StockCollection;
use App\Http\Resources\Backend\Inventory\WasteCollection;
use App\Http\Resources\Backend\Master\IngredientCollection;
use App\Http\Resources\Backend\Report\ProfitByFoodCollection;
use App\Http\Resources\Backend\Report\SaleReportCollection;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\StockAdjustmentItem;
use App\Models\User;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * @group Report Management
 *
 * APIs to Report manage
 */
class ReportController extends Controller
{
    /**
     * Display a listing of the purchase report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\PurchaseCollection
     *
     * @apiResourceModel App\Models\Purchase
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date
     * @queryParam to_date date
     * @queryParam keyword string
     */
    public function purchaseReport(Request $request): PurchaseCollection
    {
        $purchases = Purchase::query()->with('supplier:id,name');
        if (! empty($request->get('from_date')) && ! empty($request->get('to_date'))) {
            $purchases = $purchases->whereBetween('date', [$request->get('from_date'), $request->get('to_date')]);
        }

        if ($request->has('keyword')) {
            $purchases = $purchases->where(function ($queiry) use ($request) {
                $queiry->where('reference_no', 'like', "%$request->keyword%")
                    ->orWhere('total_amount', 'like', "%$request->keyword%")
                    ->orWhere('shipping_charge', 'like', "%$request->keyword%")
                    ->orWhere('discount_amount', 'like', "%$request->keyword%")
                    ->orWhere('paid_amount', 'like', "%$request->keyword%")
                    ->orWhere('date', 'like', "%$request->keyword%")
                    ->orWhere('payment_type', 'like', "%$request->keyword%")
                    ->orWhere('details', 'like', "%$request->keyword%")
                    ->orWhereRelation('supplier', 'name', 'like', "%$request->keyword%")
                    ->orWhereRelation('bank', 'name', 'like', "%$request->keyword%");
            });
        }

        $purchases = $purchases->latest('id')->paginate();

        return new PurchaseCollection($purchases);
    }

    /**
     * Display a listing of the expense report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\ExpenseCollection
     *
     * @apiResourceModel App\Models\Expense
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date
     * @queryParam to_date date
     * @queryParam keyword
     */
    public function expenseReport(Request $request)
    {
        $expenses = Expense::query()->with('staff:id,name', 'category:id,name');
        if (! empty(request()->get('from_date')) && ! empty(request()->get('to_date'))) {
            $expenses = $expenses->whereBetween('date', [request()->get('from_date'), request()->get('to_date')]);
        }

        if ($request->has('keyword')) {
            $expenses = $expenses->where(function ($query) use ($request) {
                $query->where('date', 'like', "%$request->keyword%")
                    ->orWhere('amount', 'like', "%$request->keyword%")
                    ->orWhere('note', 'like', "%$request->keyword%")
                    ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%")
                    ->orWhereRelation('category', 'name', 'like', "%$request->keyword%");
            });
        }

        $expenses = $expenses->latest('id')->paginate();

        return new ExpenseCollection($expenses);
    }

    /**
     * Display a listing of the bank transaction report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\ExpenseCollection
     *
     * @apiResourceModel App\Models\Expense
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date
     * @queryParam to_date date
     * @queryParam bank integer
     * @queryParam keyword
     */
    public function bankTransactionReport(Request $request)
    {
        $transactions = BankTransaction::query()->with('bank:id,name');
        if (! empty(request()->get('bank'))) {
            $transactions = $transactions->where('bank_id', request()->get('bank'));
        }
        if (! empty(request()->get('from_date')) && ! empty(request()->get('to_date'))) {
            $transactions = $transactions->whereBetween('date', [request()->get('from_date'), request()->get('to_date')]);
        }

        if ($request->has('keyword')) {
            $transactions = $transactions->where(function ($query) use ($request) {
                $query->where('withdraw_deposite_id', 'like', "%$request->keyword%")
                    ->orWhere('amount', 'like', "%$request->keyword%")
                    ->orWhere('type', 'like', "%$request->keyword%")
                    ->orWhere('decsription', 'like', "%$request->keyword%")
                    ->orWhere('date', 'like', "%$request->keyword%")
                    ->orWhereRelation('bank', 'name', 'like', "%$request->keyword%");
            });
        }
        $transactions = $transactions->latest('id')->paginate();

        return new BankTransactionCollection($transactions);
    }

    /**
     * Display a listing of the waste report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Inventory\WasteCollection
     *
     * @apiResourceModel App\Models\Waste
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date
     * @queryParam to_date date
     * @queryParam person integer
     * @queryParam keyword
     */
    public function wasteReport(Request $request)
    {
        $wastes = Waste::query()->with('staff:id,name');
        if (request()->has('person') && ! empty(request()->get('person'))) {
            $wastes = $wastes->where('staff_id', request()->get('bank'));
        }
        if (! empty(request()->get('from_date')) && ! empty(request()->get('to_date'))) {
            $wastes = $wastes->whereBetween('date', [request()->get('from_date'), request()->get('to_date')]);
        }
        if ($request->has('keyword')) {
            $wastes = $wastes->where(function ($query) use ($request) {
                $query->where('reference_no', 'like', "%$request->keyword%")
                    ->orWhere('date', 'name', 'like', "%$request->keyword%")
                    ->orWhere('note', 'code', 'like', "%$request->keyword%")
                    ->orWhere('added_by', 'alert_qty', 'like', "%$request->keyword%")
                    ->orWhere('total_loss', 'alert_qty', 'like', "%$request->keyword%")
                    ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%");
            });
        }
        $wastes = $wastes->latest('id')->paginate();

        return new WasteCollection($wastes);
    }

    /**
     * Display a listing of the ingredient report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Master\IngredientCollection
     *
     * @apiResourceModel App\Models\Ingredient
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam category integer
     * @queryParam unit integer
     * @queryParam keyword
     */
    public function ingredientReport(Request $request)
    {
        $ingredients = Ingredient::query()->with('category:id,name', 'unit:id,name');
        if (request()->has('category') && ! empty(request()->get('category'))) {
            $ingredients = $ingredients->where('category_id', request()->get('category'));
        }
        if (request()->has('unit') && ! empty(request()->get('unit'))) {
            $ingredients = $ingredients->where('unit_id', request()->get('unit'));
        }

        if ($request->has('keyword')) {
            $ingredients = $ingredients->where(function ($query) use ($request) {
                $query->where('name', 'like', "%$request->keyword%")
                    ->orWhere('purchase_price', 'like', "%$request->keyword%")
                    ->orWhere('alert_qty', 'like', "%$request->keyword%")
                    ->orWhere('code', 'like', "%$request->keyword%")
                    ->orWhereRelation('category', 'name', 'like', "%$request->keyword%")
                    ->orWhereRelation('unit', 'name', 'like', "%$request->keyword%");
            });
        }
        $ingredients = $ingredients->latest('id')->paginate();

        return new IngredientCollection($ingredients);
    }

    /**
     * Display a listing of the stock report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Inventory\StockCollection
     *
     * @apiResourceModel App\Models\Stock
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam ingredient integer
     * @queryParam category integer
     * @queryParam keyword string
     *
     * @return \App\Http\Resources\Backend\Inventory\StockCollection
     */
    public function stockReport(Request $request)
    {
        $stocks = Stock::query()->with(
            'ingredient:id,category_id,unit_id,name,alert_qty,code',
            'ingredient.unit:id,name',
            'ingredient.category:id,name',
            'ingredient.stock:id,ingredient_id,qty_amount'
        );

        if (request()->has('ingredient') && ! empty(request()->get('ingredient'))) {
            $stocks = $stocks->where('ingredient_id', request()->get('ingredient'));
        }
        if (request()->has('category') && ! empty(request()->get('category'))) {
            $stocks = $stocks->whereRelation('ingredient.category', 'category_id', '=', request()->get('category'));
        }

        if ($request->has('keyword')) {
            $stocks = $stocks->where(function ($query) use ($request) {
                $query->where('qty_amount', 'like', "%$request->keyword%")
                    ->orWhereRelation('ingredient', 'name', 'like', "%$request->keyword%")
                    ->orWhereRelation('ingredient', 'code', 'like', "%$request->keyword%")
                    ->orWhereRelation('ingredient', 'alert_qty', 'like', "%$request->keyword%")
                    ->orWhereRelation('ingredient.category', 'name', 'like', "%$request->keyword%")
                    ->orWhereRelation('ingredient.unit', 'name', 'like', "%$request->keyword%");
            });
        }
        $stocks = $stocks->latest('id')->paginate();

        return new StockCollection($stocks);
    }

    /**
     * Display a listing of the sales report.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Report\SaleReportCollection
     *
     * @apiResourceModel App\Models\Order
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date_format:Y-m-d
     * @queryParam to_date date_format:Y-m-d
     * @queryParam customer integer
     * @queryParam keyword
     *
     * @return \App\Http\Resources\Backend\Report\SaleReportCollection
     */
    public function saleReport(Request $request)
    {
        $orders = Order::query()->with('user:id,first_name,last_name');
        if (! empty($request->get('from_date')) && ! empty($request->get('to_date'))) {
            $orders = $orders->whereBetween('created_at', [$request->get('from_date'), $request->get('to_date')]);
        }
        if ($request->has('customer') && ! empty($request->get('customer'))) {
            $orders = $orders->where('user_id', $request->get('customer'));
        }

        if ($request->has('keyword')) {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('invoice', 'like', "%$request->keyword%")
                    ->orWhere('type', 'like', "%$request->keyword%")
                    ->orWhere('processing_time', 'like', "%$request->keyword%")
                    ->orWhere('available_time', 'like', "%$request->keyword%")
                    ->orWhere('order_by', 'like', "%$request->keyword%")
                    ->orWhere('discount', 'like', "%$request->keyword%")
                    ->orWhere('service_charge', 'like', "%$request->keyword%")
                    ->orWhere('delivery_charge', 'like', "%$request->keyword%")
                    ->orWhere('grand_total', 'like', "%$request->keyword%")
                    ->orWhere('delivery_type', 'like', "%$request->keyword%")
                    ->orWhere('address', 'like', "%$request->keyword%")
                    ->orWhere('note', 'like', "%$request->keyword%")
                    ->orWhere('rewards', 'like', "%$request->keyword%")
                    ->orWhere('date', 'like', "%$request->keyword%")
                    ->orWhere('rewards_amount', 'like', "%$request->keyword%")
                    ->orWhereRelation('user', 'first_name', 'like', "%$request->keyword%")
                    ->orWhereRelation('user', 'last_name', 'like', "%$request->keyword%");
            });
        }

        $orders = $orders->latest('id')->paginate();

        return new SaleReportCollection($orders);
    }

    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @response status=200
     * {
     *       "total_purchase_shipping_charge": "50.00",
     *       "total_purchase_discount": "50.00",
     *       "total_purchase": 7475,
     *       "total_sell_discount": "50.00",
     *       "total_customer_reward": 140,
     *       "total_expense": "1139.00",
     *       "total_waste": 8631.5,
     *       "total_stock_adjustments": 7610,
     *       "total_sell_shipping_charge": "0.00",
     *       "total_sell_service_charge": "15.00",
     *       "total_sales": "462.70",
     *       "total_sell_vat": 26.70,
     *       "gross_profit": -7012.3,
     *       "net_profit": -24541.1
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam from_date date_format:Y-m-d
     * @queryParam to_date date_format:Y-m-d
     */
    public function profitLossReport(Request $request)
    {
        $data = new stdClass;

        $total_purchase_shipping_charge = Purchase::query();
        $total_purchase_discount = Purchase::query();
        $total_purchase_amount = Purchase::query();

        $total_sell_discount = Order::query();
        $total_customer_reward = User::query();

        $total_expense = Expense::query();

        $wastes = Waste::query();

        $orderSuccessIds = Order::byStatus('success')->pluck('id');

        $total_sell_shipping_charge = Order::query();
        $total_sell_service_charge = Order::query();

        $totalSalesIds = Order::query();
        $total_sales = OrderDetails::query();
        $orderDetails = OrderDetails::query();

        $total_stock_adjustments = 0;
        $minus_stock_adjustments = 0;
        $stock_plus_items = StockAdjustmentItem::query()->with('ingredient:id,purchase_price', 'stockAdjustment:id,date');
        $stock_minus_items = StockAdjustmentItem::query()->with('ingredient:id,purchase_price', 'stockAdjustment:id,date');

        if ($request->has('from_date') && $request->has('to_date')) {
            $from = $request->get('from_date');
            $to = $request->get('to_date');

            $data->total_purchase_shipping_charge = $total_purchase_shipping_charge->whereBetween('date', [$from, $to]);
            $data->total_purchase_discount = $total_purchase_discount->whereBetween('date', [$from, $to]);
            $total_purchase_amount = $total_purchase_amount->whereBetween('date', [$from, $to]);

            $data->total_sell_discount = $total_sell_discount->whereBetween('created_at', [$from, $to]);
            $data->total_expense = $total_expense->whereBetween('created_at', [$from, $to]);

            $wastes = $wastes->whereBetween('created_at', [$from, $to]);

            $total_sell_shipping_charge = $total_sell_shipping_charge->whereBetween('created_at', [$from, $to]);
            $total_sell_service_charge = $total_sell_service_charge->whereBetween('created_at', [$from, $to]);

            $totalSalesIds = $totalSalesIds->whereBetween('created_at', [$from, $to]);

            $stock_plus_items = $stock_plus_items->whereHas('stockAdjustment', fn ($q) => $q->whereBetween('date', [$from, $to]));
            $stock_minus_items = $stock_minus_items->whereHas('stockAdjustment', fn ($q) => $q->whereBetween('date', [$from, $to]));
        }

        $data->total_purchase_shipping_charge = $total_purchase_shipping_charge->sum('shipping_charge');
        $data->total_purchase_discount = $total_purchase_discount->sum('discount_amount');
        $data->total_purchase = ($total_purchase_amount->sum('total_amount') + $data->total_purchase_shipping_charge) - $data->total_purchase_discount;
        $data->total_sell_discount = $total_sell_discount->sum('discount');
        $data->total_customer_reward = $total_customer_reward->sum('rewards') * setting('reward_exchange_rate');
        $data->total_expense = $total_expense->sum('amount');

        $data->total_waste = 0;
        foreach ($wastes->get(['id', 'items']) as $waste) {
            foreach (json_decode($waste->items) as $item) {
                $data->total_waste += $item->total;
            }
        }

        foreach ($stock_plus_items->get() as $pItem) {
            $total_stock_adjustments += $pItem->quantity_amount * $pItem->ingredient->purchase_price;
        }
        foreach ($stock_minus_items->where('consumption_status', 'minus')->get() as $mItem) {
            $minus_stock_adjustments += $mItem->quantity_amount * $mItem->ingredient->purchase_price;
        }
        $data->total_stock_adjustments = $total_stock_adjustments - $minus_stock_adjustments;

        $data->total_sell_shipping_charge = $total_sell_shipping_charge->whereIn('id', $orderSuccessIds)->sum('delivery_charge');
        $data->total_sell_service_charge = $total_sell_service_charge->whereIn('id', $orderSuccessIds)->sum('service_charge');

        $totalSalesIds = $totalSalesIds->whereIn('id', $orderSuccessIds)->pluck('id');
        $total_sales = OrderDetails::with('addons:order_details_id,price,quantity')->whereIn('order_id', $totalSalesIds)->get(['id', 'total_price']);
        $data->total_sales = 0;
        foreach ($total_sales as $total_sale) {
            $data->total_sales += $total_sale->total_price;
            foreach ($total_sale->addons as $addon) {
                $data->total_sales += ($addon->price * $addon->quantity);
            }
        }
        $data->total_sales = OrderDetails::whereIn('order_id', $totalSalesIds)->sum('total_price');

        $data->total_sell_vat = 0;
        $orderDetails = OrderDetails::whereIn('order_id', $totalSalesIds)->get(['price', 'quantity', 'total_price']);
        foreach ($orderDetails as $orderDetail) {
            $data->total_sell_vat += $orderDetail->total_price - ($orderDetail->price * $orderDetail->quantity);
        }

        // Calculation

        $data->gross_profit = $data->total_sales - $data->total_purchase;

        $profit_plus = $data->total_sell_shipping_charge + $data->total_purchase_discount + $data->total_sell_service_charge + $data->total_sell_vat;
        $profit_minus = $data->total_stock_adjustments + $data->total_expense + $data->total_waste + $data->total_purchase_shipping_charge + $data->total_sell_discount + $data->total_customer_reward;

        $data->net_profit = ($data->gross_profit + $profit_plus) - $profit_minus;

        return $data;
    }

    /**
     * Display a listing of the gross profit report.
     *
     * @authenticated
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @bodyParam type string. Example: order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profitGrossReport($type = null)
    {
        if ($type == 'order') {
            $data = Order::byStatus('success')->select('date', DB::raw('SUM(grand_total) as grand_total'))->groupBy('date')->orderBy('date', 'desc')->paginate();
        } elseif ($type == 'invoice') {
            $data = Order::query()->byStatus('success')->select('id', 'invoice', 'grand_total')->paginate();
        } elseif ($type == 'customer') {
            $data = User::query()->select('id', 'first_name', 'last_name')->withSum('orders', 'grand_total')->whereRelation('orders', 'status', '=', 'success')->paginate();
        } else {
            $orderDetails = OrderDetails::query()->with('food:id,name')->withSum('order', 'grand_total')->paginate();
            $data = new ProfitByFoodCollection($orderDetails);
        }

        return $data;
    }

    /**
     * Display a listing of the report partials.
     *
     * @authenticated
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=200
     * {
     *       "id": 1,
     *       "name": "example"
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @bodyParam type string. Example: bank
     */
    public function partials($type)
    {
        if ($type == 'bank') {
            $data = DB::table('banks')->latest('id')->get(['id', 'name']);
        } elseif ($type == 'staff') {
            $data = DB::table('staff')->latest('id')->get(['id', 'name']);
        } elseif ($type == 'ingredient_category') {
            $data = DB::table('ingredient_categories')->latest('id')->get(['id', 'name']);
        } elseif ($type == 'ingredient_unit') {
            $data = DB::table('ingredient_units')->latest('id')->get(['id', 'name']);
        } elseif ($type == 'ingredient') {
            $data = DB::table('ingredients')->latest('id')->get(['id', 'name']);
        } elseif ($type == 'customer') {
            $data = DB::table('users')->latest('id')->get(['id', 'first_name', 'last_name']);
        }

        try {
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
