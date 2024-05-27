<?php

namespace App\Http\Controllers\Api\Backend\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Master\IngredientCategoryCollection;
use App\Http\Resources\Backend\Master\IngredientCategoryResource;
use App\Models\IngredientCategory;
use Illuminate\Http\Request;

/**
 * @group Ingredient Category Management
 *
 * APIs to Ingredient Category
 */
class IngredientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Master\IngredientCategoryCollection
     *
     * @apiResourceModel App\Models\IngredientCategory
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam keyword
     */
    public function index(Request $request)
    {
        $categories = IngredientCategory::query();

        if ($request->has('keyword')) {
            $categories = $categories->where('name', 'like', "%$request->keyword%");
        }
        $categories = $categories->latest('id')->paginate();

        return new IngredientCategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "New category added successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|string|max:255']);

        IngredientCategory::create([
            'name' => $request->name,
            'slug' => generate_slug($request->name),
        ]);

        return response()->json(['message' => 'Ingredient Category created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Master\IngredientCategoryResource
     *
     * @apiResourceModel App\Models\IngredientCategory
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Master\IngredientCategoryResource
     */
    public function show(IngredientCategory $ingredientCategory)
    {
        return new IngredientCategoryResource($ingredientCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Category added successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, IngredientCategory $ingredientCategory)
    {
        $this->validate($request, ['name' => 'required|string|max:255']);

        $ingredientCategory->update([
            'name' => $request->name,
            'slug' => generate_slug($request->name),
        ]);

        return response()->json(['message' => 'Ingredient category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message" "Category deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(IngredientCategory $ingredientCategory)
    {
        $ingredientCategory->delete();

        return response()->json(['message' => 'Ingredient category deleted successfully']);
    }
}
