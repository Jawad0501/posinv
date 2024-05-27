<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\CategoryController as ApiCategoryController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CategoryRequest;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_category');
        if (request()->ajax()) {
            return DataTables::of(Category::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.category.edit', $data->id), 'type' => 'edit', 'can' => 'edit_category'],
                        ['url' => route('food.category.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_category'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_category');

        return view('pages.food.category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create_category');

        $api = new ApiCategoryController;

        return $api->store($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('edit_category');

        return view('pages.food.category.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('edit_category');
        $api = new ApiCategoryController;

        return $api->update($request, $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete_category');
        $api = new ApiCategoryController;

        return $api->destroy($category);
    }
}
