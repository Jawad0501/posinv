<section class="bg-light section">
    <div class="container space-y-5">
        <div>
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-[18px] font-medium">Favorite menu</h5>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-6 gap-x-5 gap-y-4">
                @forelse ($menus as $menu)
                    <livewire:frontend.single-menu :menu="$menu" />
                @empty
                    <div class="col-span-6">
                        <div class="bg-white shadow p-4">Favorite menu not available!</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
