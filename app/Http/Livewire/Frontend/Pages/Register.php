<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Title;
use Livewire\Component;

class Register extends Component
{
    public $name;

    public $email;

    public $phone;

    public $password;

    public function render()
    {
        return view('livewire.frontend.pages.register');
    }

    #[Title('Sign Up')]
    public function register()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $name = explode(' ', $this->name);
        $user = User::create([
            'first_name' => $name[0],
            'last_name' => isset($name[1]) ? $name[1] : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        sendVerificationNotification($user);

        $this->dispatch('alert', 'You are registered successfully, please verify your account.');

        $this->redirect(route('verification.notice'), true);
    }
}
