<nav class="top-0 left-0 w-full absolute z-[1]" :class="location.pathname === '/' ? 'absolute bg-transparent' : 'sticky bg-white'">
    <div class="container py-4">
        <div class="flex items-center justify-between">
            <div>
                <a href="/" wire:navigate>
                    <img src="{{ uploaded_file(setting(request()->is('/') ? 'light_logo' : 'dark_logo')) }}" class="w-auto h-8" alt="Logo" />
                </a>
            </div>
            <div class="flex items-center">
                <div class="hidden lg:block">
                    <ul class="flex space-x-3 items-center">
                        <li>
                            <a href="{{ route('menu') }}" wire:navigate class="nav-link {{ request()->is('/') ? 'text-white':'text-black' }}">
                                <i data-feather="shopping-bag" class="w-3 h-3"></i>
                                <span class="ml-2 text-sm">Menu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search.menu') }}" wire:navigate class="nav-link {{ request()->is('/') ? 'text-white':'text-black' }}">
                                <i data-feather="search" class="mx-auto w-4 h-4"></i>
                                <span class="ml-2 text-sm">Search</span>
                            </a>
                        </li>

                        @guest
                            <li>
                                <a href="{{ route('login') }}" class="nav-link {{ request()->is('/') ? 'text-white':'text-black' }}" wire:navigate>
                                    <i data-feather="user" class="mx-auto w-4 h-4"></i>
                                    <span class="ml-2 text-sm">Sign In</span>
                                </a>
                            </li>
                        @else
                            <li class="group relative">
                                <a href="#" class="nav-link {{ request()->is('/') ? 'text-white':'text-black' }}">
                                    <img src="{{ uploaded_file(auth()->user()->image) }}" class="w-8 h-8 rounded-full object-cover" />
                                    <span class="ml-2 text-sm">{{ auth()->user()->full_name }}</span>
                                    <i data-feather="chevron-down" class="ml-1 w-3 h-3"></i>
                                </a>
                                <ul class="absolute bg-white rounded-md py-1 transform scale-0 group-hover:scale-100 transition duration-500 ease-in-out w-48 shadow-lg z-10">
                                    <li class="">
                                        <a class="hover:bg-gray-50 py-1 px-4 block text-sm transition-colors duration-500" href="{{ route('profile') }}" wire:navigate>My Account</a>
                                    </li>
                                    <li class="">
                                        <a class="hover:bg-gray-50 py-1 px-4 block text-sm transition-colors duration-500" href="#">Delivery Support</a>
                                    </li>
                                    <li class="">
                                        <a class="hover:bg-gray-50 py-1 px-4 block text-sm transition-colors duration-500" href="{{ route('contact') }}" wire:navigate>Contact Us</a>
                                    </li>
                                    <li class="">
                                        <a class="hover:bg-gray-50 py-1 px-4 block text-sm transition-colors duration-500" href="#">Term of use</a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:bg-gray-50 py-1 px-4 block text-sm transition-colors duration-500">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest

                        <li>
                            <a href="{{ route('favorite') }}" class="nav-link relative {{ request()->is('/') ? 'text-white':'text-black' }}" wire:navigate>
                                <i data-feather="heart" class="mx-auto w-4 h-4"></i>
                                <span class="ml-2 text-sm">Favorite</span>
                                <livewire:frontend.favorite-count :mobile="false" />
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cart') }}" class="nav-link relative {{ request()->is('/') ? 'text-white':'text-black' }}" wire:navigate>
                                <i data-feather="shopping-cart" class="mx-auto w-4 h-4"></i>
                                <span class="ml-2 text-sm">Cart</span>
                                <livewire:frontend.cart-count :mobile="false" />
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <a href="javascript:void(0)" class="nav-link relative {{ request()->is('/') ? 'text-white':'text-black' }}" @click="navIsOpen = !navIsOpen">
                        <i data-feather="menu" class="mx-auto w-10 h-10"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
