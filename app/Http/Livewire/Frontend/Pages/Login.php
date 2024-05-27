<?php

namespace App\Http\Livewire\Frontend\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    #[Title('Sign In')]
    public function render()
    {
        return view('livewire.frontend.pages.login');
    }

    #[On('logout')]
    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), true);
    }
}
