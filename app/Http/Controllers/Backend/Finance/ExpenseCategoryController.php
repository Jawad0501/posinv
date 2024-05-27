<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Api\Backend\Finance\ExpenseCategoryController as ApiExpenseCategoryController;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_expense_category');
        if (request()->ajax()) {
            return DataTables::eloquent(ExpenseCategory::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('expense-category.edit', $data->id), 'type' => 'edit', 'can' => 'edit_expense_category'],
                        ['url' => route('expense-category.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_expense_category'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.expense-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_expense_category');

        return view('pages.finance.expense-category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_expense_category');
        $api = new ApiExpenseCategoryController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        $this->authorize('edit_expense_category');

        return view('pages.finance.expense-category.form', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->authorize('create_expense_category');
        $api = new ApiExpenseCategoryController;

        return $api->update($request, $expenseCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->authorize('delete_expense_category');
        $api = new ApiExpenseCategoryController;

        return $api->destroy($expenseCategory);
    }
}
