<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Enum\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ReservationRequest;
use App\Http\Resources\Frontend\ReservationCollection;
use App\Http\Resources\Frontend\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

/**
 * @group Reservation management
 *
 * APIs to Reservation management
 */
class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @apiResourceCollection App\Http\Resources\Frontend\ReservationCollection
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
    public function index()
    {
        $reservations = Reservation::query()->where('user_id', auth()->id())->get();

        return new ReservationCollection($reservations);
    }

    /**
     * Checking reservation times.
     *
     *
     * @response status=200
     * [
     *       {
     *           "time": "08:30 AM",
     *           "available": true
     *       },
     *       {
     *           "time": "09:30 AM",
     *           "available": true
     *       },
     *       {
     *           "time": "10:30 AM",
     *           "available": false
     *       },
     *       {
     *           "time": "11:30 AM",
     *           "available": true
     *       },
     *       {
     *           "time": "12:30 PM",
     *           "available": true
     *       },
     *       {
     *           "time": "01:30 PM",
     *           "available": false
     *       },
     *       {
     *           "time": "02:30 PM",
     *           "available": true
     *       },
     *       {
     *           "time": "03:30 PM",
     *           "available": true
     *       },
     *       {
     *           "time": "04:30 PM",
     *           "available": true
     *       },
     *       {
     *           "time": "05:30 PM",
     *           "available": false
     *       },
     *       {
     *           "time": "06:30 PM",
     *           "available": true
     *       },
     *       {
     *           "time": "07:30 PM",
     *           "available": true
     *       }
     * ]
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function checking(Request $request)
    {
        $request->validate([
            'total_person' => ['required', 'integer'],
            'expected_date' => ['required'],
        ]);
        $slots = [];
        foreach (reservationTimeSlots() as $time) {
            $available = checkReservationAvailability($request->expected_date, $time, $request->total_person);
            $slots[] = [
                'time' => $time,
                'available' => $available,
            ];
        }

        return $slots;
    }

    /**
     * Store a new reservation in storage.
     *
     * @apiResource App\Http\Resources\Frontend\ReservationResource
     *
     * @apiResourceModel App\Models\Reservation
     *
     * @response status=300
     * {
     *   "message": "Table not available for reservation, please try again another reservation."
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)"
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
        $available = checkReservationAvailability($request->expected_date, $request->expected_time, $request->total_person);

        if (! $available) {
            return response()->json(['message' => 'Table not available for reservation, please try again another reservation.'], 300);
        }

        $reservation = Reservation::query()->create([
            'user_id' => $request->user_id,
            'total_person' => $request->total_person,
            'expected_date' => date('Y-m-d', strtotime($request->expected_date)),
            'expected_time' => date('H:i:s', strtotime($request->expected_time)),
            'phone' => $request->contact_no,
            'invoice' => getTrx(),
        ]);

        return new ReservationResource($reservation, false);
    }

    /**
     * Display a listing of the resource.
     *
     * @apiResource App\Http\Resources\Frontend\ReservationResource
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
    public function show($invoice)
    {
        $reservation = Reservation::query()->where('user_id', auth()->id())->where('invoice', $invoice)->firstOrFail();

        return new ReservationResource($reservation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @response 200 {
     *     "message": "You are booked reservation successfully."
     * }
     * @response status=300
     * {
     *   "message": "Table not available for reservation, please try again another reservation."
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)"
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(ReservationRequest $request, $invoice)
    {
        $reservation = Reservation::query()->where('invoice', $invoice)->firstOrFail();

        $available = checkReservationAvailability($reservation->expected_date, $reservation->expected_time, $reservation->total_person);
        if (! $available) {
            return response()->json(['message' => 'Table not available for reservation, please try again another reservation.'], 300);
        }

        $reservation->update(array_merge($request->validated(), ['status' => ReservationStatus::PENDING->value]));

        return response()->json(['message' => 'You are booked reservation successfully.']);
    }
}
