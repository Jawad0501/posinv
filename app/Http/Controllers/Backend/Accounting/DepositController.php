<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_purchase');
        if (request()->ajax()) {

            $deposits = Deposit::query();

            return DataTables::of($deposits)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->created_at);
                })
                ->toJson();
        }

        return view('pages.accounting.deposit.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ingredient');

        return view('pages.accounting.deposit.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'note' => 'nullable|string'
        ]);

        $deposit = Deposit::create([
            'staff_id' => auth()->user()->id,
            'amount' => $request->amount,
            'note' => $request->note,
            'amount_before_deposit' => setting('cash_in_hand'),
        ]);

        $new_amount = setting('cash_in_hand') + $request->amount;
        
        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

        $deposit->amount_after_deposit = $new_amount;
        $deposit->save();

        Artisan::call('cache:clear');

        return response()->json(['message' => 'Deposit Seccessfull']);
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
