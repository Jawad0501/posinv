@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
    <div class="col-sm-8 col-md-6 mx-auto my-5">
        <x-page-card title="Attendance">
            <form id="form" action="{{ route('clock-in.store')}}" method="post">
                @csrf
                <div class="card-body">
                    <x-form-group name="id_number" placeholder="Enter your id number" autocomplete="off" />
                </div>
                <div class="card-footer">
                    <x-submit-button text="Clock In" class="disabled" />
                </div>
            </form>
        </x-page-card>

        <x-form-modal 
            title="Attendance" 
            :action="route('attendance.store')"
            button="Attendance in"
            id="form"
        >
            <div class="text-center">
                <h5 class="fs-5" id="main-text"></h5>
                <span id="attendance_time"></span>
            </div>
        </x-form-modal>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            
            $(document).on('keyup', 'input#id_number', function (e) {
                let btn = $('.card-footer button[type="submit"]');
                let btnText  = 'Attendance In';
                $(btn).addClass('disabled').text(btnText)
                
                $.ajax({
                    type: 'POST',
                    url: '{!! route('clock-in.check') !!}',
                    data: {
                        _token: csrf_token,
                        id_number: e.target.value
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        if(response == true) {
                            btnText = 'Clock Out';
                        }

                        $(btn).removeClass('disabled').text(btnText);
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                    },
                    error: function (err) {
                        handleError(err, false);
                    }
                });
            });
        });
    </script>
@endpush
