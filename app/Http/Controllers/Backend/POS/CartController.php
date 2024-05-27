<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * index
     */
    public function index()
    {

        if (! session()->has('pos_cart')) {
            session()->put('pos_cart', []);
        }

        return view('pages.pos.partials.cart');
    }

    /**
     * add to cart item
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'lot' => 'required|integer',
            'quantity' => 'required|integer',
            'unit_sell_price' => 'required',
        ]);

        $food = Ingredient::find($request->food_id);
        $id = $food->id;

        $cart = session()->get('pos_cart');
        


        

        if (isset($cart)) {
            if(array_key_exists($id, $cart)){
                 // If the item already exists in the cart
                $cart[$id]['quantity'] = $request->quantity;
                if ($cart[$id]['quantity'] <= 0) {
                    unset($cart[$id]); // Remove the item from the cart if the quantity becomes zero or less
                } else {
                    // Update other attributes of the existing item if needed
                    $cart[$id]['price'] = $request->unit_sell_price;
                    $cart[$id]['lot_id'] = $request->lot;
                }
            }
            else {
                // If the item doesn't exist in the cart
                $cart[$id] = [
                    'name' => $food->name,
                    'price' => $request->unit_sell_price,
                    'quantity' => $request->quantity,
                    'lot_id' => $request->lot,
                ];
            }
           
        } 


        session()->put('pos_cart', $cart);

        return response()->json(['message' => 'Item added to cart successfully']);
    }

    /**
     * Update the specified resource in session
     *
     * @param  mixed  $request
     * @param  mixed  $id
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('pos_cart');
        if (isset($cart[$id])) {
            if ($request->role == 'plus' && $cart[$id]['quantity'] >= 1) {
                $cart[$id]['quantity']++;
            } elseif ($request->role == 'minus' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }
            session()->put('pos_cart', $cart);
        }

        return response()->json(['message' => 'Item updated successfully']);
    }

    /**
     * Remove the cart resource from storage.
     */
    public function destroy($id = null)
    {
        $this->authorize('delete_pos');
        if ($id === null) {
            session()->put('pos_cart', []);

            return response()->json(['message' => 'Cart delete successfully.']);
        }

        $cart = session()->get('pos_cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('pos_cart', $cart);
        }

        if (count(session()->get('pos_cart')) <= 0) {
            session()->forget('discount');
        }

        return response()->json(['message' => 'Item removed successfully']);
    }

    /**
     * show Discount Form
     */
    public function showDiscountForm()
    {
        return view('pages.pos.partials.discountForm');
    }

    /**
     * show Discount Fomm
     */
    public function discountStore(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'discount_type' => 'required|string|in:fixed,percentage',
        ]);

        if (session()->get('pos_cart') == null) {
            return response()->json(['message' => 'You dont have any menus in your cart. Please add menu in your cart.'], 300);
        }

        if ($request->discount_type == 'fixed') {
            $discount = $request->amount;
        } else {
            $subtotal = 0;
            foreach (session()->get('pos_cart') as $cart) {
                $subtotal += $cart['price'] * $cart['quantity'];
                foreach ($cart['addons'] as $addon) {
                    $subtotal += ($addon['quantity'] * $addon['price']);
                }
            }
            $discount = ($request->amount / 100) * $subtotal;
        }

        session()->put('pos_discount', [
            'amount' => $discount,
            'discount_type' => $request->discount_type,
        ]);

        return response()->json(['message' => 'Discount given successfully']);

    }
}
