<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class Profile extends Component
{
    #[Title('Profile')]
    public function render()
    {
        return view('livewire.frontend.pages.profile');
    }
}
