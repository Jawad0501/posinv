<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Enum\ReservationStatus;
use App\Mail\ReservationMail;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;

class ReservationConfirmation extends Component
{
    public $name;

    public $email;

    public $occasion = null;

    public $special_request = null;

    public $reservation;

    public function mount()
    {
        if (! session()->has('reservation')) {
            $this->redirect('/', true);
        } else {
            $this->reservation = session()->get('reservation');
        }
    }

    #[Title('Reservation Confirmation')]
    public function render()
    {
        return view('livewire.frontend.pages.reservation-confirmation');
    }

    public function submit()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'occasion' => ['nullable', 'string', 'max:255'],
            'special_request' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->check() ? auth()->id() : null;

        $reservation = Reservation::query()->find($this->reservation->id);
        $available = checkReservationAvailability($reservation->expected_date, $reservation->expected_time, $reservation->total_person);

        if (! $available) {
            return $this->dispatch('alert', 'Table not available for reservation, please try again another reservation.', 'error');
        }
        $reservation->update(array_merge($validated, ['status' => ReservationStatus::PENDING->value]));

        Mail::to($reservation->email)->send(new ReservationMail($reservation));

        session()->forget('reservation');
        session()->get('confirmed_reservation');
        $this->redirect(route('reservation.booked'));
    }
}
