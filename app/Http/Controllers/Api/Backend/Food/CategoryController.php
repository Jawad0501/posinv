<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Resources\Backend\Food\CategoryCollection;
use App\Http\Resources\Backend\Food\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * @group Food Category Management
 *
 * APIs to Category
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\CategoryCollection
     *
     * @apiResourceModel App\Models\Category
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
        $categories = Category::query();

        if ($request->has('keyword')) {
            $categories = $categories->where('name', 'like', "%$request->keyword%");
        }
        $categories = $categories->latest('id')->paginate();

        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New category added successfully.'
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
     */
    public function store(CategoryRequest $request)
    {
        Category::create([
            'name' => $request->name,
            'slug' => generate_slug($request->name),
            'image' => $request->hasFile('image') ? file_upload($request->image, 'category') : null,
            'is_drinks' => $request->has('is_drinks') ? true : false,
        ]);

        return response()->json(['message' => 'New category added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Food\CategoryResource
     *
     * @apiResourceModel App\Models\Category
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Category added successfully.'
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
     */
    public function update(Request $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => generate_slug($request->name),
            'image' => $request->hasFile('image') ? file_upload($request->image, 'category', $category->image) : $category->image,
            'is_drinks' => $request->has('is_drinks') ? true : false,
        ]);

        return response()->json(['message' => 'Category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Category deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Category $category)
    {
        delete_uploaded_file($category->image);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
