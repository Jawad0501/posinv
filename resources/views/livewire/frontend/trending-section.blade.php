@if ($menus->count())

<section class="bg-light section">
    <div class="container">
        <div>
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-[18px] font-medium">Trending this week</h5>
                <div>
                    <a href="{{ route('menu') }}" class="text-xs font-open-sans text-primary-700 flex items-center" wire:navigate>
                        <span>View All</span>
                        <svg class="w-[10px] h-[10px] ml-1" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5 gap-x-5 gap-y-4">
                @foreach ($menus as $menu)
                    <livewire:frontend.single-menu :menu="$menu" :horizontal="true" />
                @endforeach
            </div>
        </div>
    </div>
</section>

@else
    <div>
        <span></span>
    </div>
@endif
