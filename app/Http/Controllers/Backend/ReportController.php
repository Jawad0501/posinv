<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientUnit;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Purchase;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\StockAdjustmentItem;
use App\Models\User;
use App\Models\Waste;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    /**
     * Display a listing of the purchase report.
     */
    public function purchaseReport()
    {
        $this->authorize('show_purchase');
        if (request()->ajax()) {
            return DataTables::of(Purchase::query()->with('supplier:id,name'))
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! empty(request()->get('from')) && ! empty(request()->get('to'))) {
                        $query->whereBetween('date', [request()->get('from'), request()->get('to')]);
                    }
                }, true)
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('purchase.show', $data->id), 'type' => 'show', 'id' => false],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.report.purchase');
    }

    /**
     * Display a listing of the expense report.
     */
    public function expenseReport()
    {
        $this->authorize('show_expense');

        if (request()->ajax()) {
            $expense = Expense::query()->with('staff:id,name', 'category:id,name');

            return DataTables::eloquent($expense)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! empty(request()->get('from')) && ! empty(request()->get('to'))) {
                        $query->whereBetween('date', [request()->get('from'), request()->get('to')]);
                    }
                })
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->toJson();
        }

        return view('pages.report.expense');
    }

    /**
     * Display a listing of the bank transaction report.
     */
    public function bankTransactionReport()
    {
        $this->authorize('show_bank_transaction');
        if (request()->ajax()) {
            return DataTables::eloquent(BankTransaction::query()->with('bank:id,name'))
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! empty(request()->get('from')) && ! empty(request()->get('to'))) {
                        $query->whereBetween('date', [request()->get('from'), request()->get('to')]);
                    }
                    if (request()->has('bank') && ! empty(request()->get('bank'))) {
                        $query->where('bank_id', request()->get('bank'));
                    }
                })
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->toJson();
        }

        $banks = Bank::latest('id')->get(['id', 'name']);

        return view('pages.report.bank-transaction', compact('banks'));
    }

    /**
     * Display a listing of the waste report.
     */
    public function wasteReport()
    {
        $this->authorize('show_waste');

        if (request()->ajax()) {
            $wastes = Waste::query()->with('staff:id,name');

            return DataTables::eloquent($wastes)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! empty(request()->get('from')) && ! empty(request()->get('to'))) {
                        $query->whereBetween('date', [request()->get('from'), request()->get('to')]);
                    }
                    if (request()->has('person') && ! empty(request()->get('person'))) {
                        $query->where('staff_id', request()->get('person'));
                    }
                })
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('waste.show', $data->id), 'type' => 'show', 'id' => false],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $persons = Staff::latest('id')->get(['id', 'name']);

        return view('pages.report.waste', compact('persons'));
    }

    /**
     * Display a listing of the ingredient report.
     */
    public function ingredientReport()
    {
        $this->authorize('show_ingredient');

        if (request()->ajax()) {
            $ingredients = Ingredient::query()->with('category:id,name', 'unit:id,name');

            return DataTables::eloquent($ingredients)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (request()->has('category') && ! empty(request()->get('category'))) {
                        $query->where('category_id', request()->get('category'));
                    }
                    if (request()->has('unit') && ! empty(request()->get('unit'))) {
                        $query->where('unit_id', request()->get('unit'));
                    }
                })
                ->toJson();
        }

        $categories = IngredientCategory::latest('id')->get(['id', 'name']);
        $units = IngredientUnit::latest('id')->get(['id', 'name']);

        return view('pages.report.ingredient', compact('categories', 'units'));
    }

    /**
     * Display a listing of the stock report.
     */
    public function stockReport()
    {
        $this->authorize('show_stock');
        // unserialize()
        if (request()->ajax()) {
            $stocks = Stock::query()->with('ingredient:id,category_id,unit_id,name,alert_qty,code', 'ingredient.unit:id,name', 'ingredient.category:id,name', 'ingredient.stock:id,ingredient_id,qty_amount');

            return DataTables::eloquent($stocks)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (request()->has('ingredient_id') && ! empty(request()->get('ingredient_id'))) {
                        $query->where('ingredient_id', request()->get('ingredient_id'));
                    }
                    if (request()->has('category') && ! empty(request()->get('category'))) {
                        $query->whereRelation('ingredient.category', 'category_id', '=', request()->get('category'));
                    }
                })
                ->addColumn('ingredient_name', function ($data) {
                    return "{$data->ingredient->name}({$data->ingredient->code})";
                })
                ->addColumn('alert_qty', function ($data) {
                    if ($data->ingredient->stock->qty_amount < $data->ingredient->alert_qty) {
                        return "<span class='text-danger'>{$data->ingredient->alert_qty} {$data->ingredient->unit->name}</span>";
                    }

                    return "{$data->ingredient->alert_qty} {$data->ingredient->unit->name}";
                })
                ->addColumn('stock_qty', function ($data) {
                    return "{$data->ingredient->stock->qty_amount} {$data->ingredient->unit->name}";
                })
                ->rawColumns(['ingredient_name', 'alert_qty', 'stock_qty'])
                ->toJson();
        }

        $categories = IngredientCategory::latest('id')->get(['id', 'name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name']);

        return view('pages.report.stock', compact('categories', 'ingredients'));
    }

    /**
     * Display a listing of the sales report.
     */
    public function saleReport()
    {
        $this->authorize('show_stock');

        if (request()->ajax()) {
            $orders = Order::query()->with('user:id,first_name,last_name');

            return DataTables::eloquent($orders)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (! empty(request()->get('from')) && ! empty(request()->get('to'))) {
                        $query->whereBetween('created_at', [request()->get('from'), request()->get('to')]);
                    }
                    if (request()->has('customer') && ! empty(request()->get('customer'))) {
                        $query->where('user_id', request()->get('customer'));
                    }
                })
                ->editColumn('created_at', function ($data) {
                    return format_date($data->created_at);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('orders.order.show', $data->id), 'type' => 'show', 'id' => false],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $customers = User::latest('id')->get(['id', 'first_name', 'last_name']);

        return view('pages.report.sale', compact('customers'));
    }

    /**
     * Display a listing of the profit loss report.
     */
    public function profitLossReport()
    {
        $this->authorize('show_stock');
        return view('pages.report.profit.index');
    }

    /**
     * show loss profit view
     */
    public function showLossProfitView()
    {
        
        $data = [];

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

        if (request()->has('from') && request()->has('to')) {
            $from = request()->get('from');
            $to = request()->get('to');

            $data['total_purchase_shipping_charge'] = $total_purchase_shipping_charge->whereBetween('date', [$from, $to]);
            $data['total_purchase_discount'] = $total_purchase_discount->whereBetween('date', [$from, $to]);
            $total_purchase_amount = $total_purchase_amount->whereBetween('date', [$from, $to]);

            $data['total_sell_discount'] = $total_sell_discount->whereBetween('created_at', [$from, $to]);
            $data['total_expense'] = $total_expense->whereBetween('created_at', [$from, $to]);

            $wastes = $wastes->whereBetween('created_at', [$from, $to]);

            $total_sell_shipping_charge = $total_sell_shipping_charge->whereBetween('created_at', [$from, $to]);
            $total_sell_service_charge = $total_sell_service_charge->whereBetween('created_at', [$from, $to]);

            $totalSalesIds = $totalSalesIds->whereBetween('created_at', [$from, $to]);

            $stock_plus_items = $stock_plus_items->whereHas('stockAdjustment', fn ($q) => $q->whereBetween('date', [$from, $to]));
            $stock_minus_items = $stock_minus_items->whereHas('stockAdjustment', fn ($q) => $q->whereBetween('date', [$from, $to]));
        }

        $data['total_purchase_shipping_charge'] = $total_purchase_shipping_charge->sum('shipping_charge');
        $data['total_purchase_discount'] = $total_purchase_discount->sum('discount_amount');
        $data['total_purchase'] = ($total_purchase_amount->sum('total_amount') + $data['total_purchase_shipping_charge']) - $data['total_purchase_discount'];
        $data['total_sell_discount'] = $total_sell_discount->sum('discount');
        $data['total_customer_reward'] = $total_customer_reward->sum('rewards') * setting('reward_exchange_rate');
        $data['total_expense'] = $total_expense->sum('amount');

        $data['total_waste'] = 0;
        foreach ($wastes->get(['id', 'items']) as $waste) {
            foreach (json_decode($waste->items) as $item) {
                $data['total_waste'] += $item->total;
            }
        }

        foreach ($stock_plus_items->get() as $pItem) {
            $total_stock_adjustments += $pItem->quantity_amount * $pItem->ingredient->purchase_price;
        }
        foreach ($stock_minus_items->where('consumption_status', 'minus')->get() as $mItem) {
            $minus_stock_adjustments += $mItem->quantity_amount * $mItem->ingredient->purchase_price;
        }
        $data['total_stock_adjustments'] = $total_stock_adjustments - $minus_stock_adjustments;

        $data['total_sell_shipping_charge'] = $total_sell_shipping_charge->whereIn('id', $orderSuccessIds)->sum('delivery_charge');
        $data['total_sell_service_charge'] = $total_sell_service_charge->whereIn('id', $orderSuccessIds)->sum('service_charge');

        $totalSalesIds = $totalSalesIds->whereIn('id', $orderSuccessIds)->pluck('id');
        $total_sales = OrderDetails::with('addons:order_details_id,price,quantity')->whereIn('order_id', $totalSalesIds)->get(['id', 'total_price']);
        $data['total_sales'] = 0;
        foreach ($total_sales as $total_sale) {
            $data['total_sales'] += $total_sale->total_price;
            foreach ($total_sale->addons as $addon) {
                $data['total_sales'] += ($addon->price * $addon->quantity);
            }
        }
        $data['total_sales'] = OrderDetails::whereIn('order_id', $totalSalesIds)->sum('total_price');

        $data['total_sell_vat'] = 0;
        $orderDetails = OrderDetails::whereIn('order_id', $totalSalesIds)->get(['price', 'quantity', 'total_price']);
        foreach ($orderDetails as $orderDetail) {
            $data['total_sell_vat'] += $orderDetail->total_price - ($orderDetail->price * $orderDetail->quantity);
        }

        // Calculation

        $data['gross_profit'] = $data['total_sales'] - $data['total_purchase'];

        $profit_plus = $data['total_sell_shipping_charge'] + $data['total_purchase_discount'] + $data['total_sell_service_charge'] + $data['total_sell_vat'];
        $profit_minus = $data['total_stock_adjustments'] + $data['total_expense'] + $data['total_waste'] + $data['total_purchase_shipping_charge'] + $data['total_sell_discount'] + $data['total_customer_reward'];

        $data['net_profit'] = ($data['gross_profit'] + $profit_plus) - $profit_minus;

        return view('pages.report.profit.card', compact('data'));
    }

    /**
     * Show Gross Profit
     */
    public function showGrossProfit($type)
    {
        if ($type == 'food') {

            // $foods = OrderDetails::query()->with('food:id,name')->withSum('order', 'grand_total');
            $ingredients = Ingredient::leftJoin('purchase_items', 'ingredients.id', '=', 'purchase_items.ingredient_id')
            ->leftJoin('order_details', function ($join) {
            $join->on('purchase_items.id', '=', 'order_details.variant_id')
                ->whereColumn('ingredients.id', '=', 'order_details.food_id');
            })
            ->select('ingredients.*','purchase_items.purchase_id', 'purchase_items.unit_price', 'purchase_items.quantity_amount', 'purchase_items.sold_qty')
            ->selectRaw('COALESCE(SUM(order_details.total_price), 0) as sale_amount')
            ->groupBy('ingredients.id', 'ingredients.category_id', 'ingredients.unit_id', 'ingredients.purchase_price', 'ingredients.alert_qty', 'ingredients.code', 'ingredients.created_at', 'ingredients.updated_at', 'ingredients.name', 'purchase_items.purchase_id', 'purchase_items.unit_price', 'purchase_items.quantity_amount', 'purchase_items.sold_qty')
            ->get()
            ->map(function ($ingredient) {
                $totalPurchasePrice = $ingredient->unit_price * $ingredient->quantity_amount;
                $grossProfit = $ingredient->sale_amount - $totalPurchasePrice;

                // Add gross profit to the ingredient data
                $ingredient->gross_profit = $grossProfit;

                return $ingredient;
            });
            return DataTables::of($ingredients)->addIndexColumn()->toJson();

        } 
        elseif ($type == 'invoice') {
            $orders = Order::query()->select('id', 'invoice', 'grand_total');

            return DataTables::eloquent($orders)->addIndexColumn()->filter(fn ($query) => $query->byStatus('success'))->toJson();
        } 
        elseif ($type == 'customer') {
            $customers = User::query()->select('id', 'first_name', 'last_name')->withSum('orders', 'grand_total');
            return DataTables::eloquent($customers)->addIndexColumn()->filter(fn ($q) => $q->whereRelation('orders', 'status', '=', 'success'))->toJson();
        } 
        elseif ($type == 'date') {
            $date_orders = Order::byStatus('success')->select('date', DB::raw('SUM(grand_total) as grand_total'))->groupBy('date')->orderBy('date', 'desc')->get();

            return DataTables::of($date_orders)->addIndexColumn()->toJson();
        }
    }
}
