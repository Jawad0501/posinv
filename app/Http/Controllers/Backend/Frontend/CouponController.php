<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CouponRequest;
use App\Models\Coupon;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_asked_question');
        if (request()->ajax()) {
            $coupons = Coupon::query();

            return DataTables::of($coupons)
                ->addIndexColumn()
                ->editColumn('discount_type', function ($data) {
                    return ucfirst($data->discount_type);
                })
                ->editColumn('expire_date', function ($data) {
                    return format_date($data->expire_date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('frontend.coupon.edit', $data->id), 'type' => 'edit', 'can' => 'edit_coupon'],
                        ['url' => route('frontend.coupon.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_coupon'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.frontend.coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.frontend.coupon.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        Coupon::create($request->all());

        return response()->json(['message' => 'Coupon added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('pages.frontend.coupon.form', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->all());

        return response()->json(['message' => 'Coupon updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $this->authorize('delete_coupon');
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully']);
    }
}
