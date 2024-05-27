<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Api\Backend\Finance\BankController as ApiBankController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BankRequest;
use App\Models\Bank;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_bank');
        if (request()->ajax()) {
            return DataTables::eloquent(Bank::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('bank.edit', $data->id), 'type' => 'edit', 'can' => 'edit_bank'],
                        ['url' => route('bank.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_bank'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.bank.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_bank');

        return view('pages.finance.bank.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankRequest $request)
    {
        $this->authorize('create_bank');
        $api = new ApiBankController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        $this->authorize('edit_bank');

        return view('pages.finance.bank.form', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankRequest $request, Bank $bank)
    {
        $this->authorize('edit_bank');
        $api = new ApiBankController;

        return $api->update($request, $bank);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $this->authorize('delete_bank');
        $api = new ApiBankController;

        return $api->destroy($bank);
    }
}
