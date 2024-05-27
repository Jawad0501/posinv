<div class="bg-white w-full lg:max-w-full md:mx-auto md:w-2/3 lg:w-1/2 2xl:w-1/3 h-screen px-6 lg:px-16 flex items-center justify-center">

    <div class="w-full h-100 space-y-5">
        <div>
            <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">Hello There.</h1>
            <p class="text-sm text-gray-400">Sign up to continue</p>
        </div>

        <form wire:submit.prevent="register" class="mt-6" method="POST">
            <div class="space-y-3">
                <x-frontend.form-group label="name" wire:model="name" placeholder="Enter Name" />
                <x-frontend.form-group label="Email Address" for="email" wire:model.defer="email" type="email" placeholder="Enter Email Address" />
                <x-frontend.form-group label="Mobile Number" for="phone" wire:model.defer="phone" placeholder="Enter Mobile" />
                <x-frontend.form-group label="password" type="password" wire:model.defer="password" placeholder="Enter Password" />

                <x-frontend.submit-button label="Sign Up" wire:loading.attr="disabled" wire:loading.class="btn-loading" />

                @if (json_decode(setting('oauth'))->google->enable)
                    <x-frontend.social-connect-button />
                @endif

                @if (json_decode(setting('oauth'))->facebook->enable)
                    <x-frontend.social-connect-button />
                @endif

            </div>
        </form>

        <p>
            Already an account?
            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-semibold" wire:navigate>Sign in</a>
        </p>
    </div>
</div>
