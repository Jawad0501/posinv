<?php

namespace App\Http\Controllers\Backend;

use App\Enum\OrderDetailsStatus;
use App\Enum\OrderStatus;
use App\Http\Controllers\Api\Backend\KitchenController as ApiKitchenController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public $status;

    public function __construct()
    {
        $this->status = (object) [
            'pending' => OrderDetailsStatus::PENDING->value,
            'cooking' => OrderDetailsStatus::COOKING->value,
            'ready' => OrderDetailsStatus::READY->value,
            'served' => OrderDetailsStatus::SERVED->value,
            'cancel' => OrderDetailsStatus::CANCEL->value,
        ];
    }

    /**
     * index
     */
    public function index()
    {
        $this->authorize('show_kitchen');

        return view('pages.kitchen.index', ['status' => $this->status]);
    }

    /**
     * get kitchen ordered data
     */
    public function order(Request $request)
    {
        $this->authorize('show_kitchen');
        $orders = Order::with('tables', 'user', 'orderDetails', 'orderDetails.addons')->whereNotIn('status', [OrderStatus::SUCCESS->value, OrderStatus::CANCEL->value])->latest('id')->get();

        return view('pages.kitchen.order', [
            'orders' => $orders,
            'status' => $this->status,
        ]);
    }

    /**
     * order & order details update
     */
    public function orderUpdate(Request $request, $id)
    {
        $this->authorize('edit_kitchen');

        $api = new ApiKitchenController();

        return $api->update($request, $id);
    }
}
