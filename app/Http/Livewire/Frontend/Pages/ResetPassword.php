<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class ResetPassword extends Component
{
    #[Title('Reset Password')]
    public function render()
    {
        return view('livewire.frontend.pages.reset-password');
    }
}
