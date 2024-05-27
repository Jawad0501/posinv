<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BankRequest;
use App\Http\Resources\Backend\Finance\BankCollection;
use App\Http\Resources\Backend\Finance\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;

/**
 * @group Bank Management
 *
 * APIs to Bank
 */
class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\BankCollection
     *
     * @apiResourceModel App\Models\Bank
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam keyword
     */
    public function index(Request $request)
    {
        $banks = Bank::query();

        if ($request->has('keyword')) {
            $banks = $banks->where('name', 'like', "%$request->keyword%")
                ->orWhere('account_name', 'like', "%$request->keyword%")
                ->orWhere('account_number', 'like', "%$request->keyword%")
                ->orWhere('branch_name', 'like', "%$request->keyword%")
                ->orWhere('balance', 'like', "%$request->keyword%");
        }
        $banks = $banks->latest('id')->paginate();

        return new BankCollection($banks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Bank added successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(BankRequest $request)
    {
        $input = $request->validated();
        $input['signature_image'] = $request->hasFile('signature_image') ? file_upload($request->signature_image, 'bank') : null;

        Bank::create($input);

        return response()->json(['message' => 'Bank added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\BankResource
     *
     * @apiResourceModel App\Models\Bank
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Bank $bank)
    {
        return new BankResource($bank);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Bank updated successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Unprocessable Content."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(BankRequest $request, Bank $bank)
    {
        $input = $request->validated();
        $input['signature_image'] = $request->hasFile('signature_image') ? file_upload($request->signature_image, 'bank', $bank->signature_image) : $bank->signature_image;

        $bank->update($input);

        return response()->json(['message' => 'Bank updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Bank deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Bank $bank)
    {
        delete_uploaded_file($bank->signature_image);
        $bank->delete();

        return response()->json(['message' => 'Bank deleted successfully']);
    }
}
