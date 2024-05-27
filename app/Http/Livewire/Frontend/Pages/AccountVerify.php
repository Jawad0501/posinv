<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Models\User;
use App\Rules\VerifyOTPCodeRule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Twilio\Rest\Client;

class AccountVerify extends Component
{
    public $otp_code;

    public function mount()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            $this->redirect('/');
        }
    }

    #[Title('Account Verify')]
    public function render()
    {
        return view('livewire.frontend.pages.account-verify');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function verify()
    {
        $this->validate(['otp_code' => ['required', 'string', new VerifyOTPCodeRule]]);

        $user = User::find(auth()->id());
        if ($user->hasVerifiedEmail()) {
            sessionAlert('You are already verified', 'error');

            return redirect()->to('/profile');
        }

        $msg = 'Invalid verification code entered!';
        $type = 'error';

        if ($user->verify_field == 'email') {
            if ($user->otp_code == $this->otp_code) {
                $msg = 'Your verification successfully completed.';
                $type = 'success';
            }
        } else {
            $token = config('services.twilio.auth_token');
            $twilio_sid = config('services.twilio.sid');
            $twilio_verify_sid = config('services.twilio.verify_sid');

            $twilio = new Client($twilio_sid, $token);
            $verify = ['code' => $this->otp_code, 'to' => $user->phone];

            $verification = $twilio->verify->v2->services($twilio_verify_sid)->verificationChecks->create($verify);

            if ($verification->valid) {
                $msg = 'Your verification successfully completed.';
                $type = 'success';
            }
        }

        if ($type == 'success') {
            $user->update([
                'email_verified_at' => now(),
                'otp_code' => null,
                'verify_field' => null,
            ]);

            session()->flash('alert', ['message' => $msg, 'type' => $type]);

            return $this->redirect(route('profile'));
        }
        $this->dispatch('alert', $msg, $type);
    }

    public function resendVerification()
    {
        $user = User::find(auth()->id());

        if ($user->hasVerifiedEmail()) {
            $this->dispatch('alert', 'Your account already verified.', 'error');
        } else {
            sendVerificationNotification($user);
            $this->dispatch('alert', 'Your verification OTP code has been send.');
        }
    }
}
