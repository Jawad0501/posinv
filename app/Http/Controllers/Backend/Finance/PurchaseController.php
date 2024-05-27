<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Api\Backend\Finance\PurchaseController as ApiPurchaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PurchaseRequest;
use App\Models\Bank;
use App\Models\Ingredient;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Income;
use App\Models\IncomeCategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use PDF;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_purchase');
        if (request()->ajax()) {

            $purchases = Purchase::query()->with('supplier:id,name');

            return DataTables::of($purchases)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('purchase.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_purchase'],
                        ['url' => route('purchase.invoice', $data->id), 'type' => 'invoice', 'id' => false, 'can' => 'show_purchase'],
                        ['url' => route('purchase.due-collect', $data->id), 'type' => 'edit', 'can' => 'edit_purchase'],
                        ['url' => route('purchase.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_purchase'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_purchase');
        $suppliers = Supplier::latest('id')->get(['id', 'name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name', 'code']);
        $banks = Bank::latest('id')->get(['id', 'name']);

        return view('pages.finance.purchase.form', compact('suppliers', 'ingredients', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $this->authorize('create_purchase');
        try{
            if($request->payment_type == 'cash payment' || $request->payment_type == 'due payment'){

                if(setting('cash_in_hand') > $request->paid == true){
                    $supplier = Supplier::query()->where('id', $request->supplier)->first();
        
                    $purchase = Purchase::create([
                        'supplier_id' => $request->supplier,
                        'bank_id' => $request->bank,
                        'total_amount' => $request->total_amount,
                        'shipping_charge' => $request->shipping_charge,
                        'discount_amount' => $request->discount,
                        'paid_amount' => $request->paid,
                        'date' => $request->purchase_date,
                        'details' => $request->details,
                        'payment_type' => $request->payment_type,
                        'status' => $request->due_amount > 0 ? false : true,
                        'previous_due' => $supplier->due_amount,
                        'settled_from_advance' => false,
                    ]);
            
                    if($request->change_returned == null){
                        $change_returned = false;
            
                        $supplier->advance_amount += $request->change;
                        $supplier->save();
                    }
                    else{
                        $change_returned = true;
                    }
            
                    if($request->due != 0 || $request->due != '' || $request->due != null){
                        $supplier->due_amount += $request->due;
                        $supplier->save();
                    }
            
                    $purchase->reference_no = sprintf('%s%05s', '', $purchase->id);
                    $purchase->change_returned = $change_returned;
                    $purchase->change_amount = $request->change;
                    $purchase->due_amount = $request->due;
                    $purchase->save();
            
                    if($request->settle_advance != null){
                        $supplier->advance_amount -= $purchase->paid_amount;
                        $supplier->save();
            
                        $purchase->settled_from_advance = true;
                        $purchase->save();
                    }
            
                    foreach ($request->ingredient_id as $key => $ingredient_id) {
                        $ingredient = Ingredient::find($ingredient_id, ['id']);
                        if ($ingredient) {
            
                            PurchaseItem::create([
                                'purchase_id' => $purchase->id,
                                'ingredient_id' => $ingredient->id,
                                'unit_price' => $request->unit_price[$key],
                                'quantity_amount' => $request->quantity_amount[$key],
                                'total' => $request->total[$key],
                            ]);
            
                            $stock = Stock::where('ingredient_id', $ingredient->id)->first();
                            if ($stock) {
                                $stock->qty_amount += $request->quantity_amount[$key];
                                $stock->save();
                            } else {
                                Stock::create([
                                    'ingredient_id' => $ingredient->id,
                                    'qty_amount' => $request->quantity_amount[$key],
                                ]);
                            }
                        }
                    }
    
                    $new_amount = setting('cash_in_hand') - $purchase->paid_amount;

                    if($new_amount <= 0){
                        $new_amount = 0;
                    }
    
                    Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);
    
                    Artisan::call('cache:clear');
    
                    return response()->json(['message' => 'Purchase created successfully']);
    
                }
                else{
                    // dd('here');
                    return response()->json(['error' => 'Not Enough Cash In Register. Please Deposit First.']);
                }
            }
        }
        catch (\Exception $e) {
            dd($e);
            return storeExceptionLog($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $this->authorize('show_purchase');
        $purchase->load('supplier:id,name', 'bank:id,name', 'items', 'items.ingredient:id,unit_id,name,code', 'items.ingredient.unit:id,name');

        return view('pages.finance.purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $this->authorize('edit_purchase');
        $purchase->load('supplier:id,name', 'items', 'items.ingredient:id,unit_id,name,code', 'items.ingredient.unit:id,name');
        $suppliers = Supplier::latest('id')->get(['id', 'name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name', 'code']);
        $banks = Bank::latest('id')->get(['id', 'name']);

        return view('pages.finance.purchase.form', compact('suppliers', 'ingredients', 'purchase', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $this->authorize('edit_purchase');
        $supplier = Supplier::query()->where('id', $purchase->supplier_id)->first();


        foreach ($request->ingredient_id as $key => $ingredient_id) {
            $purchaseItem = PurchaseItem::where('purchase_id', $purchase->id)->where('ingredient_id', $ingredient_id)->first();

            if ($purchaseItem) {
                // Decrement stock quantity
                $stock = Stock::where('ingredient_id', $purchaseItem->ingredient_id)->first();
                $stock->qty_amount -= $purchaseItem->quantity_amount;
                $stock->save();

                // Update Purchase Item
                $purchaseItem->update([
                    'unit_price' => $request->unit_price[$key],
                    'quantity_amount' => $request->quantity_amount[$key],
                    'total' => $request->total[$key],
                ]);

                // Add stock quantity
                $stock->qty_amount += $request->quantity_amount[$key];
                $stock->save();
            } else {
                $ingredient = Ingredient::find($ingredient_id, ['id']);
                if ($ingredient) {

                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'ingredient_id' => $ingredient->id,
                        'unit_price' => $request->unit_price[$key],
                        'quantity_amount' => $request->quantity_amount[$key],
                        'total' => $request->total[$key],
                    ]);

                    $stock = Stock::where('ingredient_id', $ingredient->id)->first();
                    if ($stock) {
                        $stock->qty_amount += $request->quantity_amount[$key];
                        $stock->save();
                    } else {
                        Stock::create([
                            'ingredient_id' => $ingredient->id,
                            'qty_amount' => $request->quantity_amount[$key],
                        ]);
                    }
                }
            }
        }

        if($request->change_returned == null){
            $change_returned = false;

            $supplier->advance_amount += $request->change;
            $supplier->save();
        }
        else{
            $change_returned = true;
            if($purchase->change_returned == true){

            }
            else if($purchase->change_returned == false){
                $supplier->advance_amount -= $request->change;
                $supplier->save();
            }
        }

        if($request->due != 0 || $request->due != '' || $request->due != null){
            if($supplier->due_amount > 0){
                $new_paid_amount = $request->paid - $purchase->paid_amount;
                $supplier->due_amount -= $new_paid_amount;
                $supplier->save();
            }
            else {
                $supplier->due_amount += $request->due;
                $supplier->save();
            }
            
        }

        $previous_paid = $purchase->paid_amount;

        $purchase->update([
            'supplier_id' => $request->supplier,
            'bank_id' => $request->bank,
            'total_amount' => $request->total_amount,
            'shipping_charge' => $request->shipping_charge,
            'discount_amount' => $request->discount,
            'paid_amount' => $request->paid,
            'date' => $request->purchase_date,
            'payment_type' => $request->payment_type,
            'details' => $request->details,
            'change_amount' => $request->change,
            'due_amount' => $request->due,
            'change_returned' => $change_returned
        ]);

        if($request->settle_advance != null){
            if($purchase->settled_from_advance == false){
                $supplier->advance_amount -= $purchase->paid_amount;
                $supplier->save();

                $purchase->settled_from_advance = true;
                $purchase->save();
            }
            
        }
        else{
            if($purchase->settled_from_advance == true){
                $supplier->advance_amount += $previous_paid;
                $supplier->save();

                $purchase->settled_from_advance = false;
                $purchase->save();
            }
        }

        return response()->json(['message' => 'Purchase created successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete_purchase');
        $api = new ApiPurchaseController;

        return $api->destroy($purchase);
    }

    /**
     * ingredient
     */
    public function ingredient(Ingredient $ingredient)
    {
        $ingredient->load('unit:id,name');

        return response()->json($ingredient);
    }

    /**
     * itemDestroy
     */
    public function itemDestroy(PurchaseItem $purchaseItem)
    {
        $api = new ApiPurchaseController;

        return $api->itemDestroy($purchaseItem);
    }

    /**
     * supplier
     */
    public function supplier()
    {
        $suppliers = Supplier::latest('id')->get(['id', 'name']);

        return response()->json($suppliers);
    }

    /**
     * advance amount of a supplier
     */
    public function advanceAmount(Request $request)
    {
        $supplier = Supplier::where('id', $request->id)->first();
        $supplier_advance_amount = $supplier->advance_amount == null ? 0 : $supplier->advance_amount;

        return response()->json(['supplier_advance_amount' => $supplier_advance_amount]);
    }

    /**
     * Display Expired purchase items list.
     */
    public function items()
    {
        $this->authorize('show_purchase');
        if (request()->ajax()) {
            $items = PurchaseItem::query()->with('ingredient:id,unit_id,name,code', 'ingredient.unit:id,name');

            return DataTables::of($items)
                ->addIndexColumn()
                ->editColumn('expire_date', function ($data) {
                    return format_date($data->expire_date);
                })
                ->filter(function ($query) {
                    $query->where('expire_date', '<', date('Y-m-d'));
                })
                ->toJson();
        }

        return view('pages.finance.purchase.items');
    }

    public function showInvoice(Request $request) {
        $purchase = Purchase::where('id', $request->id)->first();
        $this->authorize('show_purchase');
        return view('pages.finance.purchase.invoice', compact('purchase'));
    }

    public function downloadInvoice(Request $request) {
        $purchase = Purchase::where('id', $request->id)->first();
        $pdf = PDF::loadView('mail.purchase-invoice-download', compact('purchase'));
        // dd($pdf);
        return $pdf->download('Purchase Invoice-'.$purchase->reference_no.'.pdf');
    }

    public function dueCollection(Request $request){
        $purchase = Purchase::where('id', $request->id)->first();
        return view('pages.finance.purchase.due-collection-form', compact('purchase'));
    }

    public function storeDueCollection(Request $request){
        $request->validate([
            'purchase_id' => 'required',
            'settle_advance' => 'nullable',
            'change_amount' => 'required|numeric',
            'change_returned' => 'nullable',
            'give_amount' => 'required|numeric',
            'due_amount' => 'required|numeric',
        ]);

        $purchase = Purchase::where('id', $request->purchase_id)->first();
        $supplier = Supplier::where('id', $purchase->supplier_id)->first();
        // $payment = $order->payment;
        
        if($request->settle_advance != null){
            $supplier->advance_amount = $supplier->advance_amount - $request->give_amount;
            $supplier->save();

            $purchase->settled_from_advance = true;
            $purchase->save();
        }

        if($request->change_returned != null){
            $change_returned = true;
        }
        else{
            $change_returned = false;
            if($request->change_amount > 0){
                $suplier->advance_amount = $supplier->advance_amoun + $request->change_amount;
                $supplier->save();
            }  
        }

        $supplier->due_amount = $supplier->due_amount - $request->give_amount;
        $supplier->save();

        // $payment->give_amount = $payment->give_amount + $request->give_amount;
        // $payment->change_amount = $request->change_amount;
        // $payment->due_amount = $request->due_amount;
        // $payment->change_returned = $change_returned;
        // $payment->save();

        $purchase->paid_amount = $purchase->paid_amount + $request->give_amount;
        $purchase->change_amount = $request->change_amount;
        $purchase->due_amount = $request->due_amount;
        $purchase->change_returned = $change_returned;
        $purchase->save();



        if($purchase->due_amount <= 0){
            $purchase->status = true;
            $purchase->save();
        }

        $new_amount = setting('cash_in_hand') + $request->give_amount;

        if($new_amount <= 0){
            $new_amount = 0;
        }

        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

        Artisan::call('cache:clear');

        Income::create([
            'staff_id' => auth()->user()->id,
            'order_id' => $purchase->id,
            'amount' => $request->give_amount,
            'category_id' => IncomeCategory::where('name', 'Due Payment')->first()->id,
            'date' => today()->format('Y-m-d'),
            'note' => 'Due Payment under Purchase Invoice '.$purchase->reference_no,
        ]);


        // return view('pages.order.due-collection', compact('order'));
        return response()->json(['message' => 'Due Paid Successfully']);
    }
}
