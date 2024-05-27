<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Api\Backend\Master\IngredientController as ApiIngredientController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IngredientRequest;
use App\Imports\IngredientImport;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\IngredientUnit;
use App\Models\PurchaseItem;
use App\Models\OrderDetails;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\ProductReturn;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('show_ingredient');
        if (request()->ajax()) {

            $ingredients = Ingredient::query()
            ->with('category:id,name', 'unit:id,name')
            ->leftJoin('order_details', 'ingredients.id', '=', 'order_details.food_id')
            ->leftJoin('stocks', 'ingredients.id', '=', 'stocks.ingredient_id')
            ->select('ingredients.*','stocks.id as stock_id', 'stocks.qty_amount as stock_qty', DB::raw('COALESCE(SUM(order_details.quantity), 0) as sale_qty'), DB::raw('(SELECT COALESCE(SUM(purchase_items.quantity_amount), 0) FROM purchase_items WHERE purchase_items.ingredient_id = ingredients.id) as purchase_qty'), DB::raw('(SELECT COALESCE(SUM(purchase_items.total), 0) FROM purchase_items WHERE purchase_items.ingredient_id = ingredients.id) as purchase_amount'), DB::raw('COALESCE(SUM(order_details.total_price), 0) as sale_amount'))
            ->groupBy('ingredients.id', 'ingredients.category_id', 'ingredients.unit_id', 'ingredients.name', 'ingredients.purchase_price', 'ingredients.alert_qty', 'ingredients.code', 'ingredients.created_at', 'ingredients.updated_at', 'stocks.id', 'stocks.qty_amount');


            return DataTables::of($ingredients)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('ingredient.show', $data->id), 'type' => 'show', 'id'=> false, 'can' => 'show_ingredient'],
                        ['url' => route('ingredient.edit', $data->id), 'type' => 'edit', 'can' => 'edit_ingredient'],
                        ['url' => route('ingredient.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_ingredient'],
                    ]);
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('stock_out')) {
                        $query->whereIn('id', session()->get('stock_out'));
                    }
                }, true)
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('pages.master.ingredient.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ingredient');
        $categories = IngredientCategory::active()->latest('id')->get();
        $units = IngredientUnit::active()->latest('id')->get();

        return view('pages.master.ingredient.form', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IngredientRequest $request)
    {
        $this->authorize('create_ingredient');

        $api = new ApiIngredientController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        $this->authorize('show_ingredient');

        $ingredient = Ingredient::query()
        ->with('category:id,name', 'unit:id,name')
        ->leftJoin('order_details', 'ingredients.id', '=', 'order_details.food_id')
        ->leftJoin('stocks', 'ingredients.id', '=', 'stocks.ingredient_id')
        ->select('ingredients.*', 'stocks.id as stock_id', 'stocks.qty_amount as stock_qty', DB::raw('COALESCE(SUM(order_details.quantity), 0) as sale_qty'), DB::raw('(SELECT COALESCE(SUM(purchase_items.quantity_amount), 0) FROM purchase_items WHERE purchase_items.ingredient_id = ingredients.id) as purchase_qty'), DB::raw('(SELECT COALESCE(SUM(purchase_items.total), 0) FROM purchase_items WHERE purchase_items.ingredient_id = ingredients.id) as purchase_amount'), DB::raw('COALESCE(SUM(order_details.total_price), 0) as sale_amount'))
        ->groupBy('ingredients.id', 'ingredients.category_id', 'ingredients.unit_id', 'ingredients.name', 'ingredients.purchase_price', 'ingredients.alert_qty', 'ingredients.code', 'ingredients.created_at', 'ingredients.updated_at', 'stocks.id', 'stocks.qty_amount')
        ->where('ingredients.id', $ingredient->id)
        ->first();

        
        $total_purchase_items = DB::table('purchase_items')
        ->where('ingredient_id', $ingredient->id)
        ->count();

        // Append additional properties to the $ingredient object
        $ingredient->stock_id = $ingredient->stock_id;
        $ingredient->stock_qty = $ingredient->stock_qty;
        $ingredient->sale_qty = $ingredient->sale_qty;
        $ingredient->purchase_qty = $ingredient->purchase_qty;
        $ingredient->purchase_amount = $ingredient->purchase_amount;
        $ingredient->sale_amount = $ingredient->sale_amount;
        $ingredient->total_purchase_items = $total_purchase_items;

        return view('pages.master.ingredient.show', compact('ingredient'));
    }


    public function fetchHistory(Request $request) {
        $ingredient_id = $request->id;
        $order_history = OrderDetails::where('food_id', $ingredient_id)->get()->map(function ($item) {
            $item['instance'] = 'order';
            
            $order_status = Order::where('id', $item['order_id'])->first();
            $item['status'] = $order_status->status == 'paid' ? 'paid' : 'due';
           
            return $item;
        });

        
        $purchase_history = PurchaseItem::where('ingredient_id', $ingredient_id)->get()->map(function ($item) {
            $item['instance'] = 'purchase';
            $purchase_status = Purchase::where('id', $item['purchase_id'])->first();
            $item['status'] = $purchase_status->status == true ? 'paid' : 'due';
            return $item;
        });
        
        $product_returns = ReturnItem::where('ingredient_id', $ingredient_id)->get()->map(function ($item) {
            $item['instance'] = 'return';
            $return_status = ProductReturn::where('id', $item['return_id'])->first();
            $item['status'] = $return_status->status == true ? 'paid' : 'due';
            return $item;
        });

        $mergedData = $order_history->map(function ($item) {
            return $item->toArray() + ['instance' => 'sale'];
        })->merge(
            $purchase_history->map(function ($item) {
                return $item->toArray() + ['instance' => 'purchase'];
            })
        )->merge(
            $product_returns->map(function ($item) {
                return $item->toArray() + ['instance' => 'return'];
            })
        );

            
        $sortedData = $mergedData->sortBy('created_at')->values()->all();
        // dd($sortedData);

        return DataTables::of($sortedData)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    if($data['instance'] == 'order'){
                        return $this->buttonGroup([
                            ['url' => route('orders.order.show', $data['order_id']), 'type' => 'show', 'id'=> false, 'can' => 'show_order'],
                        ]);
                    }
                    else if($data['instance'] == 'purchase'){
                        return $this->buttonGroup([
                            ['url' => route('purchase.show', $data['purchase_id']), 'type' => 'show', 'id'=> false, 'can' => 'show_purchase'],
                        ]);
                    }
                    else if($data['instance'] == 'return'){
                        return $this->buttonGroup([
                            ['url' => route('returns.return.show', $data['return_id']), 'type' => 'show', 'id'=> false, 'can' => 'show_ingredient'],
                        ]);
                    }
                    
                })
                ->editColumn('quantity', function($data) {
                    if($data['instance'] == 'order'){
                        return $data['quantity'];
                    }
                    else if($data['instance']== 'purchase'){
                        return $data['quantity_amount'];
                    }
                    else{
                        return $data['quantity'];  
                    }
                })
                ->editColumn('price', function($data) {
                    if($data['instance'] == 'order'){
                        return $data['price'];
                    }
                    else if($data['instance']== 'purchase'){
                        return $data['unit_price'];
                    }
                    else{
                        return $data['price'];  
                    }
                })
                ->toJson();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        $this->authorize('edit_ingredient');
        $categories = IngredientCategory::active()->latest('id')->get();
        $units = IngredientUnit::active()->latest('id')->get();

        return view('pages.master.ingredient.form', compact('categories', 'units', 'ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IngredientRequest $request, Ingredient $ingredient)
    {
        $this->authorize('edit_ingredient');

        $api = new ApiIngredientController;

        return $api->update($request, $ingredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $this->authorize('delete_ingredient');
        $api = new ApiIngredientController;

        return $api->destroy($ingredient);
    }

    /**
     * showUploadForm
     */
    public function showUploadForm()
    {
        return view('pages.master.ingredient.upload-form');
    }

    /**
     * upload
     */
    public function upload(Request $request)
    {
        $this->validate($request, ['file' => 'required|file|mimes:xlsx,csv|max:2048']);

        Excel::import(new IngredientImport, $request->file);

        return response()->json(['message' => 'Ingredient uploaded successfully']);
    }
}
