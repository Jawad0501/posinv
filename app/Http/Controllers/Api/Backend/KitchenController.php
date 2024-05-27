<?php

namespace App\Http\Controllers\Api\Backend;

use App\Enum\OrderDetailsStatus;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\KitchenOrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @group Kitchen Management
 *
 * APIs to Kitchen manage
 */
class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\KitchenOrderCollection
     *
     * @apiResourceModel App\Models\Order
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index()
    {
        $orders = Order::with('tables:id,order_id,table_id', 'tables.table:id,number', 'user', 'orderDetails', 'orderDetails.addons')->whereNotIn('status', [OrderStatus::SUCCESS->value, OrderStatus::CANCEL->value])->latest('id')->get();

        return new KitchenOrderCollection($orders);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Order added successfully.'
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_ids' => ['required', 'array'],
            'item_ids.*' => ['required', 'integer', 'exists:order_details,id'],
            'status' => ['required', 'string', 'in:'.OrderDetailsStatus::COOKING->value.','.OrderDetailsStatus::READY->value.','.OrderDetailsStatus::SERVED->value],
        ]);

        $order = Order::with('orderDetails')->find($id, ['id', 'status']);

        foreach ($request->item_ids as $item_id) {
            $orderDetails = $order->orderDetails->where('id', $item_id)->first();
            if ($orderDetails) {
                if (in_array($orderDetails->status, [OrderDetailsStatus::PENDING->value, OrderDetailsStatus::READY->value, OrderDetailsStatus::COOKING->value])) {
                    $orderDetails->update(['status' => $request->status]);
                }
            }
        }

        $isReady = true;
        $isServed = true;
        $updatedOrder = Order::with('orderDetails:order_id,status')->find($order->id, ['id', 'status']);
        foreach ($updatedOrder->orderDetails as $orderDetail) {
            if ($orderDetail->status != OrderDetailsStatus::READY->value) {
                $isReady = false;
            }
            if ($orderDetail->status != OrderDetailsStatus::SERVED->value) {
                $isServed = false;
            }
        }

        if ($isReady) {
            $order->status = OrderStatus::READY->value;
            $order->save();
        }

        if ($isServed) {
            $order->status = OrderStatus::SERVED->value;
            $order->served_time = date('Y-m-d H:i:s');
            $order->save();
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
