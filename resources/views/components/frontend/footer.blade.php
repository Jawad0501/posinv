<footer class="bg-[#2e3333] py-10 hidden lg:block px-4">
    <div class="max-w-[1120px] mx-auto">
        <div class="grid grid-cols-3 gap-5 text-white">
            <div class="space-y-3 bg-[hsla(0,0%,100%,.1)] px-5 py-6 rounded-md">
                <h6>Discover Eatery</h6>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="text-white hover:text-primary-500 transition-colors duration-500">About us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white hover:text-primary-500 transition-colors duration-500" wire:navigate>Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="text-white hover:text-primary-500 transition-colors duration-500" wire:navigate>FAQs</a></li>
                    <li><a href="#" class="text-white hover:text-primary-500 transition-colors duration-500">Delivery Support</a></li>
                </ul>
            </div>
            <div class="space-y-3 bg-[hsla(0,0%,100%,.1)] px-5 py-6 rounded-md">
                <h6>Legal</h6>
                <ul class="text-sm space-y-2">
                    @foreach (DB::table('pages')->where('status', true)->get() as $page)
                        <li><a href="{{ route('page.details', $page->slug) }}" wire:navigate class="text-white hover:text-primary-500 transition-colors duration-500">{{ $page->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="space-y-3 bg-[hsla(0,0%,100%,.1)] px-5 py-6 rounded-md">
                <h6>Help</h6>
                <ul class="text-sm space-y-2">
                    <li>Phone: <a href="tel:{{ setting('phone') }}" class="text-white hover:text-primary-500 transition-colors duration-500">{{ setting('phone') }}</a></li>
                    <li>Email: <a href="mailto:{{ setting('email') }}" class="text-white hover:text-primary-500 transition-colors duration-500">{{ setting('email') }}</a></li>
                    <li>Opening Time: {{ setting('opening_time') }} - {{ setting('closing_time') }}</li>
                    <li>Friday - Closed</li>
                </ul>
            </div>
            {{-- <div class="space-y-3 bg-[hsla(0,0%,100%,.1)] px-5 py-6 rounded-md">
                <h6>Take Eatery with you</h6>
                <div class="space-y-5">
                    <div>
                        <a href="" target="_blank">
                            <img src="{{ Vite::image('appstore.png') }}" alt="App Store" class="h-10" />
                        </a>
                    </div>
                    <div>
                        <a href="" target="_blank">
                            <img src="{{ Vite::image('playmarket.png') }}" alt="Play Store" class="h-10" />
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="mt-4 flex justify-between">
            <div class="flex space-x-3">
                <a href="#" class="p-1 rounded-md bg-white text-primary-500 hover:bg-primary-500 hover:text-white duration-500 transition-colors" title="Facebook">
                    <i data-feather="facebook" class="w-4 h-4"></i>
                </a>
                <a href="#" class="p-1 rounded-md bg-white text-primary-500 hover:bg-primary-500 hover:text-white duration-500 transition-colors" title="Instagram">
                    <i data-feather="instagram" class="w-4 h-4"></i>
                </a>
                <a href="#" class="p-1 rounded-md bg-white text-primary-500 hover:bg-primary-500 hover:text-white duration-500 transition-colors" title="Youtube">
                    <i data-feather="youtube" class="w-4 h-4"></i>
                </a>
                <a href="#" class="p-1 rounded-md bg-white text-primary-500 hover:bg-primary-500 hover:text-white duration-500 transition-colors" title="Twitter">
                    <i data-feather="twitter" class="w-4 h-4"></i>
                </a>
            </div>
            <div>
                <p class="text-sm text-[#8b9191]">{{ setting('copyright') }}</p>
            </div>
        </div>
    </div>
</footer>
