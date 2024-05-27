<section class="section bg-light">
    <div class="container space-y-10">
        <div class="bg-white shadow rounded-md max-w-[600px] mx-auto">
            <div class="py-20 px-5 space-y-5">
                <div x-data="{ count: 300, running: false }" class="space-y-4">
                    <h5 >Confirm Table Reservation</h5>
                    <div class="flex gap-3 flex-col sm:flex-row">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <p>{{ date('D, M Y', strtotime($reservation->expected_date)) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>{{ date('h:i A', strtotime($reservation->expected_time)) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <p>{{ $reservation->total_person }} people (Standard setting)</p>
                        </div>
                    </div>
                    <p class="text-gray-600 bg-primary-50 p-2 rounded-md">We're holding this table for you for <span x-text="Math.floor(count / 60) + ':' + ('0' + count % 60).slice(-2)"></span> minutes.</p>
                    <div x-init="running = true; setInterval(() => { if (count > 0) { count--; } else { clearInterval(interval); } }, 1000);"></div>
                </div>
                <form wire:submit='submit'>
                    <div class="space-y-5">
                        <x-frontend.form-group label="name" wire:model.live='name' placeholder="Enter your name" :islabel="false" />
                        <x-frontend.form-group label="email" wire:model.live='email' placeholder="Enter your email" :islabel="false" :required="false" />
                        <x-frontend.form-group label="What is the occasion" for="occasion" wire:model.live='occasion' placeholder="Occasion(optional)" :islabel="false" :required="false" />

                        <x-frontend.form-group label="Any special request" for="special_request" wire:model="special_request" placeholder="Request(optional)" :islabel="false" :required="false" />
                        <x-frontend.submit-button label="Confirm Reservation" wire:loading.attr="disabled" wire:loading.class="btn-loading" wire:target='submit' />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
