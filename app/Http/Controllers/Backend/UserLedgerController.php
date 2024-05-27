<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\Order;


class UserLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        
        $this->authorize('show_stock');
        if (request()->ajax()) {
            $orders = Order::query()
                ->where('user_id', $request->get('customer'))
                ->with('payment', 'return', 'orderDetails')
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
        
            return DataTables::eloquent($orders)
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (!empty(request()->get('from')) && !empty(request()->get('to'))) {
                        $query->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [request()->get('from'), request()->get('to')]);
                    }
                    if (request()->has('customer') && !empty(request()->get('customer'))) {
                        $query->where('user_id', request()->get('customer'));
                    }
                })
                ->editColumn('total_items', function($data) {
                //    return $data->orderDetails->sum('quantity');

                   $quantity_total = 0;

                   if($data->return->count() > 0){
                        foreach($data->return as $return){
                            foreach($return->items as $item){
                                $quantity_total = $quantity_total + $item->quantity;
                            }
                        }
                    }
                    else{
                        $quantity_total = 0;
                    }

                    if($quantity_total > 0){
                        return $data->orderDetails->sum('quantity'). '(-'.$quantity_total.')';
                    }
                    else{
                        return $data->orderDetails->sum('quantity');
                    }

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

                    return $data->grand_total;
                })
                ->toJson();
        }
        
        
        
        


        $customers = User::latest('id')->get(['id', 'first_name', 'last_name']);
        $first_customer = User::query()->first();

        return view('pages.ledger.index', compact('customers', 'first_customer'));
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

    public function printLedger(Request $request)
    {
        $customerId = $request->get('customer');
        $customer = User::where('id', $customerId)->first();
        $from = $request->from;
        $to = $request->to;

        // Query orders based on the provided parameters
        $ordersQuery = Order::query()->where('user_id', $customerId)->with('payment')->orderBy('created_at');

        if ($request->from != null) {
            $ordersQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to != null) {
            $ordersQuery->whereDate('created_at', '<=', $request->to);
        }

        $orders = $ordersQuery->get();

        // Calculate totals
        $grand_total = $orders->sum('grand_total');
        $give_total = $orders->sum('payment.give_amount');
        $change_total = $orders->sum('payment.change_amount');
        $due_total = $orders->sum('payment.due_amount');

        // Generate the PDF
        $pdf = PDF::loadView('mail.user-ledger', compact('orders', 'customer', 'from', 'to', 'grand_total', 'give_total', 'change_total', 'due_total'));

        // Create the directory if it doesn't exist
        $directory = 'temp/';
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($directory);

        // Save the PDF
        $pdfPath = storage_path('app/public/'.$directory.'Ledger-'.str_replace(' ', '', $customer->full_name).'.pdf');
        $pdf->save($pdfPath);

        // Return the URL of the PDF
        return response()->json(['url' => url('storage/app/public/'.$directory.'Ledger-'.str_replace(' ', '', $customer->full_name).'.pdf'), 'customer_full_name' => str_replace(' ', '', $customer->full_name)]);
    }

    public function customer(Request $request){
        $customer = User::where('id', $request->customer_id)->first();
        return response()->json(['customer' => $customer]);
    }

}
