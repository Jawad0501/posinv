<aside class="fixed top-0 left-0 bottom-0 h-full z-[9999]">
    <div @click="navIsOpen = false"
        class="fixed w-full h-full left-0 top-0 right-0 bottom-0 bg-black/50 overscroll-none" x-show="navIsOpen"
        x-transition:enter.duration.500ms x-transition:leave.duration.400ms>
    </div>
    <div class="fixed top-0 bottom-0 w-72 h-full max-h-full max-w-full bg-white z-[9998] left-0" x-show="navIsOpen" x-transition:enter.duration.500ms x-transition:leave.duration.400ms>
        <div class="relative h-screen pb-20">
            <div class="bg-[#343a40]">
                <h2 class="p-4 text-xl text-white">Eatery</h2>
            </div>

            <ul class="relative asidescroll max-h-[87vh]">
                <li>
                    <a href="/" wire:navigate class="py-[14px] px-[17px] flex items-center text-primary-500 border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                        <i data-feather="home" class="w-3 h-3"></i>
                        <span class="ml-3 text-sm">Homepage</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('order') }}" wire:navigate class="py-[14px] px-[17px] flex items-center text-[#343a40] border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                        <i data-feather="list" class="w-3 h-3"></i>
                        <span class="ml-3 text-sm">My Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('menu') }}" wire:navigate class="py-[14px] px-[17px] flex items-center text-[#343a40] border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                        <i data-feather="shopping-bag" class="w-3 h-3"></i>
                        <span class="ml-3 text-sm">Menu</span>
                    </a>
                </li>
                <li>
                    <a href="" class="py-[14px] px-[17px] flex items-center text-[#343a40] border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                        <i data-feather="list" class="w-3 h-3"></i>
                        <span class="ml-3 text-sm">My Offers</span>
                    </a>
                </li>
                @if (setting('reservation_allows'))
                    <li>
                        <a href="{{ route('reservation') }}" wire:navigate class="py-[14px] px-[17px] flex items-center text-[#343a40] border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                            <i data-feather="table" class="w-3 h-3"></i>
                            <span class="ml-3 text-sm">Reservation</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('order') }}" wire:navigate class="py-[14px] px-[17px] flex items-center text-[#343a40] border-b border-[#edf1f4] transition-all duration-500 hover:bg-gray-50">
                        <i data-feather="log-in" class="w-3 h-3"></i>
                        <span class="ml-3 text-sm">Sign in</span>
                    </a>
                </li>
            </ul>

            <div class="absolute bottom-0 flex flex-nowrap items-stretch w-full border-t border-[#edf1f4] shadow-lg bg-white">
                <a href="/" wire:navigate class="p-3 text-center w-1/3 hover:bg-gray-50 transition-colors">
                    <i data-feather="home" class="mx-auto w-5 h-5"></i>
                    <span class="block text-center text-sm">Home</span>
                </a>
                <a href="#" class="p-3 text-center w-1/3 hover:bg-gray-50">
                    <i data-feather="message-circle" class="mx-auto w-5 h-5"></i>
                    <span class="block text-center text-sm">FAQ</span>
                </a>
                <a href="#" class="p-3 text-center w-1/3 hover:bg-gray-50">
                    <i data-feather="phone" class="mx-auto w-5 h-5"></i>
                    <span class="block text-center text-sm">Help</span>
                </a>
                </ul>

            </div>
        </div>

</aside>
