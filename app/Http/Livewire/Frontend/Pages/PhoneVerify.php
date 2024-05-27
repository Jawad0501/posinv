<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class PhoneVerify extends Component
{
    public $phone;

    public function mount()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            $this->redirect('/');
        }
    }

    #[Title('Phone Verify')]
    public function render()
    {
        return view('livewire.frontend.pages.phone-verify');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function verify()
    {
        $this->validate(['phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11']);

        $user = User::find(auth()->id());
        sendVerificationNotification($user, ['phone' => $this->phone]);

        $this->dispatch('alert', 'Your verification OTP code has been send.');

        return $this->redirect(route('verification.notice', ['redirect', encrypt(rand())]));
    }
}
