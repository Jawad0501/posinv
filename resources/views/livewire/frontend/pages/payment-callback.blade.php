<div class="{{ $is_api ? 'flex items-center bg-white h-screen':'flex items-center bg-light py-20 lg:py-16 2xl:py-24' }}">
    <div class="p-6 mx-auto">
        <div class="text-center">
            @if ($payment->status === \App\Enum\PaymentStatus::SUCCESS->value)
                <div class="bg-primary-600 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-2">
                    <i data-feather="check" class="text-white w-14 h-14"></i>
                </div>
                <h3 class="md:text-2xl text-base text-gray-900 font-semibold text-center">Payment Done!</h3>
                <p class="text-gray-600 my-2">Thank you for completing your secure online payment.</p>
                <p>Have a great day!</p>
            @else
                <div class="bg-warning-600 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-2">
                    <i data-feather="x" class="text-white w-14 h-14"></i>
                </div>
                <h3 class="md:text-2xl text-base text-gray-900 font-semibold text-center">Payment Cancel!</h3>
                <p class="text-gray-600 my-2">Your payment has been cancelled.</p>
            @endif

            @if (!$is_api)
                <div class="py-10 text-center">
                    <a href="/" wire:navigate class="px-12 bg-primary-600 hover:bg-primary-500 text-white font-semibold py-3 rounded-md">
                        GO BACK
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
