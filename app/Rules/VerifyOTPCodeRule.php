<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyOTPCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (auth()->user()->verify_field !== 'phone') {
            if (auth()->user()->otp_code != $value) {
                $fail('Invalid OTP code given.');
            }
        }
    }
}
