<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IncomeRequest;
use App\Http\Resources\Backend\Finance\IncomeCollection;
use App\Http\Resources\Backend\Finance\IncomeResource;
use App\Models\Income;
use Illuminate\Http\Request;

/**
 * @group Income Management
 *
 * APIs to Income
 */
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\IncomeCollection
     *
     * @apiResourceModel App\Models\Income
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
        $incomes = Income::query()->with('staff:id,name', 'category:id,name');

        if ($request->has('keyword')) {
            $incomes = $incomes->where('date', 'like', "%$request->keyword%")
                ->orWhere('amount', 'like', "%$request->keyword%")
                ->orWhere('note', 'like', "%$request->keyword%")
                ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('category', 'name', 'like', "%$request->keyword%");
        }
        $incomes = $incomes->latest('id')->paginate();

        return new IncomeCollection($incomes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Income created successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(IncomeRequest $request)
    {
        $input = $request->validated();
        $input['staff_id'] = $request->person;
        $input['category_id'] = $request->category;

        Income::create($input);

        return response()->json(['message' => 'Income created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\IncomeResource
     *
     * @apiResourceModel App\Models\Income
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Income $income)
    {
        return new IncomeResource($income);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Income updated successfully."
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
    public function update(IncomeRequest $request, Income $income)
    {
        $input = $request->validated();
        $input['staff_id'] = $request->person;
        $input['category_id'] = $request->category;

        $income->update($input);

        return response()->json(['message' => 'Income updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Income deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Income $income)
    {
        $income->delete();

        return response()->json(['message' => 'Income deleted successfully']);
    }
}
