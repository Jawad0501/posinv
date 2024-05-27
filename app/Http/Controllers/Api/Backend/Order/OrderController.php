<?php

namespace App\Http\Controllers\Api\Backend\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Order\OrderCollection;
use App\Http\Resources\Backend\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @group Orders Order Management
 *
 * APIs to Order
 */
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Order\OrderCollection
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
     * @queryParam keyword
     * @queryParam status
     */
    public function index(Request $request)
    {
        $orders = Order::with('user:id,first_name,last_name');
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

        if ($request->has('status')) {
            $orders = $orders->where('status', $request->status);
        }
        if ($request->has('type')) {
            $orders = $orders->where('type', ucfirst($request->type));
        }

        $orders = $orders->latest('id')->paginate();

        return new OrderCollection($orders);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Order\OrderResource
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
     * @return \App\Http\Resources\Backend\Order\OrderResource
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderDetails', 'payment', 'orderDetails.addons', 'tables', 'tables.table:id,name,number');

        return new OrderResource($order);
    }
}
