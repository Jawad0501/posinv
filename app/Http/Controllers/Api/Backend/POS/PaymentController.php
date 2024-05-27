<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Enum\OrderStatus;
use App\Enum\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\PaymentRequest;
use App\Models\GiftCardTransaction;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use App\Models\Payment;
use App\Models\ProductionUnit;
use App\Models\Stock;
use App\Models\Setting;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Artisan;
use DB;

/**
 * @group POS Payment management
 *
 * APIs to POS Payment management
 */
class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Payment successfully done.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The order id field is required. (and 3 more errors)",
     *   "errors": {
     *       "order_id": [
     *           "The order id field is required."
     *       ],
     *       "payment_method": [
     *           "The payment method field is required."
     *       ]
     *   }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function __invoke(PaymentRequest $request, $isPos = false, $cart_items)
    {
        try {
        
            $customer = User::find($request->customer_id, ['id', 'address_book', 'rewards', 'wallet']);
            
            if (! $customer) {
                $customer = User::query()->create(['password' => bcrypt(rand())]);
            }
        
            $service_charge = setting('service_charge');
            $delivery_charge = $request->order_type == 'Delivery' ? setting('delivery_charge') : 0;
            
            $address = isset($customer) && $customer?->address_book !== null ? (isset($customer->address_book[0]) ? $customer->address_book[0] : []) : [];

            $order = Order::create([
                'user_id' => $customer->id ?? null,
                'status' => OrderStatus::DUE->value,
                'type' => 'Dine In',
                'order_by' => auth()->user()->name,
                'discount' => $request->discount_amount,
                'service_charge' => $service_charge,
                'delivery_charge' => $delivery_charge,
                'address' => $address,
                'date' => date('Y-m-d'),
                'seen_time' => date('Y-m-d H:i'),
                'invoice' => null,
                'grand_total' => null,
                'token_no' => null,
                'delivered_time' => null,
                'customer_previous_due' => $customer->due,
                'settled_from_wallet' => false,
            ]);


            $subTotal = 0;


            foreach ($cart_items as $index => $cart) {

                $total = $cart['price'] * $cart['quantity'];

                $subTotal += $total;


                OrderDetails::create([
                    'order_id' => $order->id,
                    'food_id' => $index,
                    'purchase_id' => $cart['lot_id'],
                    'price' => $cart['price'],
                    'quantity' => $cart['quantity'],
                    'total_price' => $total,
                ]);


            }
            

            $order->update([
                'invoice' => generate_invoice($order->id),
                'grand_total' => ($subTotal + $service_charge + $delivery_charge) - $order->discount,
                'token_no' => sprintf('%s%03s', '', DB::table('orders')->whereDate('created_at', date('Y-m-d'))->count()),
            ]);

            // session()->forget('pos_cart');
            // session()->forget('pos_discount');

            $grand_total = $order->grand_total + $order->discount;
            $discount    = $order->discount;
            

            if ($request->has('discount_amount')) {
                if ($request->discount_type == 'fixed') {
                    $discount = $request->discount_amount;
                } 
                else {
                    $discount = ($request->discount_amount / 100) * $grand_total;
                }
            }
            
        
            if (! $request->has('include_service_charge')) {
                $grand_total -= $order->service_charge;
                $order->service_charge = 0; // decrease service chrage
            }

            $grand_total -= $discount;

            if($request->change_returned != null){
                $change_returned = true;
            }
            else{
                $change_returned = false;
                if($request->change_amount > 0){
                    $customer->wallet += $request->change_amount;
                    $customer->save();
                }   
            }

            if($request->due_amount > 0){
                $customer->due += $request->due_amount; 
                $customer->save();

                $order->status = OrderStatus::DUE->value;
                $order->save();

                
            }
            
            $payment = Payment::query()->create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'give_amount' => $request->give_amount,
                'change_amount' => ($request->give_amount - $grand_total) <= 0 ? 0 : ($request->give_amount - $grand_total),
                'grand_total' => $grand_total,
                'change_returned' => $change_returned,
                'due_amount' => $request->due_amount
            ]);

            $order->grand_total = $grand_total;
            if($payment->due_amount <= 0) {
                $order->status = OrderStatus::PAID->value;
            }
            $order->delivered_time = date('Y-m-d H:i:s');
            $order->save();

            if($request->settle_advance != null){
                $customer->wallet = $customer->wallet - $grand_total;
                $customer->save();
    
                $order->settled_from_wallet = true;
                $order->save();
            }


            // Stock out the ordered food quantity
            foreach ($order->orderDetails as $orderDetails) {
                $purchasedItem = PurchaseItem::where('id', $orderDetails->purchase_id)->first();
                $purchasedItem->sold_qty += $orderDetails->quantity;
                $purchasedItem->save();

                $stock = Stock::where('ingredient_id', $orderDetails->food_id)->first();
                $stock->qty_amount -= $orderDetails->quantity;
                $stock->save();
            }
           

            $new_amount = setting('cash_in_hand') + $payment->give_amount;

            if($new_amount <= 0){
                $new_amount = 0;
            }

            Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

            Artisan::call('cache:clear');


            if ($isPos) {
                return [
                    'status' => true,
                    'order_id' => $order->id,
                ];
            }

            
            // session()->flash('message', 'Payment successfully done.');
            return response()->json(['success' => 'Payment successfully done.']);
            // return redirect()->back();
        } catch (\Exception $e) {
            dd($e);
            return storeExceptionLog($e);
        }
    }

    /**
     * giveReward
     */
    public static function giveReward($order)
    {
        $rewards = 0;

        if (setting('reward_type') == 'By Menus') {
            $sum = 'quantity';
        } else {
            $sum = 'total_price';
        }

        if ($order->orderDetails()->sum($sum) >= setting('reward_items_or_amount')) {
            $rewards += setting('get_reward');
        }

        if ($rewards > 0) {
            $order->rewards = $rewards;
            $order->save();

            if ($order->user != null) {
                $order->user->rewards += $rewards;
                $order->user->rewards_available += $rewards;
                $order->user->save();
            }
        }

        return true;
    }
}
