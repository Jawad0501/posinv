<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\MealPeriodRequest;
use App\Http\Resources\Backend\Food\MealPeriodCollection;
use App\Http\Resources\Backend\Food\MealPeriodResource;
use App\Models\MealPeriod;
use Illuminate\Http\Request;

/**
 * @group Food Meal Period Management
 *
 * APIs to Meal Period
 */
class MealPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\MealPeriodCollection
     *
     * @apiResourceModel App\Models\MealPeriod
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
        $periods = MealPeriod::query();

        if ($request->has('keyword')) {
            $periods = $periods->where('name', 'like', "%$request->keyword%");
        }

        $periods = $periods->latest('id')->get();

        return new MealPeriodCollection($periods);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New meal period added successfully.'
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
    public function store(MealPeriodRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Food\MealPeriodResource
     *
     * @apiResourceModel App\Models\MealPeriod
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(MealPeriod $mealPeriod)
    {
        return new MealPeriodResource($mealPeriod);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Meal period added successfully.'
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
    public function update(MealPeriodRequest $request, MealPeriod $mealPeriod)
    {
        return $request->saved($mealPeriod);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Meal period deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(MealPeriod $mealPeriod)
    {
        delete_uploaded_file($mealPeriod->image);
        $mealPeriod->delete();

        return response()->json(['message' => 'Meal period deleted successfully.']);
    }
}
