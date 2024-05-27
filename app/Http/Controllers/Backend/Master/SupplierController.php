<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Api\Backend\Master\SupplierController as ApiSupplierController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SupplierRequest;
use App\Imports\SupplierImport;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_supplier');
        if (request()->ajax()) {
            $suppliers = Supplier::query();

            return DataTables::eloquent($suppliers)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return $data->status ? 'Active' : 'Disabled';
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('supplier.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_supplier'],
                        ['url' => route('supplier.edit', $data->id), 'type' => 'edit', 'can' => 'edit_supplier'],
                        ['url' => route('supplier.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_supplier'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.master.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_supplier');

        return view('pages.master.supplier.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $this->authorize('create_supplier');

        $api = new ApiSupplierController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $this->authorize('show_supplier');

        $supplier = Supplier::find($supplier->id);
        $purchases = Purchase::query()->where('supplier_id', $supplier->id);

        if (request()->ajax()) {
            
            return DataTables::eloquent($purchases)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('purchase.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_report'],
                        ['url' => route('purchase.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_report'],
                        // ['url' => route('supplier.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_supplier'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.master.supplier.show', compact('supplier', 'purchases'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $this->authorize('edit_supplier');

        return view('pages.master.supplier.form', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('edit_supplier');

        $api = new ApiSupplierController;

        return $api->update($request, $supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete_supplier');
        $api = new ApiSupplierController;

        return $api->destroy($supplier);
    }

    /**
     * showUploadForm
     */
    public function showUploadForm()
    {
        return view('pages.master.supplier.upload-form');
    }

    /**
     * upload
     */
    public function upload(Request $request)
    {
        $this->validate($request, ['file' => 'required|file|mimes:xlsx,csv|max:2048']);

        Excel::import(new SupplierImport, $request->file);

        return response()->json(['message' => 'Supplier uploaded successfully']);
    }
}
