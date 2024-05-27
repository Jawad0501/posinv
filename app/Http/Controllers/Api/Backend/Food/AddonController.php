<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AddonRequest;
use App\Http\Resources\Backend\Food\AddonCollection;
use App\Http\Resources\Backend\Food\AddonResource;
use App\Models\Addon;
use Illuminate\Http\Request;

/**
 * @group Food Addon Management
 *
 * APIs to Addon
 */
class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\AddonCollection
     *
     * @apiResourceModel App\Models\Addon
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
        $addons = Addon::query()->when($request->has('keyword'), fn ($q) => $q->whereLike(['name'], $request->keyword))->orderBy('name')->paginate();

        return new AddonCollection($addons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New addon added successfully.'
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
    public function store(AddonRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Food\AddonResource
     *
     * @apiResourceModel App\Models\Addon
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Addon $addon)
    {
        return new AddonResource($addon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Addon updated successfully.'
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
    public function update(AddonRequest $request, Addon $addon)
    {
        return $request->saved($addon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Addon deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Addon $addon)
    {
        delete_uploaded_file($addon->image);
        $addon->delete();

        return response()->json(['message' => 'Addon deleted successfully']);
    }
}
