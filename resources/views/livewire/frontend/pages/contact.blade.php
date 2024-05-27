<section class="bg-light pt-10 pb-20">
    <div class="container space-y-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-10">
            <div class="space-y-5">
                <h3 class="text-lg">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                    <div class="bg-white border border-gray-100 p-8 text-center space-y-5">
                        <img src="{{ Vite::image('icons/location.svg') }}" class="h-12 mx-auto rotate-360 transition-transform duration-500" alt="" srcset="">
                        <h6 class="font-semibold">{{ $content->address_title }}</h6>
                        <p>{!! $content->address !!}</p>
                    </div>
                    <div class="bg-white border border-gray-100 p-8 text-center space-y-5">
                        <img src="{{ Vite::image('icons/mail.svg') }}" class="h-12 mx-auto rotate-360 transition-transform duration-500" alt="" srcset="">
                        <h6 class="font-semibold">{{ $content->email_title }}</h6>
                        <p>{!! $content->email !!}</p>
                    </div>
                    <div class="bg-white border border-gray-100 p-8 text-center space-y-5">
                        <img src="{{ Vite::image('icons/phone.svg') }}" class="h-12 mx-auto rotate-360 transition-transform duration-500" alt="" srcset="">
                        <h6 class="font-semibold">{{ $content->phone_title }}</h6>
                        <p>{!! $content->phone !!}</p>
                    </div>
                    <div class="bg-white border border-gray-100 p-8 text-center space-y-5">
                        <img src="{{ Vite::image('icons/support.svg') }}" class="h-12 mx-auto rotate-360 transition-transform duration-500" alt="" srcset="">
                        <h6 class="font-semibold">{{ $content->support_title }}</h6>
                        <p>{!! $content->support !!}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-5">
                <h3 class="text-lg">Write to Us</h3>
                <form wire:submit='submit'>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-5">
                        <x-frontend.form-group label="name" wire:model='name' placeholder="Your Name" />
                        <x-frontend.form-group label="email" wire:model='email' placeholder="Your Email" />
                        <x-frontend.form-group label="phone" wire:model='phone' placeholder="Your Phone" />
                        <x-frontend.form-group label="subject" wire:model='subject' placeholder="Write Subject" />
                        <x-frontend.form-group label="message" wire:model='message' placeholder="Write Message" isType="textarea" rows="4" column="col-span-2" />

                        <div class="col-span-2">
                            <x-frontend.submit-button label="Send Message" wire:loading.attr="disabled" wire:loading.class="btn-loading" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
