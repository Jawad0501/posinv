<?php

namespace App\Http\Controllers\Api\Backend\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\IngredientUnitRequest;
use App\Http\Resources\Backend\Master\IngredientUnitCollection;
use App\Http\Resources\Backend\Master\IngredientUnitResource;
use App\Models\IngredientUnit;
use Illuminate\Http\Request;

/**
 * @group Ingredient Unit Management
 *
 * APIs to Ingredient Unit
 */
class IngredientUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Master\IngredientUnitCollection
     *
     * @apiResourceModel App\Models\IngredientUnit
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
        $units = IngredientUnit::query();

        if ($request->has('keyword')) {
            $units = $units->where('name', 'like', "%$request->keyword%")->orWhere('description', 'like', "%$request->keyword%");
        }
        $units = $units->latest('id')->paginate();

        return new IngredientUnitCollection($units);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "New Unit added successfully."
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
    public function store(IngredientUnitRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Master\IngredientUnitResource
     *
     * @apiResourceModel App\Models\IngredientUnit
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Master\IngredientUnitResource
     */
    public function show(IngredientUnit $ingredientUnit)
    {
        return new IngredientUnitResource($ingredientUnit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Unit added successfully."
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
    public function update(IngredientUnitRequest $request, IngredientUnit $ingredientUnit)
    {
        return $request->saved($ingredientUnit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Unit deleted successfully."
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
    public function destroy(IngredientUnit $ingredientUnit)
    {
        $ingredientUnit->delete();

        return response()->json(['message' => 'Ingredient Unit deleted successfully']);
    }
}
