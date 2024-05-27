@extends('layouts.app')

@section('title', 'Subscriber')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 col-md-8 offset-md-2 my-5">
        <x-page-card title="Subscriber List">
            <div class="card-body">
                <x-table :items="['Sl No', 'Email', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('frontend.subscriber.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'email', name: 'email'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
