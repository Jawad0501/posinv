<?php

namespace App\Http\Controllers\Api\Backend\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RiderRequest;
use App\Http\Resources\Backend\Client\RiderCollection;
use App\Http\Resources\Backend\Client\RiderResource;
use App\Models\User;

/**
 * @group Client Rider management
 *
 * APIs to Client Rider management
 */
class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Client\RiderCollection
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index()
    {
        $riders = User::query()->rider()->latest('id')->paginate();

        return new RiderCollection($riders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Rider successfully stored."
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
    public function store(RiderRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Client\RiderResource
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(User $rider)
    {
        return new RiderResource($rider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Rider successfully updated.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)",
     *    "errors": {
     *        "name": [
     *            "The name field is required."
     *        ],
     *        "email": [
     *            "The email field is required."
     *        ],
     *        "phone": [
     *            "The phone field is required."
     *        ]
     *    }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(RiderRequest $request, User $rider)
    {
        return $request->saved($rider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Rider deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(User $rider)
    {
        delete_uploaded_file($rider->image);
        $rider->delete();

        return response()->json(['message' => 'Rider deleted successfully']);
    }
}
