<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Api\Backend\Finance\IncomeCategoryController as ApiIncomeCategoryController;

class IncomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_expense_category');
        if (request()->ajax()) {
            return DataTables::eloquent(IncomeCategory::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('income-category.edit', $data->id), 'type' => 'edit', 'can' => 'edit_expense_category'],
                        ['url' => route('income-category.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_expense_category'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.income-category.index');
    }

    public function category(){

    } 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_expense_category');

        return view('pages.finance.income-category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_expense_category');
        $api = new ApiIncomeCategoryController;

        return $api->store($request);
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
    public function edit(IncomeCategory $incomeCategory)
    {
        $this->authorize('edit_expense_category');

        return view('pages.finance.income-category.form', compact('incomeCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $this->authorize('create_expense_category');
        $api = new ApiIncomeCategoryController;

        return $api->update($request, $incomeCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomeCategory $incomeCategory)
    {
        $this->authorize('delete_expense_category');
        $api = new ApiIncomeCategoryController;

        return $api->destroy($incomeCategory);
    }
}
