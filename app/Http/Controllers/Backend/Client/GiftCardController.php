<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\GiftCardRequest;
use App\Models\GiftCard;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class GiftCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_gift_card');
        if (request()->ajax()) {
            return DataTables::eloquent(GiftCard::query()->with('user:id,first_name,last_name'))
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('client.gift-card.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_customer'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.client.gift-card.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_gift_card');
        $users = User::query()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'customer_id']);

        return view('pages.client.gift-card.form', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GiftCardRequest $request)
    {
        $this->authorize('create_gift_card');

        return $request->saved();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiftCard $giftCard)
    {
        $this->authorize('delete_gift_card');
        $giftCard->delete();

        return response()->json(['message' => 'Gift card deleted successfully']);
    }
}
