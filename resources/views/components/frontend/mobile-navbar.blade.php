<nav class="fixed bottom-0 left-0 right-0 bg-white shadow-lg shadow-gray-800 z-50 h-[58px] lg:hidden">
    <div class="grid grid-cols-5">
        <div>
            <a href="/" class="p-3 text-center block {{ request()->is('/') ? 'text-primary-500':'text-black' }}" wire:navigate>
                <i data-feather="home" class="mx-auto w-5 h-5"></i>
                <p class="text-xs mt-1">Home</p>
            </a>
        </div>
        <div>
            <a href="{{ route('menu') }}" class="p-3 text-center block {{ request()->is('menu') ? 'text-primary-500':'text-black' }}" wire:navigate>
                <i data-feather="map-pin" class="mx-auto w-5 h-5"></i>
                <p class="text-xs mt-1">Trending</p>
            </a>
        </div>
        <div class="bg-white rounded-full p-2 w-[85px] h-[85px] mx-auto -mt-5">
            <div class="bg-primary-500 rounded-full w-full h-full mt-0 shadow flex justify-center items-center">
                <a href="{{ route('cart') }}" class="text-center block text-black relative" wire:navigate>
                    <i data-feather="shopping-cart" class="mx-auto w-7 h-7 text-white"></i>
                    <p class="text-xs mt-1"></p>
                    <livewire:frontend.cart-count />
                </a>
            </div>
        </div>
        <div>
            <a href="{{ route('favorite') }}" class="p-3 text-center relative block {{ request()->is('favorite') ? 'text-primary-500':'text-black' }}" wire:navigate>
                <i data-feather="heart" class="mx-auto w-5 h-5"></i>
                <p class="text-xs mt-1">Favorite</p>
                <livewire:frontend.favorite-count />
            </a>
        </div>
        <div>
            <a href="{{ route('profile') }}" class="p-3 text-center block {{ request()->is('profile') ? 'text-primary-500':'text-black' }}" wire:navigate>
                <i data-feather="user" class="mx-auto w-5 h-5"></i>
                <p class="text-xs mt-1">Profile</p>
            </a>
        </div>

    </div>
</nav>
