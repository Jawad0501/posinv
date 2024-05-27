<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

class ChangePassword extends Component
{
    public $current_password;

    public $password;

    public $password_confirmation;

    #[Title('Change Password')]
    public function render()
    {
        return view('livewire.frontend.pages.change-password');
    }

    /**
     * update authenticated user password
     */
    public function update()
    {
        $this->validate([
            'current_password' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        if (Hash::check($this->current_password, auth()->user()->password)) {
            if (! Hash::check($this->password, auth()->user()->password)) {

                User::find(auth()->id())->update(['password' => Hash::make($this->password)]);

                Auth::guard('web')->logout();
                $this->dispatch('alert', 'Password updated successfully');
                $this->redirect(route('login'), true);
            } else {
                $this->dispatch('alert', 'New password can not be same as current password', 'error');
            }
        } else {
            $this->dispatch('alert', 'Password does not match', 'error');
        }
    }
}
