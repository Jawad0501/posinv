<div
    x-data
    class="fixed inset-0 z-[70] w-full h-full flex flex-col items-center justify-center bg-gray-600 bg-opacity-50"
    x-transition:enter="transition ease-out duration-1000"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-show="$wire.show"
>
    <div
        class="w-full h-full transition-all transform sm:max-w-lg product-details-modal-content rounded-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-headline"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 sm:translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4 sm:translate-y-4"
        @click.away="$wire.closeModal()"
    >
        @if ($menu)
            <div class="shadow-sm bg-white rounded-md h-full relative">
                <div
                    wire:click="closeModal()"
                    class="absolute top-2 right-4 bg-white w-10 h-10 rounded-full flex items-center justify-center z-[1] cursor-pointer shadow"
                >
                    <svg class="w-5 h-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="absolute top-0 w-full">
                    <div class="bg-transparent p-4">
                        <h3 class="text-lg text-center hidden">{{ $menu->name }}</h3>
                    </div>
                </div>

                <form wire:submit="addToCart" class="h-full relative">
                    <div class="overflow-y-auto h-[84%] sm:h-[85%]">
                        <div class="rounded-md">
                            <img src="{{ uploaded_file($menu->image) }}" alt="" srcset="" class="rounded-t" />
                        </div>
                        <div class="p-4">
                            <div class="pb-5 border-b">
                                <h3 class="text-lg mb-2">{{ $menu->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $menu->description }}</p>
                            </div>

                            @if ($menu->variants->count())
                                <div class="mt-5">
                                    <p class="text-[20px] mb-3 font-semibold">Variants</p>
                                    <div>
                                        @foreach ($menu->variants as $variant)
                                            <div class="flex items-center border-b border-gray-100 p-2">
                                                <div>
                                                    <input type="radio" wire:model.defer="variant" id="{{ $variant->id }}" value="{{ $variant->id }}" class="opacity-0 absolute h-6 w-6" />
                                                    <div class="bg-white border rounded-md border-gray-100 w-5 h-5 flex justify-center items-center focus-within:border-secondary-500">
                                                        <svg class="fill-current hidden w-3 h-3" version="1.1" viewBox="0 0 17 12" xmlns="http://www.w3.org/2000/svg">
                                                            <g fill="none" fill-rule="evenodd">
                                                                <g transform="translate(-9 -11)" fill="#E56988" fill-rule="nonzero">
                                                                    <path d="m25.576 11.414c0.56558 0.55188 0.56558 1.4439 0 1.9961l-9.404 9.176c-0.28213 0.27529-0.65247 0.41385-1.0228 0.41385-0.37034 0-0.74068-0.13855-1.0228-0.41385l-4.7019-4.588c-0.56584-0.55188-0.56584-1.4442 0-1.9961 0.56558-0.55214 1.4798-0.55214 2.0456 0l3.679 3.5899 8.3812-8.1779c0.56558-0.55214 1.4798-0.55214 2.0456 0z" />
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <label for="{{ $variant->id }}" class="ml-2 text-sm text-gray-500">
                                                    <span >{{ $variant->name }}</span>
                                                    <span class="text-gray-500">+{{ convert_amount($variant->price) }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($menu->addons->count())
                                <div class="mt-5">
                                    <p class="text-[20px] mb-3 font-semibold">Addons</p>
                                    <div class="space-y-3">
                                        @foreach ($addons as $key => $addon)
                                            <div class="flex items-center justify-between text-sm">
                                                <p>{{ $addon['name'] }}</p>
                                                <div class="flex items-center space-x-1">
                                                    <p>+{{ convert_amount($addon['price']) }}</p>
                                                    <div class="flex items-center space-x-1">
                                                        <div wire:click="quantityUpdate(false, '{{ $key }}')">
                                                            <svg class="w-5 h-5 {{ $addon['quantity'] > 0 ? 'text-primary-500':'text-gray-300 cursor-not-allowed' }} cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <p>{{ $addon['quantity'] }}</p>
                                                        <div wire:click="quantityUpdate(true, '{{ $key }}')">
                                                            <svg class="w-5 h-5 text-primary-500 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-5">
                                <textarea name="" id="" class="w-full p-4 border border-primary-500 focus-visible:outline-none rounded-md" placeholder="Write note"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="w-full absolute bottom-0">
                        <div>
                            <div class="bg-white p-4 space-y-3 shadow-[0_-1px_4px_0_rgba(0,0,0,.08)] rounded-md">
                                <div class="flex items-center space-x-10 justify-center">
                                    <div wire:click="quantityUpdate(false)">
                                        <svg class="w-6 h-6 {{ $quantity > 1 ? 'text-primary-500':'text-gray-300 cursor-not-allowed' }} cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-lg">{{ $quantity }}</p>
                                    <div wire:click="quantityUpdate()">
                                        <svg class="w-6 h-6 text-primary-500 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <x-frontend.submit-button label="Apply" wire:loading.attr="disabled" wire:target="addToCart" wire:loading.class="btn-loading" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
