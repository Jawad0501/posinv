<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use App\Models\Purchase;
use App\Models\Supplier;

class SupplierLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('show_stock');
        if (request()->ajax()) {
            $purchases = Purchase::query()
                ->where('supplier_id', $request->get('supplier'))
                ->with('items')
                ->orderBy('created_at');
                // ->get()
                // ->map(function ($item) {
                //     if ($item->order_id === null) {
                //         $item['instance'] = 'order'; // Set instance to 'order' if no corresponding income data
                //     } else {
                //         $item['instance'] = 'due_collection'; // Set instance to 'due_collection' if there is income data
                //     }
                //     return $item;
                // });
        
            // // Create a key-value array mapping IDs to their corresponding 'instance' values
            // $idInstanceMap = $orders->pluck('instance', 'order_id')->toArray();
        
            // // Extract IDs for orders and due_collections
            // $orderIds = $orders->pluck('id')->toArray();
            // $dueCollectionIds = $orders->filter(function ($item) {
            //     return $item['instance'] === 'due_collection';
            // })->pluck('id')->toArray();
        
            // // Create a new query builder instance for all orders
            // $dataTableQuery = Order::query()->whereIn('id', $orderIds);
        
            // // Attach the 'instance' column to the query builder
            // $dataTableQuery->selectRaw('*, CASE WHEN id IN (' . implode(',', $dueCollectionIds) . ') THEN "due_collection" ELSE "order" END AS instance');
        
            // dd($purchases);
            return DataTables::eloquent($purchases)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
                        $query->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [request()->get('from'), request()->get('to')]);
                    }
                    if (request()->has('supplier') && !empty(request()->get('supplier'))) {
                        $query->where('supplier_id', request()->get('supplier'));
                    }
                })
                ->editColumn('total_items', function($data) {
                    
                    return $data->items->sum('quantity_amount');

                })
                ->editColumn('total', function($data) {
                //     $amount_total = 0;

                //    if($data->return->count() > 0){
                        
                //         foreach($data->return as $return){
                //             foreach($return->items as $item){
                //                 $amount_total = $amount_total + $item->total_price;
                //             }
                //         }

                //     }
                //     else{
                //         $amount_total = 0;
                //     }

                    return $data->total_amount;
                })
                ->toJson();
        }
        
        
        
        


        $suppliers = Supplier::latest('id')->get();
        $first_supplier = Supplier::query()->first();

        return view('pages.ledger.supplier-ledger', compact('suppliers', 'first_supplier'));
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

    public function supplier(Request $request){
        $supplier = Supplier::where('id', $request->supplier_id)->first();
        return response()->json(['supplier' => $supplier]);
    }
}
