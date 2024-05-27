<x-form-modal 
    :title="isset($attendance) ? 'Attendance in':'Attendance out'" 
    :action="isset($attendance) ? route('attendance.update', $attendance->id) : route('attendance.store')"
    :button="isset($attendance) ? 'Attendance in':'Attendance out'"
    id="form"
>
    @isset($attendance)
        @method('PUT')
    @endisset

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
</x-form-modal>