@if ($horizontal)
    <div class="bg-white rounded-md shadow transition-shadow duration-500 delay-100 hover:shadow-2xl">
        <div wire:click="$dispatch('showModal', { slug: '{{ $menu->slug }}' })" class="p-4 cursor-pointer">
            <div class="flex space-x-3 justify-between w-full">
                <div class="space-y-1 text-left w-full">
                    <p class="font-open-sans whitespace-nowrap text-ellipsis overflow-hidden text-black font-medium ">
                        {{ $menu->name }}
                    </p>
                    <div>
                        <span class="text-gray-600 text-sm text-ellipsis overflow-hidden line-clamp-2">{{ $menu->description }}</span>
                    </div>
                    <p class="text-base text-[#585c5c] font-open-sans">{{ $menu->calorie }} kcal</p>
                    <p class="text-base text-[#585c5c]">{{ convert_amount($menu->price) }}</p>
                </div>
                <div class="w-[100px] h-[100px]">
                    <img src="{{ uploaded_file($menu->image) }}" class="w-full rounded-md bg-cover" />
                </div>
            </div>
        </div>
    </div>
@else
    <div class="bg-white rounded shadow relative transition-shadow duration-500 delay-100 hover:shadow-2xl">
        <div wire:click="$dispatch('showModal', { slug: '{{ $menu->slug }}' })" class="cursor-pointer">
            <img src="{{ uploaded_file($menu->image) }}" class="rounded-t w-full min-h-[16rem]">
            <div class="p-3">
                <p class="font-open-sans whitespace-nowrap text-ellipsis overflow-hidden text-black font-medium ">
                    {{ $menu->name }}
                </p>
                <p class="text-gray-600 text-sm text-ellipsis overflow-hidden line-clamp-2">{{ $menu->description }}</p>
                <div class="mt-3 flex space-x-1 items-center">
                    @foreach ($menu->allergies as $allergy)
                        <img src="{{ uploaded_file($allergy->image) }}" alt="" class="w-4 h-4 object-cover" />
                    @endforeach
                </div>
                <div class="mt-3">
                    <p class="text-sm text-gray-500">{{ convert_amount($menu->price) }}</p>
                </div>
            </div>
        </div>
        <button wire:click="$dispatch('addToFavorite', { slug: '{{ $menu->slug }}' })" class="absolute right-2 top-2 shadow w-6 h-6 {{ auth()->check() && in_array($menu->id, auth()->user()->favorites()->pluck('food_id')->toArray()) ? 'text-white bg-primary-500':'bg-white text-primary-700' }} rounded-full flex items-center justify-center  hover:bg-primary-500 hover:text-white transition-colors duration-500">
            <i data-feather="heart" class="w-4 h-4"></i>
        </button>
    </div>
@endif
