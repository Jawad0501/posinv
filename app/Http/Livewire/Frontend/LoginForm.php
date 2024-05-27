<?php

namespace App\Http\Livewire\Frontend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginForm extends Component
{
    public $email;

    public $password;

    public $remember = false;

    public $redirect;

    public function mount($redirect = '/profile')
    {
        $this->redirect = $redirect;
    }

    public function render()
    {
        return view('livewire.frontend.login-form');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            $this->password = null;
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        } else {
            session()->regenerate();
            $this->dispatch('alert', 'You are logged in successfully.');
            $this->redirect($this->redirect);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
