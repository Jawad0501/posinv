<div x-data="{ active: null}">
    <section class="py-4 sticky top-[88px] left-0 bg-white border-t border-gray-200 z-[0]">
        <div class="container">
            <ul class="flex align-items-center flex-nowrap overflow-x-auto relative space-x-3 py-4" id="menuCategories">
                @foreach ($categories as $key => $category)
                    @if ($category->foods->count())
                        <li class="category-item {{ $category->slug }}">
                            <a href="#{{ $category->slug }}" class="category-item-link" @click="active = '{{ $category->slug }}'">{{ $category->name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </section>
    <section class="section bg-light">
        <div class="container">
            <div class="grid grid-cols-6 gap-y-5 gap-x-10">
                <div class="col-span-6 xl:col-span-4 space-y-5">
                    @foreach ($categories as $category)
                        @if ($category->foods->count())
                            <div id="{{ $category->slug }}" class="sections" :class="active == '{{ $category->slug }}' && 'pt-48' ">
                                <div class="mb-3" name="{{ $category->slug }}">
                                    <h5 class="text-[18px] font-semibold">{{ $category->name }}</h5>
                                    <p>{{ $category->description }}</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-x-5 gap-y-4">
                                    @foreach ($category->foods as $menu)
                                        <livewire:frontend.single-menu :menu="$menu" :horizontal="true" />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="hidden xl:block col-span-2">
                    <div class="sticky left-0 top-[190px]">
                        <livewire:frontend.cart-item />
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
