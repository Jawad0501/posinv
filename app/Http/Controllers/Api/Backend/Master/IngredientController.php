<?php

namespace App\Http\Controllers\Api\Backend\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IngredientRequest;
use App\Http\Resources\Backend\Master\IngredientCollection;
use App\Http\Resources\Backend\Master\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Request;

/**
 * @group Ingredient Management
 *
 * APIs to Ingredient
 */
class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Master\IngredientCollection
     *
     * @apiResourceModel App\Models\Ingredient
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam keyword
     *
     * @param  \Illuminate\Http\Request  $name
     */
    public function index(Request $request)
    {
        $ingredients = Ingredient::query()->with('category:id,name', 'unit:id,name');

        if ($request->has('keyword')) {
            $ingredients = $ingredients->where('name', 'like', "%$request->keyword%")
                ->orWhere('purchase_price', 'like', "%$request->keyword%")
                ->orWhere('alert_qty', 'like', "%$request->keyword%")
                ->orWhere('code', 'like', "%$request->keyword%")
                ->orWhereRelation('category', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('unit', 'name', 'like', "%$request->keyword%");
        }
        $ingredients = $ingredients->latest('id')->paginate();

        return new IngredientCollection($ingredients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Ingredient added successfully."
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
    public function store(IngredientRequest $request)
    {
        $input = $request->validated();
        $input['category_id'] = $request->category;
        $input['unit_id'] = $request->unit;

        Ingredient::create($input);

        return response()->json(['message' => 'Ingredient created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Master\IngredientResource
     *
     * @apiResourceModel App\Models\Ingredient
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Master\IngredientResource
     */
    public function show(Ingredient $ingredient)
    {
        return new IngredientResource($ingredient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Ingredient added successfully."
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
    public function update(IngredientRequest $request, Ingredient $ingredient)
    {
        $input = $request->validated();
        $input['category_id'] = $request->category;
        $input['unit_id'] = $request->unit;

        $ingredient->update($input);

        return response()->json(['message' => 'Ingredient updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Ingredient deleted successfully."
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
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json(['message' => 'Ingredient deleted successfully']);
    }
}
