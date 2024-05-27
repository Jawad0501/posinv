<?php

namespace App\Http\Controllers\Backend\Order;

use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('show_order');
        if (request()->ajax()) {
            $orders = Order::query()->with('user:id,first_name,last_name');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('user_name', function ($data) {
                    return $data->user->full_name ?? null;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->status !== 'all') {
                        $query->where('status', $request->status);
                    }
                }, true)
                ->addColumn('action', function ($data) {
                    if($data->status == 'paid'){
                        return $this->buttonGroup([
                            ['url' => route('orders.order.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_order'],
                            ['url' => route('pos.order.invoice', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_order'],
                            ['url' => route('orders.order.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_order'],
                        ]);
                    }
                    else{
                        return $this->buttonGroup([
                            ['url' => route('orders.order.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_order'],
                            ['url' => route('pos.order.invoice', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_order'],
                            ['url' => route('pos.order.due-collection', $data->id), 'type' => 'show', 'can' => 'show_order'],
                            ['url' => route('orders.order.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_order'],
                        ]);
                    }
                    
                })
                ->rawColumns(['action', 'user_name'])
                ->toJson();
        }
        $status = ['all', OrderStatus::PENDING->value, OrderStatus::PROCESSING->value, OrderStatus::SUCCESS->value, OrderStatus::SERVED->value];

        return view('pages.order.index', compact('status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'OrderDetails', 'payment', 'OrderDetails.purchase', 'OrderDetails.addons', 'tables', 'tables.table:id,name,number');
        
        return view('pages.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {

        $order_payment = Payment::where('order_id', $order->id)->first();

        $order_details = OrderDetails::where('order_id', $order->id)->get();

        if($order_details != null){
            foreach($order_details as $item){
                $item->delete();
            }
        }
        
        if($order_payment != null){
            $order_payment->delete();
        }

        
        $order->delete();

        return response()->json(['message' => 'Order Deleted']);
    }
}
