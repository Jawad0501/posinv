<form wire:submit="login" class="mt-6">
    <div class="space-y-3">
        <x-frontend.form-group label="Email Address" for="email" wire:model="email" type="email" placeholder="Enter Email Address" />
        <x-frontend.form-group label="password" type="password" wire:model="password" placeholder="Enter Password" />
        <div class="text-right mt-2">
            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-700 focus:text-blue-700" wire:navigate>Forgot Password?</a>
        </div>

        <x-frontend.checkbox label="Remember Me" for="remember" wire:model="remember" />

        <x-frontend.submit-button label="Sign In" wire:loading.attr="disabled" wire:loading.class="btn-loading" />

        @if (json_decode(setting('oauth'))->google->enable)
            <x-frontend.social-connect-button />
        @endif

        @if (json_decode(setting('oauth'))->facebook->enable)
            <x-frontend.social-connect-button />
        @endif

    </div>
</form>

