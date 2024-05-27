<section class="section bg-light">
    <div class="container">
        <div class="flex justify-center max-w-4xl mx-auto">
            <div class="mr-8">
                <h4 class="font-medium mb-5">FAQs</h4>
                <div class="bg-white border border-gray-200" x-data="{ selected: null }">
                    <ul class="shadow-box">

                        @foreach ($faqs as $key => $faq)
                            <li class="relative border-b border-gray-200" wire:key="{{ $key }}">
                                <button type="button" class="w-full px-4 py-4 text-left" @click="selected !== {{ $loop->iteration }} ? selected = {{ $loop->iteration }} : selected = null">
                                    <div class="flex items-center justify-between" :class="selected == {{ $loop->iteration }} && 'text-primary-500'">
                                        <h6>{{ $faq->question }}</h6>
                                        <svg x-show="selected != {{ $loop->iteration }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <svg x-show="selected == {{ $loop->iteration }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                    </div>
                                </button>

                                <div class="relative overflow-hidden transition-all max-h-0 duration-700" style="" x-ref="container{{ $loop->iteration }}" x-bind:style="selected == {{ $loop->iteration }} ? 'max-height: ' + $refs.container{{ $loop->iteration }}.scrollHeight + 'px' : ''">
                                    <div class="p-6">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
