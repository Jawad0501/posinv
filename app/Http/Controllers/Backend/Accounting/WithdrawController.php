<?php

namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_purchase');
        if (request()->ajax()) {

            $withdraws = Withdraw::query();

            return DataTables::of($withdraws)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->created_at);
                })
                ->toJson();
        }

        return view('pages.accounting.withdraw.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ingredient');

        return view('pages.accounting.withdraw.form');
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

        $withdraw = Withdraw::create([
            'staff_id' => auth()->user()->id,
            'amount' => $request->amount,
            'note' => $request->note,
            'amount_before_withdraw' => setting('cash_in_hand'),
        ]);

        $new_amount = setting('cash_in_hand') - $request->amount;

        if($new_amount <= 0) {
            $new_amount = 0;
        }
        
        // setting('cash_in_hand', null, $new_amount);
        Setting::updateOrCreate(['key' => 'cash_in_hand'], ['value' => $new_amount]);

        $withdraw->amount_after_withdraw = $new_amount;
        $withdraw->save();

        Artisan::call('cache:clear');

        return response()->json(['message' => 'Withdraw Seccessfull']);
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
