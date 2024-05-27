<section class="section bg-light">
    <div class="container space-y-10">
        <form method="GET">
            <div class="flex items-center">
                <form wire:submit='submit'>
                    <div class="relative w-full">
                        <input type="text" name="search" wire:model="search" class="w-full rounded-3xl border border-gray-100 py-2 px-4 focus-visible:outline-0" placeholder="Search menu..." />
                        <button type="submit" class="absolute right-0 top-0 bg-primary-500 px-5 h-full rounded-r-3xl text-white">
                            <i data-feather="search" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </form>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-4 mt-10">
            @if ($menus->count())
                @foreach ($menus as $key=> $menu)
                    <livewire:frontend.single-menu :menu="$menu" :horizontal="true" wire:key="{{ $key }}" />
                @endforeach
            @else
                <div class="bg-white p-4 col-span-4">
                    <p>Menu not available</p>
                </div>
            @endif
            <div wire:loading class="col-span-1"> Searching...</div>
        </div>
    </div>
</section>
