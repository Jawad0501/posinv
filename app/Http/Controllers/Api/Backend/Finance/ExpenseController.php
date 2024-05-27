<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ExpenseRequest;
use App\Http\Resources\Backend\Finance\ExpenseCollection;
use App\Http\Resources\Backend\Finance\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

/**
 * @group Expense Management
 *
 * APIs to Expense
 */
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\ExpenseCollection
     *
     * @apiResourceModel App\Models\Expense
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
        $expenses = Expense::query()->with('staff:id,name', 'category:id,name');

        if ($request->has('keyword')) {
            $expenses = $expenses->where('date', 'like', "%$request->keyword%")
                ->orWhere('amount', 'like', "%$request->keyword%")
                ->orWhere('note', 'like', "%$request->keyword%")
                ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('category', 'name', 'like', "%$request->keyword%");
        }
        $expenses = $expenses->latest('id')->paginate();

        return new ExpenseCollection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Expense created successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(ExpenseRequest $request)
    {
        $input = $request->validated();
        $input['staff_id'] = $request->person;
        $input['category_id'] = $request->category;

        Expense::create($input);

        return response()->json(['message' => 'Expense created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\ExpenseResource
     *
     * @apiResourceModel App\Models\Expense
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Expense $expense)
    {
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Expense updated successfully."
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
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $input = $request->validated();
        $input['staff_id'] = $request->person;
        $input['category_id'] = $request->category;

        $expense->update($input);

        return response()->json(['message' => 'Expense updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Expense deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
