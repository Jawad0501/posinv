<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Expense;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('show_dashboard');

        $data = [];
        // Total Sales
        $data['total_sale'] = Order::query()->sum('grand_total');
        $data['total_paid_sale'] = Order::query()->where('status', 'paid')->sum('grand_total');
        $data['total_due_sale'] = Order::query()->where('status', 'due')->sum('grand_total');

        $data['total_expense'] = Expense::query()->sum('amount');

        $data['product_wise_profit'] = Order::query()->sum('grand_total') - Purchase::query()->sum('total_amount');

        $data['total_purchase'] = Purchase::query()->sum('total_amount');
        
        $data['total_payment'] = Purchase::sum('paid_amount');

        $data['stock_product_value'] = PurchaseItem::whereRaw('purchase_items.quantity_amount > purchase_items.sold_qty')
                                    ->selectRaw('SUM((purchase_items.quantity_amount - purchase_items.sold_qty) * purchase_items.unit_price) AS total_stock_value')
                                    ->value('total_stock_value');

        $data['nit_profit'] = $data['total_paid_sale'] - $data['total_payment'];


        $data['order_history'] = Order::with('user:id,first_name,last_name')->latest()->limit(10)->get();
        $data['online_orders'] = Order::with('user:id,first_name,last_name')->where('type', 'Online')->whereDate('created_at', date('Y-m-d'))->latest()->get();

        $foodIds = OrderDetails::with('food')->select('food_id', DB::raw('SUM(quantity) as popular'))->groupBy('food_id')->orderBy('popular', 'desc')->take(10)->pluck('food_id');
        $data['top_items'] = Food::withSum('orderDetails', 'total_price')->withSum('orderDetails', 'quantity')->whereIn('id', $foodIds)->active()->latest('id')->get();

        $data['pending_orders'] = Order::byStatus('pending')->count();
        $data['success_orders'] = Order::byStatus('success')->count();
        $data['cancel_orders'] = Order::byStatus('cancel')->count();

        $data['daily_orders'] = Order::byStatus('success')->whereDate('created_at', date('Y-m-d'))->count();
        $data['weekly_orders'] = Order::byStatus('success')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $data['monthly_orders'] = Order::byStatus('success')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $data['yearly_orders'] = Order::byStatus('success')->whereYear('created_at', date('Y'))->count();
        $data['total_orders'] = Order::byStatus('success')->count();

        $data['daily_sales'] = Order::byStatus('success')->whereDate('created_at', date('Y-m-d'))->sum('grand_total');
        $data['weekly_sales'] = Order::byStatus('success')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        $data['monthly_sales'] = Order::byStatus('success')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('grand_total');
        $data['yearly_sales'] = Order::byStatus('success')->whereYear('created_at', date('Y'))->sum('grand_total');
        $data['total_sales'] = Order::byStatus('success')->sum('grand_total');

        // $months = ['January', 'February', 'March', 'April', 'May', 'Jun', 'July', 'August', 'September', 'October', 'November', 'December'];
        // $months = ['Jan', 'Feb', 'Mar', 'April', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        // $days   = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thur", "Fri"];
        $data['sales'] = [];

        foreach ($months as $month) {
            $sale = Order::byStatus('success')->whereMonth('created_at', $month)->whereYear('created_at', date('Y'))->sum('grand_total');
            array_push($data['sales'], $sale);
        }

        // $data['types']      = ['Dine In', 'Takeaway', 'Delivery', 'Online'];
        $orderIds = [];
        $data['types'] = ['Dine In', 'Takeaway', 'Delivery', 'Online'];
        $data['type_sales'] = [];

        foreach ($data['types'] as $key => $type) {
            $orderIds[$type] = Order::byStatus('success')->where('type', $type)->pluck('id');
        }

        foreach ($orderIds as $id => $orderId) {
            $type_sales = OrderDetails::whereIn('order_id', $orderId)->sum('quantity');
            array_push($data['type_sales'], $type_sales);
        }

        return view('pages.dashboard', compact('data'));
    }
}
