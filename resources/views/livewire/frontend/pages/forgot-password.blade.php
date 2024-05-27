<div class="bg-white w-full lg:max-w-full md:mx-auto md:w-2/3 lg:w-1/2 2xl:w-1/3 h-screen px-6 lg:px-16 flex items-center justify-center">

    <div class="w-full h-100 space-y-5">
        <div>
            <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">Forgot password</h1>
            <p class="text-sm text-gray-400">Enter your email address below and we'll send you an email with instructions on how to change your password</p>
        </div>

        <form class="mt-6" action="#" method="POST">
            <x-frontend.form-group label="Email Address" for="email" wire:model="email" type="email" placeholder="Enter Email Address" />

            <x-frontend.submit-button label="Send" wire:loading.attr="disabled" wire:loading.class="btn-loading" />
        </form>

        <p>
            Already an account?
            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-semibold" wire:navigate>Sign in</a>
        </p>
    </div>
</div>
