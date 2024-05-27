<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Api\Backend\Finance\ExpenseController as ApiExpenseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Staff;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_expense');

        if (request()->ajax()) {
            $expense = Expense::query()->with('staff:id,name', 'category:id,name');

            return DataTables::of($expense)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('expense.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_expense'],
                        ['url' => route('expense.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_expense'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.expense.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_expense');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $categories = ExpenseCategory::active()->latest('id')->get(['id', 'name']);

        return view('pages.finance.expense.form', compact('persons', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $this->authorize('create_expense');
        $api = new ApiExpenseController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('edit_expense');
        $expense->load('staff:id,name', 'category:id,name');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $categories = ExpenseCategory::active()->latest('id')->get(['id', 'name']);

        return view('pages.finance.expense.form', compact('persons', 'categories', 'expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->authorize('create_expense');

        $api = new ApiExpenseController;

        return $api->update($request, $expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete_expense');
        $api = new ApiExpenseController;

        return $api->destroy($expense);
    }

    /**
     * category
     */
    public function category()
    {
        $categories = ExpenseCategory::active()->latest('id')->get(['id', 'name']);

        return response()->json($categories);
    }
}
