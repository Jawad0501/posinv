<?php

namespace App\Http\Controllers\Api\Backend\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SupplierRequest;
use App\Http\Resources\Backend\Master\SupplierCollection;
use App\Http\Resources\Backend\Master\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * @group Supplier Management
 *
 * APIs to Supplier
 */
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Master\SupplierCollection
     *
     * @apiResourceModel App\Models\Supplier
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam keyword
     *
     * @param  \Illuminate\Http\Request  $name
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::query();

        if ($request->has('keyword')) {
            $suppliers = $suppliers->where('name', 'like', "%$request->keyword%")
                ->orWhere('email', 'like', "%$request->keyword%")
                ->orWhere('phone', 'like', "%$request->keyword%")
                ->orWhere('reference', 'like', "%$request->keyword%")
                ->orWhere('address', 'like', "%$request->keyword%");
        }
        $suppliers = $suppliers->latest('id')->paginate();

        return new SupplierCollection($suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Supplier added successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SupplierRequest $request)
    {
        $input = $request->validated();
        $input['id_card_front'] = $request->hasFile('id_card_front') ? file_upload($request->file('id_card_front'), 'supplier') : null;
        $input['id_card_back'] = $request->hasFile('id_card_back') ? file_upload($request->file('id_card_back'), 'supplier') : null;
        
        Supplier::create($input);

        return response()->json(['message' => 'Supplier created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Master\SupplierResource
     *
     * @apiResourceModel App\Models\Supplier
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Master\SupplierResource
     */
    public function show(Supplier $Supplier)
    {
        return new SupplierResource($Supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Supplier updated successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $input = $request->validated();
        $input['id_card_front'] = $request->hasFile('id_card_front') ? file_upload($request->file('id_card_front'), 'supplier', $supplier->id_card_front) : $supplier->id_card_front;
        $input['id_card_back'] = $request->hasFile('id_card_back') ? file_upload($request->file('id_card_back'), 'supplier', $supplier->id_card_back) : $supplier->id_card_back;

        $supplier->update($input);

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Supplier deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Supplier $supplier)
    {
        delete_uploaded_file($supplier->id_card_front);
        delete_uploaded_file($supplier->id_card_back);
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
