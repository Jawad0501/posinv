<x-form-modal
    :title="isset($reservation) ? 'Update reservation':'Add new reservation'"
    action="{{ isset($reservation) ? route('orders.reservations.update', $reservation->id) : route('orders.reservations.store')}}"
    :button="isset($reservation) ? 'Update':'Submit'"
    id="form"
    size="lg"
>
    @isset($reservation)
        @method('PUT')
    @endisset

    <div class="row">
        <x-form-group name="name" :value="$reservation->name ?? null" placeholder="Enter name" column="col-md-6" />
        <x-form-group name="email" type="email" :value="$reservation->email ?? null" placeholder="Enter email" column="col-md-6" />
        <x-form-group name="phone" :value="$reservation->phone ?? null" placeholder="Enter phone" column="col-md-6" />

        <x-form-group name="expected_date" :value="$reservation->expected_date ?? null" type="date" column="col-md-6" />

        <x-form-group name="expected_time" isType="select" column="col-md-6">
            @foreach (reservationTimeSlots() as $item)
                <option value="{{ $item }}">{{ $item }}</option>
            @endforeach
        </x-form-group>

        <x-form-group name="total_person" type="number" :value="$reservation->total_person ?? null" placeholder="Enter total person" column="col-md-6" />

        <x-form-group name="status" isType="select" column="col-md-6">
            @foreach ($status as $item)
                <option value="{{ $item }}" @selected(isset($reservation) && $reservation->status == $item)>{{ ucfirst($item) }}</option>
            @endforeach
        </x-form-group>

        <x-form-group name="occasion" :value="$reservation->occasion ?? null" placeholder="Enter name" column="col-12" placeholder="Reservation occasion" :required="false" />
        <x-form-group name="special_request" :value="$reservation->special_request ?? null" placeholder="Enter name" placeholder="Reservation special request" column="col-12" :required="false" />

    </div>
</x-form-modal>
