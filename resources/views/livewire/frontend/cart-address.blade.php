<div class="bg-white rounded-md p-4 shadow">
    <div class="space-y-4">
        <p class="font-semibold text-md">Delivery Address</p>

        <div class="grid grid-cols-2 gap-5" x-data="{ isDefault: true }">
            @if (auth()->check() && auth()->user()->address_book !== null)
                @foreach (auth()->user()->address_book as $key => $item)
                    <div @if (!$ispage) wire:click="updateAddressKey('{{ $key }}')" @endif class="p-4 space-y-2 rounded-md transition duration-500 col-span-2 sm:col-span-1 cursor-pointer {{ $addressKey === $key ? 'border-2 border-primary-500' : 'border' }}">
                        <div class="flex justify-between items-center">
                            <h6>{{ $item->type }}</h6>
                            @if ($loop->first)
                                <p class="bg-primary-700 text-white px-2 py-1 text-xs rounded-md">Default</p>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-xs">{{ $item->location }}</p>
                            </div>
                            <div>
                                <button wire:click="showModal({{ $key }})" class="text-white px-2 py-1 text-sm rounded-md bg-info-500">Edit</button>
                                <button wire:click="delete({{ $key }})" class="text-white px-2 py-1 text-sm rounded-md bg-warning-500">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div>
            <button type="button" @auth wire:click='showModal' @else wire:click="$dispatch('updateParam', { key: 'show', value: {{ true }} })" @endauth class="w-full text-sm uppercase bg-primary-500 text-center py-2 block rounded-md text-white">Add new address</button>
        </div>
    </div>

    <div
        x-data
        class="fixed inset-0 z-[700] w-full h-full flex flex-col items-center justify-center bg-gray-600 bg-opacity-50"
        x-transition:enter="transition ease-out duration-1000"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-show="$wire.show"
    >
        <div
            class="w-full transition-all transform sm:max-w-lg product-details-modal-content rounded-md"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4 sm:translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4 sm:translate-y-4"
            @click.away="$wire.set('show', false)"
        >
            <div class="shadow-sm bg-white rounded-md relative p-10">
                <div @click="$wire.set('show', false)" class="absolute top-2 right-4 bg-white w-10 h-10 rounded-full flex items-center justify-center z-[1] cursor-pointer shadow">
                    <svg class="w-5 h-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div class="w-full h-100 space-y-5">
                    <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">
                        @if ($update) Update address @else Add new address @endif
                    </h1>
                    <form wire:submit="submit" class="mt-6">
                        {{--  @if ($show) x-init="setInterval(() => $wire.set('location', $refs.location.value), 2000)" @endif --}}
                        <div class="space-y-3">
                            <x-frontend.form-group label="type" wire:model="type" isType="select">
                                @foreach (['Home','Work'] as $item)
                                    <option value="{{ $item }}" @selected($item == $type)>{{ $item }}</option>
                                @endforeach
                            </x-frontend.form-group>

                            {{-- <x-frontend.form-group label="location" wire:model="location" placeholder="Enter location" autocomplete="off" x-ref="location" /> --}}
                            <x-frontend.form-group
                                label="location"
                                isType="custom"
                                column="relative"
                            >
                                <input type="text" wire:model="location" wire:keydown="search" placeholder="Enter location" autocomplete="off" class="form-control">

                                <div class="absolute w-full max-h-64 top-16 bg-white shadow-md overflow-y-auto">
                                    <ul>
                                        @foreach ($results as $key => $result)
                                            <li class="px-4 py-1 hover:bg-light hover:transition-colors cursor-pointer truncate" wire:click="setValue({{ $key }})">{{ $result['description'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-frontend.form-group>

                            <x-frontend.submit-button :label="$update ? 'Update':'Submit'" wire:loading.attr="disabled" />
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
