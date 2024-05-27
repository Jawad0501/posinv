<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductReturnRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProductReturn;
use App\Models\Ingredient;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Bank;
use App\Models\Order;
use App\Models\Income;
use App\Models\Expense;
use App\Models\OrderDetails;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\ReturnItem;
use App\Models\Stock;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class ProductReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_supplier');
        if (request()->ajax()) {
            $returns = ProductReturn::query();

            return DataTables::eloquent($returns)
                ->addIndexColumn()
                ->editColumn('person', function($data) {
                    if($data->product_return_person == 'supplier'){
                        $supplier = Supplier::where('id', $data->supplier_id)->first();
                        return $supplier->name;
                    }
                    else{
                        $customer = User::find($data->client_id);
                        // dd($customer);
                        return $customer->full_name;
                    }
                })
                ->editColumn('order_invoice_no', function($data){
                    if($data->product_return_person == 'supplier'){
                        $order = Purchase::where('id', $data->orderdetail_id)->first();
                        return $order->reference_no;
                    }
                    else{
                        $order = Order::where('id', $data->orderdetail_id)->first();
                        return $order->invoice_no;
                    }
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('returns.return.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_supplier'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.return.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_supplier');
        $suppliers = Supplier::latest('id')->get(['id', 'name']);
        $customers = User::latest('id')->get(['id', 'first_name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name', 'code']);
        $banks = Bank::latest('id')->get(['id', 'name']);

        return view('pages.return.form', compact('ingredients', 'suppliers', 'banks', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductReturnRequest $request)
    {
        
        $this->authorize('create_supplier');

        if($request->vendor == 'customer'){
            $order = Order::query()->where('invoice', $request->invoice_no)->first();

            if($order == null) {
                return response()->json(['error' => 'Invoice Not Found. Please Re-chek']);
            }

            $order_details = OrderDetails::query()->where('order_id', $order->id);
            $customer = User::where('id', $request->customer)->first();

            $related_id = $order->id;
            
        }
        elseif($request->vendor == 'supplier'){
            $purchase = Purchase::query()->where('reference_no', $request->ref_number)->first();

            if($purchase == null) {
                return response()->json(['error' => 'Invoice Not Found. Please Re-chek']);
            }

            $purchase_items = PurchaseItem::where('purchase_id', $purchase->id)->get();
            $supplier = Supplier::where('id', $request->supplier)->first();

            $related_id = $purchase->id;

        }

        $product_return = ProductReturn::create([
            'orderdetail_id' => $related_id,
            'product_return_person' => $request->vendor,
            'supplier_id' => isset($supplier) ? $supplier->id : null,
            'client_id' => isset($customer) ? $customer->id : null,
            'invoice_no' => $request->invoice_no,
            'return_date' => $request->return_date,
            'payment_method' => $request->payment_type,
            'bank_id' => $request->bank,
            'grand_total' => $request->grand_total,
            'paid_amount' => $request->paid,
            'due_amount' => $request->due,
            'status' => $request->paid < $request->grand_total ? false : true,
        ]);

        $product_return_id = $product_return->id;
        $product_return->return_invoice = generate_invoice($product_return_id);
        $product_return->save();


        
        if(isset($purchase_items)){
            $ingredients = $request->ingredient_id;
            $orderData = [];
            foreach ($ingredients as $index => $ingredientId) {
                $orderData[] = [
                    'ingredient_id' => $ingredientId,
                    'unit_price' => $request->unit_price[$index],
                    'quantity_amount' => $request->quantity_amount[$index],
                    'total_amount' => $request->unit_price[$index] * $request->quantity_amount[$index]
                ];
            }

            foreach($orderData as $item){
                $purchase_instance = $purchase_items->where('ingredient_id', $item['ingredient_id'])->first();

                if($purchase_instance == null){
                    return response()->json(['error' => 'Product' . Ingredient::where('id', $item['ingredient_id'])->first()->name . ' Does Not Exist In This Order. Please Check Order Invoice.']);
                }

                // $purchase_lot = PurchaseItem::where('id', $order_instance->purchase_id)->first();
                
                $purchase_instance->quantity_amount -= $item['quantity_amount'];
                $purchase_instance->total = $purchase_instance->quantity_amount * $purchase_instance->unit_price;
                // dd($order_instance);
                $purchase_instance->save();

                $stock = Stock::where('ingredient_id', $item['ingredient_id'])->first();
                $stock->qty_amount = $stock->qty_amount - $item['quantity_amount'];
                $stock->save();

                // $purchase_lot->sold_qty -= $item['quantity_amount'];
                // $purchase_lot->save();

                $cumulative_amount = ($purchase_instance->unit_amount * $item['quantity_amount']) - $item['total_amount'];

            
                if($cumulative_amount < 0) {
                    $category = IncomeCategory::where('name', 'Product Return')->first();
                    $income = Income::create([
                        'staff_id' => auth()->user()->id,
                        'category_id' => $category->id,
                        'date' => today()->format('Y-m-d'),
                        'amount' => $cumulative_amount,
                    ]);
                }
                elseif($cumulative_amount > 0) {
                    $category = ExpenseCategory::where('name', 'Product Return')->first();
                    $expense = Expense::create([
                        'staff_id' => auth()->user()->id,
                        'category_id' => $category->id,
                        'date' => today()->format('Y-m-d'),
                        'amount' => abs($cumulative_amount,)
                    ]);
                }
            }

            $purchase->total_amount = $purchase->total_amount - $product_return->grand_total;
            $purchase->save();
            if($purchase->status == true){
                $purchase->paid_amount = $purchase->total_amount;
                $purchase->change_amount = 0;
                $purchase->due_amount = 0;
                $purchase->save();
                
                if($product_return->due_amount > 0){
                    $supplier->advance_amount = $supplier->advance_amount + $product_return->due_amount;
                    $supplier->save();
                }

                if($product_return->paid_amount > 0){
                    $new_amount = setting('cash_in_hand') + $product_return->paid_amount;

                    if($new_amount <= 0){
                        $new_amount = 0;
                    }

                    Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

                    Artisan::call('cache:clear');
                }
            }
            else{
                if(($purchase->total_amount - $product_return->grand_total) < $purchase->paid_amount){
                    $purchase->paid_amount = $purchase->total_amount - $product_return->grand_total;

                    if($product_return->paid_amount > 0){
                        $new_amount = setting('cash_in_hand') + $product_return->paid_amount;

                        if($new_amount <= 0){
                            $new_amount = 0;
                        }

                        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

                        Artisan::call('cache:clear');
                    }

                    if($product_return->due_amount > 0){
                        $supplier->advance_amount = $supplier->advance_amount + ($order->payment->give_amount - ($order->grand_total - $product_return->grand_total));
                        $supplier->save();
                    }
                    $purchase->status = true;

                    $purchase->due_amount = 0;
                }
                else {
                    $purchase->due_amount = $purchase->due_amount - $product_return->grand_total;
                }
            }
            // $order->grand_total = $order->grand_total - $product_return->grand_total;
            // $order->payment->grand_total = $order->grand_total;
            // $order->payment->save();
            $purchase->save();
        }

        if(isset($order_details)){
            $ingredients = $request->ingredient_id;
            $orderData = [];
            foreach ($ingredients as $index => $ingredientId) {
                $orderData[] = [
                    'ingredient_id' => $ingredientId,
                    'unit_price' => $request->unit_price[$index],
                    'quantity_amount' => $request->quantity_amount[$index],
                    'total_amount' => $request->unit_price[$index] * $request->quantity_amount[$index]
                ];
            }

            $subTotal =  0;

            foreach($orderData as $item){
                $order_instance = $order_details->where('food_id', $item['ingredient_id'])->first();
            
                if($order_instance == null){
                    return response()->json(['message' => 'Product' . Ingredient::where('id', $item['ingredient_id'])->first()->name . ' Does Not Exist In This Order. Please Check Order Invoice.'], 422);
                }

                $purchase_lot = PurchaseItem::where('id', $order_instance->purchase_id)->first();
                
                $order_instance->quantity -= $item['quantity_amount'];
                $order_instance->total_price = $order_instance->quantity * $order_instance->price;
                // dd($order_instance);
                $order_instance->save();

                $subTotal = $subTotal + $order_instance->total_price;

                $purchase_lot->sold_qty -= $item['quantity_amount'];
                $purchase_lot->save();

                $stock = Stock::where('ingredient_id', $item['ingredient_id'])->first();
                $stock->qty_amount = $stock->qty_amount + $item['quantity_amount'];
                $stock->save();

                $cumulative_amount = ($order_instance->price * $item['quantity_amount']) - $item['total_amount'];

            
                if($cumulative_amount > 0) {
                    $category = IncomeCategory::where('name', 'Product Return')->first();
                    $income = Income::create([
                        'staff_id' => auth()->user()->id,
                        'category_id' => $category->id,
                        'date' => today()->format('Y-m-d'),
                        'amount' => $cumulative_amount,
                    ]);
                }
                elseif($cumulative_amount < 0) {
                    $category = ExpenseCategory::where('name', 'Product Return')->first();
                    $expense = Expense::create([
                        'staff_id' => auth()->user()->id,
                        'category_id' => $category->id,
                        'date' => today()->format('Y-m-d'),
                        'amount' => abs($cumulative_amount,)
                    ]);
                }
            }

            $order->grand_total = $order->grand_total - $product_return->grand_total;
            $order->save();
            if($order->status == 'paid'){
                $order->payment->give_amount = $order->grand_total;
                $order->payment->change_amount = 0;
                $order->payment->due_amount = 0;
                $order->payment->save();
                
                if($product_return->due_amount > 0){
                    $customer->wallet = $customer->wallet + $product_return->due_amount;
                    $customer->save();
                }

                if($product_return->paid_amount > 0){
                    $new_amount = setting('cash_in_hand') - $product_return->paid_amount;

                    if($new_amount <= 0){
                        $new_amount = 0;
                    }

                    Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

                    Artisan::call('cache:clear');
                }
            }
            else{
                if(($order->grand_total - $product_return->grand_total) < $order->payment->give_amount){
                    $order->payment->give_amount = $order->grand_total - $product_return->grand_total;
                    if($product_return->paid_amount > 0){
                        $new_amount = setting('cash_in_hand') - $product_return->paid_amount;

                        if($new_amount <= 0){
                            $new_amount = 0;
                        }

                        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

                        Artisan::call('cache:clear');


                    }
                    if($product_return->due_amount > 0){
                        $customer->wallet = $customer->wallet + $product_return->due_amount;
                        $customer->save();
                    }
                    $order->status = 'paid';

                    $order->payment->due_amount = 0;
                }
                else {
                    $order->payment->due_amount = $order->payment->due_amount - $product_return->grand_total;
                }
            }
            // $order->grand_total = $order->grand_total - $product_return->grand_total;
            $order->payment->grand_total = $order->grand_total;
            $order->payment->save();
            $order->save();


        }
        
        
        foreach($orderData as $item) {
            ReturnItem::create([
                'return_id' => $product_return->id,
                'ingredient_id' => $item['ingredient_id'],
                'price' => $item['unit_price'],
                'quantity' => $item['quantity_amount'],
                'total_price' => $item['unit_price'] * $item['quantity_amount'],
            ]);
        }

        return response()->json(['message' => 'Return created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
