<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\VariantController as ApiVariantController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VariantRequest;
use App\Models\Food;
use App\Models\Variant;
use Yajra\DataTables\Facades\DataTables;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_variant');
        if (request()->ajax()) {
            return DataTables::of(Variant::query()->with('food:id,name'))
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.variant.edit', $data->id), 'type' => 'edit', 'can' => 'edit_variant'],
                        ['url' => route('food.variant.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_variant'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.variant.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_variant');
        $foods = Food::active()->get(['id', 'name']);

        return view('pages.food.variant.form', compact('foods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariantRequest $request)
    {
        $this->authorize('create_variant');
        $api = new ApiVariantController;

        return $api->store($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Variant $variant)
    {
        $this->authorize('edit_variant');
        $foods = Food::active()->get(['id', 'name']);

        return view('pages.food.variant.form', compact('foods', 'variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariantRequest $request, Variant $variant)
    {
        $this->authorize('edit_variant');

        $api = new ApiVariantController;

        return $api->update($request, $variant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $this->authorize('delete_variant');
        $api = new ApiVariantController;

        return $api->destroy($variant);
    }
}
