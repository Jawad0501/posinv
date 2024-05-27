<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\OrderRequest;
use App\Http\Resources\Backend\POS\OnlineOrderCollection;
use App\Http\Resources\Backend\POS\OrderCollection;
use App\Http\Resources\Backend\POS\OrderResource;
use App\Models\Addon;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Tablelayout;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group POS Order management
 *
 * APIs to POS Order management
 */
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\OrderCollection
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
     * @queryParam search Field to search by. Example: invoice/table/name
     */
    public function index(Request $request)
    {
        $orders = Order::query()->with('tables:order_id,table_id,total_person', 'tables.table:id,number', 'user:id,first_name,last_name,customer_id')->whereIn('status', [OrderStatus::DUE->value]);
        if ($request->has('keyword')) {
            $orders = $orders->where(function ($query) use ($request) {
                if ($request->search == 'table') {
                    $query->orWhereRelation('tables.table', 'number', 'like', "%$request->keyword%");
                } elseif ($request->search == 'name') {
                    $query->whereRelation('user', 'first_name', 'like', "%$request->keyword%")->orWhereRelation('user', 'last_name', 'like', "%$request->keyword%");
                } else {
                    $query->where('invoice', 'like', "%$request->keyword%");
                }
            });
        }
        $orders = $orders->latest('id')->get();

        return new OrderCollection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     "message": "Customer successfully stored."
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *    "message": "The items.0.quantity must be a string. (and 7 more errors)",
     *    "errors": {
     *        "items.0.quantity": [
     *            "The items.0.quantity must be a string."
     *        ],
     *        "items.1.quantity": [
     *            "The items.1.quantity must be a string."
     *        ],
     *        "items.0.addons.id": [
     *            "The items.0.addons.id field is required."
     *        ],
     *        "items.1.addons.id": [
     *            "The items.1.addons.id field is required."
     *        ],
     *        "items.0.addons.quantity": [
     *            "The items.0.addons.quantity field is required."
     *        ],
     *        "items.1.addons.quantity": [
     *            "The items.1.addons.quantity field is required."
     *        ],
     *        "tables.0.person": [
     *            "The tables.0.person field is required."
     *        ],
     *        "tables.1.person": [
     *            "The tables.1.person field is required."
     *        ]
     *    }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(OrderRequest $request)
    {
        $customer = User::find($request->customer, ['id', 'address_book']);
        if (! $customer) {
            $customer = User::query()->create(['password' => bcrypt(rand())]);
        }
        $discount = $request->discount;

        $service_charge = setting('service_charge');
        $delivery_charge = $request->order_type == 'Delivery' ? setting('delivery_charge') : 0;

        $subTotal = 0;

        $address = isset($customer) && $customer?->address_book !== null ? (isset($customer->address_book[0]) ? $customer->address_book[0] : []) : [];

        $order = Order::create([
            'user_id' => $customer->id ?? null,
            'rider_id' => $request->rider ?? null,
            'status' => 'processing',
            'type' => $request->order_type,
            'order_by' => auth()->user()->name,
            'discount' => $discount,
            'service_charge' => $service_charge,
            'delivery_charge' => $delivery_charge,
            'address' => $address,
            'date' => date('Y-m-d'),
            'seen_time' => date('Y-m-d H:i'),
        ]);

        foreach ($request->items as $item) {
            $food = Food::find($item['id'], ['id', 'processing_time', 'tax_vat']);
            $variant = Variant::find($item['variant_id'], ['id', 'price']);
            $price = $variant->price ?? $food->price;

            $total = $price * $item['quantity'];
            $tax_vat = ($total / 100) * $food->tax_vat;
            $subTotal += $total;

            $orderDetails = OrderDetails::create([
                'order_id' => $order->id,
                'food_id' => $food->id,
                'variant_id' => $item['variant_id'],
                'processing_time' => $food->processing_time,
                'price' => $price,
                'quantity' => $item['quantity'],
                'vat' => $food->tax_vat,
                'total_price' => ($total + $tax_vat),
                'note' => $item['note'],
            ]);

            foreach ($item['addons'] as $itemAddon) {
                $addon = Addon::find($itemAddon['id']);
                if ($addon) {
                    $subTotal += $addon->price * $itemAddon['quantity'];
                    $orderDetails->addons()->create([
                        'addon_id' => $addon->id,
                        'price' => $addon->price,
                        'quantity' => $itemAddon['quantity'],
                    ]);
                }
            }
        }

        if ($request->order_type == 'Dine In' && $request->has('tables')) {
            foreach ($request->tables as $table) {
                $order->tables()->create([
                    'table_id' => $table['id'],
                    'total_person' => $table['person'],
                ]);
                Tablelayout::query()->find($table['id'])->decrement('available', $table['person']);
            }
        }

        $order->update([
            'invoice' => generate_invoice($order->id),
            'grand_total' => ($subTotal + $service_charge + $delivery_charge) - $discount,
            'token_no' => sprintf('%s%03s', '', DB::table('orders')->whereDate('created_at', date('Y-m-d'))->count()),
        ]);

        return response()->json(['message' => 'Order added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\POS\OrderResource
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
    public function show(Order $order)
    {
        $order->load(
            'user:id,first_name,last_name,customer_id,discount',
            'orderDetails',
            'tables',
            'orderDetails.food:id,name,image',
            'orderDetails.variant:id,name',
            'orderDetails.addons'
        );

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     "message": "Customer successfully stored."
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *    "message": "The items.0.quantity must be a string. (and 7 more errors)",
     *    "errors": {
     *        "items.0.quantity": [
     *            "The items.0.quantity must be a string."
     *        ],
     *        "items.1.quantity": [
     *            "The items.1.quantity must be a string."
     *        ],
     *        "items.0.addons.id": [
     *            "The items.0.addons.id field is required."
     *        ],
     *        "items.1.addons.id": [
     *            "The items.1.addons.id field is required."
     *        ],
     *        "items.0.addons.quantity": [
     *            "The items.0.addons.quantity field is required."
     *        ],
     *        "items.1.addons.quantity": [
     *            "The items.1.addons.quantity field is required."
     *        ],
     *        "tables.0.person": [
     *            "The tables.0.person field is required."
     *        ],
     *        "tables.1.person": [
     *            "The tables.1.person field is required."
     *        ]
     *    }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(OrderRequest $request, Order $order)
    {
        $customer = User::find($request->customer, ['id', 'address_book']);
        $discount = $request->discount;

        $service_charge = setting('service_charge');
        $delivery_charge = $request->order_type == 'Delivery' ? setting('delivery_charge') : 0;

        $subTotal = 0;
        $address = isset($customer) && $customer?->address_book !== null ? (isset($customer->address_book[0]) ? $customer->address_book[0] : []) : [];
        $order->update([
            'user_id' => $customer->id ?? null,
            'rider_id' => $request->rider ?? null,
            'type' => $request->order_type,
            'order_by' => auth()->user()->name,
            'discount' => $discount,
            'address' => $address,
            'date' => date('Y-m-d'),
        ]);

        foreach ($order->orderDetails as $currentOrderDetails) {
            $currentOrderDetails->delete();
        }

        foreach ($request->items as $item) {
            $food = Food::find($item['id'], ['id', 'processing_time', 'tax_vat']);
            $variant = Variant::find($item['variant_id'], ['id', 'price']);
            $price = $variant->price ?? $food->price;

            $total = $price * $item['quantity'];
            $tax_vat = ($total / 100) * $food->tax_vat;
            $subTotal += $total;

            $orderDetails = $order->orderDetails()->create([
                'food_id' => $food->id,
                'variant_id' => $item['variant_id'],
                'processing_time' => $food->processing_time,
                'price' => $price,
                'quantity' => $item['quantity'],
                'vat' => $food->tax_vat,
                'total_price' => ($total + $tax_vat),
                'note' => $item['note'],
            ]);

            foreach ($item['addons'] as $itemAddon) {
                $addon = Addon::find($itemAddon['id']);
                if ($addon) {
                    $subTotal += $addon->price * $itemAddon['quantity'];
                    $orderDetails->addons()->create([
                        'addon_id' => $addon->id,
                        'price' => $addon->price,
                        'quantity' => $itemAddon['quantity'],
                    ]);
                }
            }
        }

        foreach ($order->tables as $orderTable) {
            $orderTable->table->available += $orderTable->total_person;
            $orderTable->table->save();
            $orderTable->delete();
        }

        if ($request->order_type == 'Dine In' && $request->has('tables')) {
            foreach ($request->tables as $table) {
                $order->tables()->create([
                    'table_id' => $table['id'],
                    'total_person' => $table['person'],
                ]);
                Tablelayout::query()->find($table['id'])->decrement('available', $table['person']);
            }
        }
        $order->grand_total = ($subTotal + $service_charge + $delivery_charge) - $discount;
        $order->invoice = generate_invoice($order->id);
        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Order Cancel the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     "message": "Order cancelled successfully."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function cancel(Order $order)
    {
        if (in_array($order->status, [OrderStatus::SUCCESS->value, OrderStatus::CANCEL->value])) {
            return response()->json(['message' => 'Not allowed to cancel this order'], 300);
        }

        $order->status = OrderStatus::CANCEL->value;
        $order->save();

        foreach ($order->tables as $orderTable) {
            $orderTable->table->available += $orderTable->total_person;
            $orderTable->table->save();
        }

        return response()->json(['message' => 'Order cancelled successfully.']);
    }

    /**
     * Order Accept the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     "message": "Order accepted successfully."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function accept(Order $order)
    {
        if (in_array($order->status, [OrderStatus::SUCCESS->value, OrderStatus::CANCEL->value])) {
            return response()->json(['message' => 'Not allowed to accespt this order'], 300);
        }

        $order->status = OrderStatus::PROCESSING->value;
        $order->seen_time = date('Y-m-d H:i');
        $order->save();

        return response()->json(['message' => 'Order accepted successfully.']);
    }

    /**
     * Display online order listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\OnlineOrderCollection
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
    public function online()
    {
        $orders = Order::with('user:id,first_name,last_name,image', 'orderDetails')->byStatus(OrderStatus::PENDING->value)->latest('id')->get(['id', 'user_id', 'invoice', 'grand_total', 'created_at']);

        return new OnlineOrderCollection($orders);
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
     */
    public function details(Order $order)
    {
        $order->load('user', 'orderDetails', 'payment', 'orderDetails.addons', 'tables', 'tables.table:id,name,number');

        return new OrderResource($order);
    }
}
