<section class="bg-light pt-10 pb-20">
    <div class="container space-y-5">
        <div class="text-center">
            <h5 class="text-md">{{ $page->name }}</h5>
            <p>{{ $page->title }}</p>
        </div>
        <div class="max-w-[1200px] mx-auto">{!! $page->description !!}</div>
    </div>
</section>
