<section class="section bg-light">
    <div class="container">
        <div class="bg-white shadow rounded-md mx-auto max-w-2xl">
            <div class="px-5 md:px-10 py-20 space-y-5">
                <div class="text-center">
                    <h4>Verify your account</h4>
                    <p class="text-gray-600 mt-1">We have sent you verification OTP code in this {{ maskData((bool) setting('verify_by') == 'email' ? auth()->user()->email : auth()->user()->phone) }} {{ (bool) setting('verify_by') == 'email' ? 'email':'mobile number' }}</p>
                </div>

                <div class="w-full h-100">
                    <div class="space-y-5">
                        <form wire:submit="verify" class="mt-6" method="POST">
                            <x-frontend.form-group label="OTP Code" wire:model="otp_code" for="otp_code" type="number" placeholder="Enter OTP Code" />

                            <x-frontend.submit-button label="Verify" wire:loading.attr="disabled" wire:loading.class="btn-loading" wire:target='verify' />
                        </form>

                        <form wire:submit="resendVerification" class="mt-6">
                            <p class="flex items-center">
                                <button type="submit" class="text-primary-500">Resend verification code</button>
                                <svg wire:loading wire:target='resendVerification' class="animate-spin ml-1 mr-3 h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
