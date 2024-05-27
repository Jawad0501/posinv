<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\Reservation as ModelsReservation;
use Livewire\Attributes\Title;
use Livewire\Component;

class Reservation extends Component
{
    public $total_person;

    public $expected_date;

    public $expected_time;

    public $contact_no;

    public $slots = [];

    #[Title('Reservation')]
    public function render()
    {
        return view('livewire.frontend.pages.reservation');
    }

    /**
     * store
     */
    public function submit()
    {

        $this->validate([
            'total_person' => ['required', 'numeric'],
            'expected_date' => ['required'],
            'expected_time' => ['required'],
            'contact_no' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:11'],
        ]);

        $available = checkReservationAvailability($this->expected_date, $this->expected_time, $this->total_person);

        if (! $available) {
            $this->dispatch('alert', 'Table not available for reservation, please try again another reservation.', 'error');
        } else {
            $reservation = ModelsReservation::query()->create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'total_person' => $this->total_person,
                'expected_date' => date('Y-m-d', strtotime($this->expected_date)),
                'expected_time' => date('H:i:s', strtotime($this->expected_time)),
                'phone' => $this->contact_no,
                'invoice' => getTrx(),
            ]);

            session()->put('reservation', $reservation);

            $this->redirect(route('reservation.confirmation'), true);
        }
    }
}
