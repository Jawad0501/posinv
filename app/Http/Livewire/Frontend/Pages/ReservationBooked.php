<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class ReservationBooked extends Component
{
    public function mount()
    {
        if (! session()->has('confirmed_reservation')) {
            $this->redirect('/', true);
        } else {
            session()->forget('confirmed_reservation');
        }
    }

    #[Title('Reservation Booked')]
    public function render()
    {
        return view('livewire.frontend.pages.reservation-booked');
    }
}
