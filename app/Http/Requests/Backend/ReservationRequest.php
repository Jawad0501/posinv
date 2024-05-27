<?php

namespace App\Http\Requests\Backend;

use App\Enum\ReservationStatus;
use App\Mail\ReservationMail;
use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Mail;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'total_person' => ['required', 'numeric'],
            'expected_date' => ['required'],
            'expected_time' => ['required'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:20'],
            'occasion' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:'.ReservationStatus::HOLD->value.','.ReservationStatus::PENDING->value.','.ReservationStatus::CONFIRM->value.','.ReservationStatus::CANCEL->value],
            'special_request' => ['nullable', 'string'],
        ];
    }

    /**
     * saved
     */
    public function saved(Reservation $reservation = null)
    {
        if ($this->isMethod('POST')) {
            $available = checkReservationAvailability($this->expected_date, $this->expected_time, $this->total_person);
            if (! $available) {
                return response()->json(['message' => 'Table not available for reservation, please try again another reservation.'], 300);
            }
        }
        $data = array_merge($this->validated(), [
            'expected_date' => date('Y-m-d', strtotime($this->expected_date)),
            'expected_time' => date('H:i:s', strtotime($this->expected_time)),
        ]);

        if ($this->isMethod('POST')) {
            $reservation = Reservation::create($data);
        } else {
            $reservation->update($data);
            Mail::to($reservation->email)->send(new ReservationMail($reservation, true));
        }

        return response()->json(['message' => $this->isMethod('POST') ? 'Reservation added successfully.' : "Reservation $reservation->status successfully"]);
    }
}
