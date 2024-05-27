<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Api\Backend\Master\IngredientUnitController as ApiIngredientUnitController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IngredientUnitRequest;
use App\Models\IngredientUnit;
use Yajra\DataTables\Facades\DataTables;

class IngredientUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_ingredient_unit');
        if (request()->ajax()) {

            $units = IngredientUnit::query();

            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('ingredient-unit.edit', $data->id), 'type' => 'edit', 'can' => 'edit_ingredient_unit'],
                        ['url' => route('ingredient-unit.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_ingredient_unit'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.master.ingredientUnit.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ingredient_unit');

        return view('pages.master.ingredientUnit.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IngredientUnitRequest $request)
    {
        $this->authorize('create_ingredient_unit');

        return $request->saved();
    }

    /**
     * Display the specified resource.
     */
    public function show(IngredientUnit $ingredientUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IngredientUnit $ingredientUnit)
    {
        $this->authorize('edit_ingredient_unit');

        return view('pages.master.ingredientUnit.form', compact('ingredientUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IngredientUnitRequest $request, IngredientUnit $ingredientUnit)
    {
        $this->authorize('edit_ingredient_unit');

        return $request->saved($ingredientUnit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientUnit $ingredientUnit)
    {
        $this->authorize('delete_ingredient_unit');
        $api = new ApiIngredientUnitController;

        return $api->destroy($ingredientUnit);
    }
}
