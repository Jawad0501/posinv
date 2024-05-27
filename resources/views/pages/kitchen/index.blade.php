@extends('layouts.app')

@section('title', 'Sale Foods')

@section('content')

    <div class="kitchen-loading d-none" id="kitchen-loading">
        <div class="loadingio-spinner-blocks-c9l6kjiweq">
            <div class="ldio-2shmzovtr6v">
                <div style='left:38px;top:38px;animation-delay:0s'></div>
                <div style='left:80px;top:38px;animation-delay:0.125s'></div>
                <div style='left:122px;top:38px;animation-delay:0.25s'></div>
                <div style='left:38px;top:80px;animation-delay:0.875s'></div>
                <div style='left:122px;top:80px;animation-delay:0.375s'></div>
                <div style='left:38px;top:122px;animation-delay:0.75s'></div>
                <div style='left:80px;top:122px;animation-delay:0.625s'></div>
                <div style='left:122px;top:122px;animation-delay:0.5s'></div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <form id="kitchen_form" action="" method="post">
            <div class="row mt-3" id="all_orders"></div>
        </form>

    </div>

    <input type="hidden" name="audio" id="audio" value="{{ asset('assets/audio/beep.wav') }}" />

@endsection

@push('js')
    <script>
        window.statuses = @json($status)
    </script>
@endpush
