<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class POSController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $cart = session()->get('pos_cart');

        // dd($cart);
        return view('pages.pos.index');
    }
}
