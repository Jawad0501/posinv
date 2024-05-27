<?php

namespace App\Http\Controllers\Backend\Restaurant;

use App\Enum\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ReservationRequest;
use App\Models\Reservation;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_reservation');
        if (request()->ajax()) {
            return DataTables::of(Reservation::query())
                ->addIndexColumn()
                ->editColumn('expected_date', fn ($data) => format_date("$data->expected_date $data->expected_time", true))
                ->editColumn('created_at', fn ($data) => format_date($data->created_at, true))
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('orders.reservations.edit', $data->id), 'type' => 'edit', 'can' => 'edit_reservation'],
                        ['url' => route('orders.reservations.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_reservation'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.restaurant.reservation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_reservation');
        $status = [ReservationStatus::HOLD->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRM->value, ReservationStatus::CANCEL->value];

        return view('pages.restaurant.reservation.form', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        $this->authorize('create_reservation');

        return $request->saved();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $this->authorize('edit_reservation');

        $status = [ReservationStatus::HOLD->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRM->value, ReservationStatus::CANCEL->value];

        return view('pages.restaurant.reservation.form', compact('reservation', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $this->authorize('edit_reservation');

        return $request->saved($reservation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete_reservation');
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
