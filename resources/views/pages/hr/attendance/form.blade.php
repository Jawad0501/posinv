<x-form-modal
    :title="isset($attendance) ? 'Update attendance':'Add new attendance'"
    action="{{ isset($attendance) ? route('attendance.update', $attendance->id) : route('attendance.store')}}"
    :button="isset($attendance) ? 'Update':'Submit'"
    id="form"
>
    @isset($attendance)
        @method('PUT')
    @endisset

    @isset($attendance)
        <div class="text-center" id="attendance_time">
            {{ date('H:i:s A') }}
        </div>

        <script>

            var timeInterval = setInterval(timer, 1000);

            function timer() {

                try {
                    document.getElementById('attendance_time').innerHTML = new Date().toLocaleTimeString();
                }
                catch (error) {
                    clearInterval(timeInterval);
                }
            }
        </script>
    @else
        <x-form-group name="staff" isType="select" class="select2">
            <option value="">Select staff</option>
            @foreach ($staff as $data)
                @php
                    $selected = !auth('staff')->user()->isAdmin() && $data->id == auth('staff')->id() ? true : false;
                @endphp
                <option value="{{ $data->id }}" @selected($selected)>{{ $data->name }}</option>
            @endforeach
        </x-form-group>
    @endisset
</x-form-modal>
