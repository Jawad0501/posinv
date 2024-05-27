<?php

namespace App\Http\Controllers\Backend\POS;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Http\Controllers\Api\Backend\POS\OrderController as ApiOrderController;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Tablelayout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\IncomeCategory;
use PDF;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = new ApiOrderController;
        $orders = $order->index($request);

        return view('pages.pos.order.index', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            // 'order_type' => 'required|string|in:Dine In,Takeway,Delivery',
            'customer' => 'required|integer|exists:users,id',
            // 'table_id' => 'required_if:order_type,"Dine In"'
            'process_without_table' => 'required'
        ]);


        if (session()->get('pos_cart') == null) {
            return response()->json(['message' => 'Your cart is empty, please add menu to cart.'], 300);
        }
        try {

            $customer_id = $request->customer;

            $subTotal = 0;

            foreach (session()->get('pos_cart') as $index => $cart) {
                $total = $cart['price'] * $cart['quantity'];
                // $total_vat = $cart['tax_vat'] * $cart['quantity'];
                // $tax_vat = ($total / 100) * $total_vat;
                $subTotal += $total;
                

                // $orderDetails = OrderDetails::create([
                //     'order_id' => $order->id,
                //     'food_id' => $index,
                //     'price' => $cart['price'],
                //     'quantity' => $cart['quantity'],
                //     'total_price' => $total,
                //     'variant_id' => $cart['lot_id']
                // ]);


            }

            $discount = session()->has('pos_discount') ? session()->get('pos_discount')['amount'] : 0;
            
            // $customer = User::find($request->customer, ['id', 'address_book', 'rewards']);
            
            // if (! $customer) {
            //     $customer = User::query()->create(['password' => bcrypt(rand())]);
            // }

            

            // $service_charge = setting('service_charge');
            // $delivery_charge = $request->order_type == 'Delivery' ? setting('delivery_charge') : 0;

            // $address = isset($customer) && $customer?->address_book !== null ? (isset($customer->address_book[0]) ? $customer->address_book[0] : []) : [];
            // $order = Order::create([
            //     'user_id' => $customer->id ?? null,
            //     'status' => OrderStatus::DUE->value,
            //     'type' => $request->order_type,
            //     'order_by' => auth()->user()->name,
            //     'discount' => $discount,
            //     'service_charge' => $service_charge,
            //     'delivery_charge' => $delivery_charge,
            //     'address' => $address,
            //     'date' => date('Y-m-d'),
            //     'seen_time' => date('Y-m-d H:i'),
            // ]);

            

            // $order->update([
            //     'invoice' => generate_invoice($order->id),
            //     'grand_total' => ($subTotal + $service_charge + $delivery_charge) - $discount,
            //     'token_no' => sprintf('%s%03s', '', DB::table('orders')->whereDate('created_at', date('Y-m-d'))->count()),
            // ]);

            // session()->forget('pos_cart');
            // session()->forget('pos_discount');

            $finalize_order = [
                'grand_total'      => (float) $subTotal,
                // 'reward_amount'    => (float) setting('reward_exchange_rate') * $order->user->rewards_available ?? 0,
                'service_charge'   => (float) setting('service_charge'),
                // 'special_discount' => $order->user->discount ?? 0,
                'discount_amount'  => $discount ?? 0,
            ];

            $customer_wallet = User::where('id', $customer_id)->first()->wallet;

            // session()->forget('tables');

            return response()->json(['message' => 'Order added successfully', 'customer_id' => $customer_id, 'customer_wallet' => $customer_wallet, 'order_discount' => $discount, 'order_grand_total' => $subTotal, 'finalize_order' => $finalize_order]);
            // return redirect()->route('pos.payment.create', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            return storeExceptionLog($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $apiOrder = new ApiOrderController;
        $order = $apiOrder->show($order);

        return view('pages.pos.order.details', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        session()->forget('pos_cart');
        // session()->forget('tables');
        session()->forget('pos_discount');

        $cart = [];
        foreach ($order->orderDetails as $orderDetails) {
            $cart[$orderDetails->food_id] = [
                'order_details_id' => $orderDetails->id,
                'name' => $orderDetails->food->name,
                'price' => $orderDetails->variant->price ?? $orderDetails->food->price,
                'image' => $orderDetails->food->image,
                'calorie' => $orderDetails->food->calorie,
                'quantity' => $orderDetails->quantity,
                'tax_vat' => $orderDetails->vat / $orderDetails->quantity,
                'processing_time' => $orderDetails->processing_time,
                'note' => $orderDetails->note,
                'variant' => [
                    'name' => $orderDetails->variant->name ?? null,
                    'id' => $orderDetails->variant->id ?? null,
                    'price' => $orderDetails->variant->price ?? null,
                ],

            ];

            $addons = [];

            if ($orderDetails->addons->count() > 0) {
                foreach ($orderDetails->addons as $key => $orderAddonDetails) {
                    $addon = Addon::find($orderAddonDetails->addon_id);
                    if ($addon) {
                        $addons[$orderAddonDetails->addon_id]['name'] = $addon->name;
                        $addons[$orderAddonDetails->addon_id]['quantity'] = $orderAddonDetails->quantity;
                        $addons[$orderAddonDetails->addon_id]['price'] = $orderAddonDetails->price;
                    }
                    $cart[$orderDetails->food_id]['addons'] = $addons;
                }
            } else {
                $cart[$orderDetails->food_id]['addons'] = [];
            }
        }

        session()->put('pos_discount', [
            'amount' => $order->discount,
            'discount_type' => $order->discount_type,
        ]);

        // $tables = [];
        // foreach ($order->tables as $key => $table) {
        //     $tables[$key] = [
        //         'table_id' => $table->table_id,
        //         'person' => $table->total_person,
        //     ];
        // }

        // session()->put('tables', $tables);
        session()->put('pos_cart', $cart);

        session()->put('cart_update', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);

        return response()->json(session()->get('cart_update'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_type' => 'required|string|in:Dine In,Takeway,Delivery',
            'customer' => 'nullable|integer|exists:users,id',
        ]);

        if (session()->get('pos_cart') == null) {
            return response()->json(['message' => 'Your cart is empty, please add menu to cart.'], 300);
        }

        $customer = User::find($request->customer, ['id', 'address_book']);

        $discount = session()->has('pos_discount') ? session()->get('pos_discount')['amount'] : 0;

        $service_charge = setting('service_charge');
        $delivery_charge = $request->order_type == 'Delivery' ? setting('delivery_charge') : 0;

        $subTotal = 0;

        $address = isset($customer) && $customer?->address_book !== null ? (isset($customer->address_book[0]) ? $customer->address_book[0] : []) : [];
        $order->update([
            'user_id' => $customer->id ?? null,
            'type' => $request->order_type,
            'order_by' => auth()->user()->name,
            'discount' => $discount,
            'address' => $address,
            'date' => date('Y-m-d'),
        ]);

        foreach ($order->orderDetails as $currentOrderDetails) {
            $currentOrderDetails->delete();
        }

        foreach (session()->get('pos_cart') as $index => $cart) {

            $total = $cart['price'] * $cart['quantity'];
            $total_vat = $cart['tax_vat'] * $cart['quantity'];
            $tax_vat = ($total / 100) * $total_vat;
            $subTotal += $total;

            $orderDetails = OrderDetails::create([
                'order_id' => $order->id,
                'food_id' => $index,
                'variant_id' => $cart['variant']['id'],
                'processing_time' => $cart['processing_time'],
                'price' => $cart['price'],
                'quantity' => $cart['quantity'],
                'vat' => $cart['tax_vat'],
                'total_price' => ($total + $tax_vat),
                'note' => $cart['note'],
            ]);

            foreach ($cart['addons'] as $key => $cartAddon) {
                $addon = Addon::find($key);
                if ($addon) {
                    $subTotal += $addon->price * $cartAddon['quantity'];
                    $orderDetails->addons()->create([
                        'addon_id' => $addon->id,
                        'price' => $cartAddon['price'],
                        'quantity' => $cartAddon['quantity'],
                    ]);
                }
            }
        }

        // foreach ($order->tables as $orderTable) {
        //     $orderTable->table->available += $orderTable->total_person;
        //     $orderTable->table->save();
        //     $orderTable->delete();
        // }

        // if ($request->order_type == 'Dine In') {
        //     foreach (session()->get('tables') as $table) {
        //         $order->tables()->create([
        //             'table_id' => $table['table_id'],
        //             'total_person' => $table['person'],
        //         ]);
        //         Tablelayout::query()->find($table['table_id'])->decrement('
        //         ', $table['person']);
        //     }
        // }

        $order->grand_total = ($subTotal + $service_charge + $delivery_charge) - $discount;
        $order->invoice = generate_invoice($order->id);
        $order->save();

        session()->forget('pos_cart');
        session()->forget('pos_discount');
        session()->forget('tables');
        session()->forget('cart_update');

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Order Cancel the specified resource in storage.
     */
    public function cancel(Order $order)
    {
        $apiOrder = new ApiOrderController;

        return $apiOrder->cancel($order);
    }

    /**
     * Order Print the specified resource in storage.
     */
    public function print(Order $order)
    {
        $apiOrder = new ApiOrderController;
        $order = $apiOrder->show($order);

        return view('pages.pos.order.print', compact('order'));
    }

    /**
     * Order Print KOT of the specified resource in storage.
     */
    public function printKOT(Order $order)
    {
        $apiOrder = new ApiOrderController;
        $order = $apiOrder->show($order);

        return view('pages.pos.order.kot', compact('order'));
    }

    /**
     * Order Accept the specified resource in storage.
     */
    public function accept(Order $order)
    {
        $apiOrder = new ApiOrderController;

        return $apiOrder->accept($order);
    }

    /**
     * Display online order listing of the resource.
     */
    public function online()
    {
        $apiOrder = new ApiOrderController;
        $orders = $apiOrder->online();

        return view('pages.pos.order.online', compact('orders'));
    }
    
    public function wallet(Request $request)
    {
        $customer = User::where('id', $request->id)->first();
        $customer_wallet = $customer->wallet == null ? 0 : $customer->wallet;

        return response()->json(['customer_wallet' => $customer_wallet]);
    }

    public function invoice(Request $request){
        $order = Order::where('id', $request->id)->first();
        $this->authorize('show_order');
        return view('pages.order.invoice', compact('order'));
    }

    public function downloadInvoice(Request $request){
        // $url = url()->previous();
        // $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();

        // if($route == 'admin.invoice.index'){
        //     $quote = Quote::query()->with('items')->where('id', $id)->first();
        //     $pdf = PDF::loadView('mail.invoice-download', compact('quote'));
        //     return $pdf->download('Invoice-'.$quote->invoice.'.pdf');
        // }
        // else{
        //     $quote = Quote::query()->with('items')->where('id', decrypt($id))->first();
        //     $pdf = PDF::loadView('mail.invoice-download', compact('quote'));
        //     return $pdf->download('Invoice-'.$quote->invoice.'.pdf');
        // }

        $order = Order::where('id', $request->id)->first();
        $pdf = PDF::loadView('mail.invoice-download', compact('order'));
        // dd($pdf);
        return $pdf->download('Invoice-'.$order->invoice.'.pdf');
        // return view('mail.invoice-download', compact('order'));
    }

    public function dueCollection(Request $request){
        $order = Order::where('id', $request->id)->first();
        return view('pages.order.due-collection', compact('order'));
    }

    public function storeDueCollection(Request $request){
        $request->validate([
            'order_id' => 'required',
            'settle_advance' => 'nullable',
            'change_amount' => 'required|numeric',
            'change_returned' => 'nullable',
            'give_amount' => 'required|numeric',
            'due_amount' => 'required|numeric',
        ]);

        $order = Order::where('id', $request->order_id)->first();
        $customer = User::where('id', $order->user_id)->first();
        $payment = $order->payment;
        
        if($request->settle_advance != null){
            $customer->wallet = $customer->wallet - $request->give_amount;
            $customer->save();

            $order->settled_from_wallet = true;
            $order->save();
        }

        if($request->change_returned != null){
            $change_returned = true;
        }
        else{
            $change_returned = false;
            if($request->change_amount > 0){
                $customer->wallet = $customer->wallet + $request->change_amount;
                $customer->save();
            }  
        }

        $customer->due = $customer->due - $request->give_amount;
        $customer->save();

        $payment->give_amount = $payment->give_amount + $request->give_amount;
        $payment->change_amount = $request->change_amount;
        $payment->due_amount = $request->due_amount;
        $payment->change_returned = $change_returned;
        $payment->save();

        if($payment->due_amount <= 0){
            $order->status = OrderStatus::PAID->value;
            $order->save();
        }

        $new_amount = setting('cash_in_hand') + $request->give_amount;

        if($new_amount <= 0){
            $new_amount = 0;
        }

        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

        Artisan::call('cache:clear');

        Income::create([
            'staff_id' => auth()->user()->id,
            'order_id' => $order->id,
            'amount' => $request->give_amount,
            'category_id' => IncomeCategory::where('name', 'Due Collection')->first()->id,
            'date' => today()->format('Y-m-d'),
            'note' => 'Due Collection under Invoice '.$order->invoice,
        ]);


        // return view('pages.order.due-collection', compact('order'));
        return response()->json(['message' => 'Due Collected Successfully']);
    }
}
