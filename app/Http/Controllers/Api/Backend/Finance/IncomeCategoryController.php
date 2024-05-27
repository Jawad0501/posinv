<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Finance\IncomeCategoryCollection;
use App\Http\Resources\Backend\Finance\IncomeCategoryResource;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

/**
 * @group Income Category Management
 *
 * APIs to Income Category
 */
class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\ExpenseCategoryCollection
     *
     * @apiResourceModel App\Models\ExpenseCategory
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
        $categories = IncomeCategory::query();

        if ($request->has('keyword')) {
            $categories = $categories->where('name', 'like', "%$request->keyword%");
        }
        $categories = $categories->latest('id')->paginate();

        return new IncomeCategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "New category added successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        IncomeCategory::create(['name' => $request->name]);

        return response()->json(['message' => 'Income Category created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\ExpenseCategoryResource
     *
     * @apiResourceModel App\Models\ExpenseCategory
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return new ExpenseCategoryResource($expenseCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Category added successfully."
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
     */
    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $incomeCategory->update(['name' => $request->name]);

        return response()->json(['message' => 'Income category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Category deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(IncomeCategory $incomeCategory)
    {
        $incomeCategory->delete();

        return response()->json(['message' => 'Income category deleted successfully']);
    }
}
