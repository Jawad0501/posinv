<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CustomerRequest;
use App\Models\User;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_customer');
        if (request()->ajax()) {
            return DataTables::eloquent(User::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('client.customer.show', $data->id), 'type' => 'show', 'id'=>false, 'can' => 'show_customer'],
                        ['url' => route('client.customer.edit', $data->id), 'type' => 'edit', 'can' => 'edit_customer'],
                        ['url' => route('client.customer.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_customer'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.client.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_customer');

        return view('pages.client.customer.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $this->authorize('create_customer');

        return $request->saved();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        $this->authorize('show_customer');
        $customer = User::query()->where('id', $customer->id)->first();

        $orders = Order::query()->where('user_id', $customer->id);


        if (request()->ajax()) {
            
            return DataTables::eloquent($orders)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('orders.order.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_report'],
                        // ['url' => route('order.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_report'],
                        // ['url' => route('supplier.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_supplier'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.client.customer.show', compact('customer', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        $this->authorize('edit_customer');

        return view('pages.client.customer.form', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, User $customer)
    {
        $this->authorize('edit_customer');

        return $request->saved($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        $this->authorize('delete_customer');
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
