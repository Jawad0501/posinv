<section class="section bg-light">
    <div class="container space-y-10">
        <div class="bg-white shadow rounded-md max-w-[500px] mx-auto">
            <div class="py-20 px-5 space-y-5">
                <div class="text-center">
                    <h4>Make a Reservation</h4>
                    <p class="text-gray-600">Check our availability and reserve you <span id="timer"></span> place</p>
                </div>
                <form wire:submit='submit'>
                    <div class="space-y-5">
                        <x-frontend.form-group label="total_person" wire:model.live='total_person' type="number" placeholder="Total person" :islabel="false" />
                        <x-frontend.form-group label="expected_date" isType="custom" :islabel="false">
                            <x-frontend.datepicker id="expected_date" wire:model.live="expected_date" />
                        </x-frontend.form-group>
                        <x-frontend.form-group label="expected_time" wire:model="expected_time" type="number" placeholder="Total person" :islabel="false" isType="select">
                            <option value="">Select time</option>
                            @foreach (reservationTimeSlots() as $time)
                                <option value="{{ $time }}" @disabled(!checkReservationAvailability($expected_date, $time, $total_person))>{{ $time }}</option>
                            @endforeach
                        </x-frontend.form-group>
                        <x-frontend.form-group label="contact_no" wire:model="contact_no" placeholder="Contact Number" :islabel="false" />
                        <x-frontend.submit-button wire:loading.attr="disabled" wire:loading.class="btn-loading" wire:target='submit' />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
