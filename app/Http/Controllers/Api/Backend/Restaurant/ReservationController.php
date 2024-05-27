<?php

namespace App\Http\Controllers\Api\Backend\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ReservationRequest;
use App\Http\Resources\Backend\Restaurant\ReservationCollection;
use App\Http\Resources\Backend\Restaurant\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

/**
 * @group Restaurant Reservation Management
 *
 * APIs to Reservation
 */
class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Restaurant\ReservationCollection
     *
     * @apiResourceModel App\Models\Reservation
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
        $reservations = Reservation::query();
        if ($request->has('keyword')) {
            $reservations = $reservations->whereLike(['name', 'email', 'phone', 'expected_date', 'expected_time', 'total_person', 'status', 'invoice', 'occasion', 'special_request'], $request->keyword);
        }
        $reservations = $reservations->latest('id')->paginate();

        return new ReservationCollection($reservations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Reservation added successfully.'
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
    public function store(ReservationRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Restaurant\ReservationResource
     *
     * @apiResourceModel App\Models\Reservation
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Reservation $reservation)
    {
        return new ReservationResource($reservation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Reservation updated successfully.'
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
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        return $request->saved($reservation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Reservation deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
