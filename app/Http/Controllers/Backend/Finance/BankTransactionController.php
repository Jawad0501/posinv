<?php

namespace App\Http\Controllers\Backend\Finance;

use App\Http\Controllers\Api\Backend\Finance\BankTransactionController as ApiBankTransactionController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BankTransactionRequest;
use App\Models\Bank;
use App\Models\BankTransaction;
use Yajra\DataTables\Facades\DataTables;

class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_bank_transaction');
        if (request()->ajax()) {
            return DataTables::eloquent(BankTransaction::query()->with('bank:id,name'))
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('bank-transaction.edit', $data->id), 'type' => 'edit', 'can' => 'edit_bank_transaction'],
                        ['url' => route('bank-transaction.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_bank_transaction'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.finance.bank-transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_bank_transaction');
        $banks = Bank::latest('id')->get();

        return view('pages.finance.bank-transaction.form', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankTransactionRequest $request)
    {
        $this->authorize('create_bank_transaction');
        $api = new ApiBankTransactionController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankTransaction $bankTransaction)
    {
        $this->authorize('edit_bank_transaction');
        $banks = Bank::latest('id')->get();

        return view('pages.finance.bank-transaction.form', compact('banks', 'bankTransaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankTransactionRequest $request, BankTransaction $bankTransaction)
    {
        $this->authorize('edit_bank_transaction');
        $api = new ApiBankTransactionController;

        return $api->update($request, $bankTransaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankTransaction $bankTransaction)
    {
        $this->authorize('delete_bank_transaction');
        $api = new ApiBankTransactionController;

        return $api->destroy($bankTransaction);
    }
}
