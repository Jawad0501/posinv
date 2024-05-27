@extends('layouts.app')

@section('title', 'Table Lists')

@section('content')
    <div class="col-12 col-md-12 col-lg-12 my-5">
        <x-page-card title="Tables">
            <div class="row">
                @foreach ($table_layouts as $table_layout)
                    <div id="tables" class="ui-widget-content draggable" style="height:100px;width:100px;float:left;padding: 100px">
                        <img style="height:120px;width:120px;" src="{{ uploaded_file($table_layout->image) }}" />
                    </div>
                @endforeach
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </x-page-card>
    </div>
@endsection

@push('js')

    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>

    <script>
        $(function() {
            $("#tables").draggable();

            var positions = JSON.parse(localStorage.positions || '{}');

            var d = $("[id=tables]").attr("id", function(i) {
                return "tables_" + i
            })
            $.each(positions, function(id, pos) {
                $("#" + id).css(pos)
            })

            d.draggable({
                containment: "#containment-wrapper",
                scroll: false,
                stop: function(event, ui) {
                    positions[this.id] = ui.position
                    localStorage.positions = JSON.stringify(positions)
                }
            });
        });
    </script>
@endpush
