<div>
    @php($content = json_decode(setting('hero_section_content')))
    <section class="h-[650px] bg-[41%] md:bg-[30%] bg-cover bg-no-repeat" style="background-image: url('/storage/placeholder-image.jpg')">
        <div class="container h-full">
            <div class="flex h-full items-center text-white">

                <div class="md:w-3/4 lg:w-3/4 xl:w-1/2 space-y-8 text-center md:text-left">
                    <h1 class="text-xl lg:text-4xl">{{ $content->heading }}</h1>
                    <p class="font-open-sans">{{ $content->description }}</p>
                    <div class="flex space-x-4 items-center justify-center md:justify-start">
                        <a href="{{ route('menu') }}" wire:navigate class="bg-primary-800 text-white rounded-3xl border border-primary-800 py-3 px-8 sm:px-10 transition hover:bg-transparent hover:text-primary-800">Order Now</a>
                        @if (setting('reservation_allows'))
                            <a href="{{ route('reservation') }}" wire:navigate class="bg-transparent text-primary-800 rounded-3xl border border-primary-800 py-3 px-8 sm:px-10 transition hover:bg-primary-800 hover:text-white">Reservation</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light py-10" x-data="categories($refs.categories)">
        <div class="container">

            <div class="h-full relative">
                <!-- Slider main container -->

                <div class="swiper" x-ref="categories">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            <div class="swiper-slide">
                                <div class="bg-white rounded shadow text-center">
                                    <a class="block p-2 text-center" href="{{ route('menu') }}#{{ $category->slug }}">
                                        <img src="{{ uploaded_file($category->image) }}" class="saturate-200 mx-auto rounded-full w-14 h-14 bg-cover">
                                        <p class="mt-2 text-sm text-gray-500 truncate">{{ $category->name }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <!-- Slides -->
                    </div>
                </div>
                <!-- If we need navigation buttons -->
                <div x-ref="previews" @click="slidePrev($refs.previews)" class="cursor-pointer swiper-button-prev absolute left-0 top-2/4 w-8 h-8 translate-y-[-50%] translate-x-[-50%] bg-white rounded-full shadow z-10 flex items-center justify-center">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </div>
                <div x-ref="next" @click="slideNext($refs.next)" class="cursor-pointer swiper-button-next absolute right-[-7.5%] sm:right-[-4.5%] md:right-[-3.5%] xl:right-[-2.5%] 2xl:right-[-1.5%] top-2/4 w-8 h-8 translate-y-[-50%] translate-x-[-50%] bg-white rounded-full shadow z-10 flex items-center justify-center">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>

        </div>
    </section>


    <livewire:frontend.popular-section />

    <livewire:frontend.trending-section />

    <section class="bg-white section" x-data="ads($refs.ads)">
        <div class="container">
            <div class="h-full relative">
                <!-- Slider main container -->
                <div class="swiper"  x-ref="ads">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        @foreach ($ads as $ad)
                            <div class="swiper-slide">
                                <div class="rounded shadow text-center">
                                    <a href="{{ $ad->link !== null ? $ad->link : 'javascript:void(0)' }}" @if ($ad->link !== null) target="{{ $ad->type }}" @endif>
                                        <img src="{{ uploaded_file($ad->image) }}" class="rounded" alt="{{ $ad->title }}" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- If we need navigation buttons -->
                <div x-ref="previews" @click="slidePrev($refs.previews)" class="cursor-pointer swiper-button-prev absolute left-0 top-2/4 w-8 h-8 translate-y-[-50%] translate-x-[-50%] bg-white rounded-full shadow z-10 flex items-center justify-center">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </div>
                <div x-ref="next" @click="slideNext($refs.next)" class="cursor-pointer swiper-button-next absolute right-[-7.5%] sm:right-[-4.5%] md:right-[-3.5%] xl:right-[-2.5%] 2xl:right-[-1.5%] top-2/4 w-8 h-8 translate-y-[-50%] translate-x-[-50%] bg-white rounded-full shadow z-10 flex items-center justify-center">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </div>
    </section>
</div>
