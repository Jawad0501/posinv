<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VariantRequest;
use App\Http\Resources\Backend\Food\VariantCollection;
use App\Http\Resources\Backend\Food\VariantResource;
use App\Models\Variant;
use Illuminate\Http\Request;

/**
 * @group Food Variant Management
 *
 * APIs to Variant
 */
class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\VariantCollection
     *
     * @apiResourceModel App\Models\Variant
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
        $variants = Variant::query()->with('food:id,name');

        if ($request->has('keyword')) {
            $variants = $variants->where('name', 'like', "%$request->keyword%")->orWhere('price', 'like', "%$request->keyword%")->orWhereRelation('food', 'name', 'like', "%$request->keyword%");
        }
        $variants = $variants->latest('id')->paginate();

        return new VariantCollection($variants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New variant added successfully.'
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
    public function store(VariantRequest $request)
    {
        $input = $request->validated();
        $input['food_id'] = $request->menu;

        Variant::create($input);

        return response()->json(['message' => 'New variant added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Food\VariantResource
     *
     * @apiResourceModel App\Models\Variant
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Food\VariantResource
     */
    public function show(Variant $variant)
    {
        return new VariantResource($variant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Variant updated successfully.'
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
    public function update(VariantRequest $request, Variant $variant)
    {
        $input = $request->validated();
        $input['food_id'] = $request->menu;
        $variant->update($input);

        return response()->json(['message' => 'Variant updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Variant deleted successfully.'
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
    public function destroy(Variant $variant)
    {
        $variant->delete();

        return response()->json(['message' => 'Variant deleted successfully']);
    }
}
