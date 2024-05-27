<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class Address extends Component
{
    #[Title('Address')]
    public function render()
    {
        return view('livewire.frontend.pages.address');
    }
}
