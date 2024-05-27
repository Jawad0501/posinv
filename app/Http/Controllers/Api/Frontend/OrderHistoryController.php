<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\OrderHistoryCollection;
use App\Http\Resources\Frontend\OrderHistoryResource;
use App\Http\Resources\Frontend\OrderResource;
use App\Models\Order;

/**
 * @group Authenticated order history management
 *
 * APIs to Authenticated order history management
 */
class OrderHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @apiResourceCollection App\Http\Resources\Frontend\OrderHistoryCollection
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
        $orders = Order::query()->with('orderDetails:id,order_id,food_id', 'orderDetails.food:id,name,image')->where('user_id', auth()->id())->latest('id')->get();

        return new OrderHistoryCollection($orders);
    }

    /**
     * Display the specified resource.
     *
     * @apiResource App\Http\Resources\Frontend\OrderHistoryResource
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
    public function show(string $invoice)
    {
        $orders = Order::query()->with('orderDetails', 'orderDetails.food:id,name,image', 'orderDetails.addons', 'orderDetails.addons.addon')->where('user_id', auth()->id())->where('invoice', $invoice)->firstOrFail();

        return new OrderHistoryResource($orders);
    }

    /**
     * Display a listing of the resource.
     *
     * @apiResource App\Http\Resources\Frontend\OrderResource
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
    public function order()
    {
        $order = Order::query()->with('orderDetails:id,order_id,food_id,variant_id,quantity,processing_time', 'orderDetails.food:id,name,image', 'orderDetails.variant:id,name', 'orderDetails.addons.addon:id,name')->where('user_id', auth()->id())->whereNotIn('status', [OrderStatus::SUCCESS->value, OrderStatus::CANCEL->value])->latest('id')->first();

        return new OrderResource($order);
    }
}
