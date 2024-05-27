<div class="bg-white w-full lg:max-w-full md:mx-auto md:w-2/3 lg:w-1/2 2xl:w-1/3 h-screen px-6 lg:px-16 flex items-center justify-center">

    <div class="w-full h-100 space-y-5">
        <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">
            Log in to your account
        </h1>

        <livewire:frontend.login-form lazy />

        <p class="">
            Need an account?
            <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                Create an account
            </a>
        </p>
    </div>
</div>
