<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Api\Backend\Master\IngredientCategoryController as ApiIngredientCategoryController;
use App\Http\Controllers\Controller;
use App\Models\IngredientCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IngredientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_ingredient_category');
        if (request()->ajax()) {
            $categories = IngredientCategory::query();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('ingredient-category.edit', $data->id), 'type' => 'edit', 'can' => 'edit_ingredient_category'],
                        ['url' => route('ingredient-category.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_ingredient_category'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.master.ingredient-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_ingredient_category');

        return view('pages.master.ingredient-category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_ingredient_category');
        $api = new ApiIngredientCategoryController;

        return $api->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(IngredientCategory $ingredientCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IngredientCategory $ingredientCategory)
    {
        $this->authorize('edit_ingredient_category');

        return view('pages.master.ingredient-category.form', compact('ingredientCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IngredientCategory $ingredientCategory)
    {
        $this->authorize('edit_ingredient_category');
        $api = new ApiIngredientCategoryController;

        return $api->update($request, $ingredientCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientCategory $ingredientCategory)
    {
        $this->authorize('delete_ingredient_category');
        $api = new ApiIngredientCategoryController;

        return $api->destroy($ingredientCategory);
    }
}
