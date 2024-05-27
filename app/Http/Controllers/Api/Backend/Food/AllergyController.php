<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AllergyRequest;
use App\Http\Resources\Backend\Food\AllergyCollection;
use App\Http\Resources\Backend\Food\AllergyResource;
use App\Models\Allergy;
use Illuminate\Http\Request;

/**
 * @group Food Allergy Management
 *
 * APIs to Allergy
 */
class AllergyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\AllergyCollection
     *
     * @apiResourceModel App\Models\Allergy
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
        $alleries = Allergy::query();

        if ($request->has('keyword')) {
            $alleries = $alleries->where('name', 'like', "%$request->keyword%");
        }
        $alleries = $alleries->latest('id')->paginate();

        return new AllergyCollection($alleries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New allergy added successfully.'
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
    public function store(AllergyRequest $request)
    {
        Allergy::create([
            'name' => $request->name,
            'image' => $request->hasFile('image') ? file_upload($request->image, 'allergy') : null,
        ]);

        return response()->json(['message' => 'New allergy added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Food\AllergyResource
     *
     * @apiResourceModel App\Models\Allergy
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Allergy $allergy)
    {
        return new AllergyResource($allergy);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Allergy updated successfully.'
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
    public function update(AllergyRequest $request, Allergy $allergy)
    {
        $allergy->update([
            'name' => $request->name,
            'image' => $request->hasFile('image') ? file_upload($request->image, 'allergy', $allergy->image) : $allergy->image,
        ]);

        return response()->json(['message' => 'Allergy updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Allergy deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Allergy $allergy)
    {
        delete_uploaded_file($allergy->image);
        $allergy->delete();

        return response()->json(['message' => 'Allergy deleted successfully']);
    }
}
