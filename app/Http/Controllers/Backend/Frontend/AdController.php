<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdRequest;
use App\Models\Ad;
use Yajra\DataTables\Facades\DataTables;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_ad');
        if (request()->ajax()) {
            return DataTables::of(Ad::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('frontend.ad.edit', $data->id), 'type' => 'edit', 'can' => 'edit_ad'],
                        ['url' => route('frontend.ad.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_ad'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.frontend.ad.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ad');

        return view('pages.frontend.ad.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdRequest $request)
    {
        $this->authorize('create_ad');

        return $request->saved();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        $this->authorize('edit_ad');

        return view('pages.frontend.ad.form', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdRequest $request, Ad $ad)
    {
        $this->authorize('edit_ad');

        return $request->saved($ad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $this->authorize('delete_ad');
        delete_uploaded_file($ad->image);
        $ad->delete();

        return response()->json(['message' => 'Ad deleted successfully']);
    }
}
