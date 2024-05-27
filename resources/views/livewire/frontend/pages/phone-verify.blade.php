<section class="section bg-light">
    <div class="container">
        <div class="bg-white shadow rounded-md mx-auto max-w-2xl">
            <div class="px-5 md:px-10 py-20 space-y-5">
                <div class="text-center">
                    <h4>Verify your phone number</h4>
                    <p class="text-gray-600 mt-1">Please enter your phone number. We will then send you a 6-digit code to verify your number.</p>
                </div>

                <div class="w-full h-100">
                    <div class="space-y-5">
                        <form wire:submit="verify" class="mt-6" method="POST">
                            <x-frontend.form-group label="phone" wire:model="phone" placeholder="Enter phone number" :islabel="false" />

                            <x-frontend.submit-button label="Send verification code" wire:loading.attr="disabled" wire:loading.class="btn-loading" wire:target='verify' />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
