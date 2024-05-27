<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Backend\Finance\IncomeController as ApiIncomeController;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\IncomeRequest;
use App\Models\Income;
use App\Models\Staff;
use App\Models\IncomeCategory;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_expense');

        if (request()->ajax()) {
            $income = Income::query()->with('staff:id,name', 'category:id,name');

            return DataTables::of($income)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('income.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_expense'],
                        ['url' => route('income.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_expense'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.income.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_expense');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $categories = IncomeCategory::active()->latest('id')->get(['id', 'name']);

        return view('pages.finance.income.form', compact('persons', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        $this->authorize('create_expense');
        $api = new ApiIncomeController;

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
