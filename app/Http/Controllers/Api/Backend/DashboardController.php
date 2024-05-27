<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\DashboardResource;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * @group Dashboard Management
 *
 * APIs to Dashboard
 */
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @response status=200
     * {
     *    "data": {
     *        "pending_orders": 7,
     *        "success_orders": 2,
     *        "cancel_orders": 0,
     *        "daily_orders": 0,
     *        "weekly_orders": 0,
     *        "monthly_orders": 0,
     *        "yearly_orders": 2,
     *        "total_orders": 2,
     *        "daily_sales": 0,
     *        "weekly_sales": 0,
     *        "monthly_sales": 0,
     *        "yearly_sales": "436.00",
     *        "total_sales": "436.00",
     *        "order_history": {
     *            "data": [
     *                {
     *                    "date": "27-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00010",
     *                    "grand_total": "108.90",
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "27-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": null,
     *                    "grand_total": null,
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "27-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00008",
     *                    "grand_total": "129.50",
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "27-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00007",
     *                    "grand_total": "79.50",
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "24-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00006",
     *                    "grand_total": "196.00",
     *                    "status": "success"
     *                },
     *                {
     *                    "date": "10-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00005",
     *                    "grand_total": "240.00",
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "09-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00004",
     *                    "grand_total": "240.00",
     *                    "status": "pending"
     *                },
     *                {
     *                    "date": "09-11-2022",
     *                    "customer_name": "Aphrodite ",
     *                    "invoice": "KL00003",
     *                    "grand_total": "240.00",
     *                    "status": "success"
     *                },
     *                {
     *                    "date": "09-11-2022",
     *                    "customer_name": "Aphrodite ",
     *                    "invoice": "KL00002",
     *                    "grand_total": "121.00",
     *                    "status": "processing"
     *                },
     *                {
     *                    "date": "10-11-2022",
     *                    "customer_name": "User ",
     *                    "invoice": "KL00001",
     *                    "grand_total": "240.00",
     *                    "status": "pending"
     *                }
     *            ]
     *        },
     *        "online_orders": {
     *            "data": []
     *        },
     *        "top_items": {
     *            "data": [
     *                {
     *                    "name": "Sandwich",
     *                    "price": "64.00",
     *                    "total_sold_price": "632.37",
     *                    "total_ordered": "12"
     *                },
     *                {
     *                    "name": "Salad",
     *                    "price": "89.00",
     *                    "total_sold_price": "597.45",
     *                    "total_ordered": "9"
     *                }
     *            ]
     *        }
     *  }
     *}
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function __invoke(Request $request)
    {
        $data = new stdClass;

        $data->order_history = Order::with('user:id,first_name,last_name')->latest()->limit(10)->get();
        $data->online_orders = Order::with('user:id,first_name,last_name')->where('type', 'Online')->whereDate('created_at', date('Y-m-d'))->latest()->get();
        $foodIds = OrderDetails::with('food')->select('food_id', DB::raw('SUM(quantity) as popular'))->groupBy('food_id')->orderBy('popular', 'desc')->take(10)->pluck('food_id');

        $data->top_items = Food::withSum('orderDetails', 'total_price')->withSum('orderDetails', 'quantity')->whereIn('id', $foodIds)->active()->latest('id')->get();

        $data->pending_orders = Order::byStatus('pending')->count();
        $data->success_orders = Order::byStatus('success')->count();
        $data->cancel_orders = Order::byStatus('cancel')->count();

        $data->daily_orders = Order::byStatus('success')->whereDate('created_at', date('Y-m-d'))->count();
        $data->weekly_orders = Order::byStatus('success')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $data->monthly_orders = Order::byStatus('success')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $data->yearly_orders = Order::byStatus('success')->whereYear('created_at', date('Y'))->count();
        $data->total_orders = Order::byStatus('success')->count();

        $data->daily_sales = Order::byStatus('success')->whereDate('created_at', date('Y-m-d'))->sum('grand_total');
        $data->weekly_sales = Order::byStatus('success')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        $data->monthly_sales = Order::byStatus('success')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('grand_total');
        $data->yearly_sales = Order::byStatus('success')->whereYear('created_at', date('Y'))->sum('grand_total');
        $data->total_sales = Order::byStatus('success')->sum('grand_total');

        return new DashboardResource($data);
    }
}
