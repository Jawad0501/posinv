<?php

namespace App\Http\Livewire\Frontend\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Title('Forgot Password')]
    public function render()
    {
        return view('livewire.frontend.pages.forgot-password');
    }
}
