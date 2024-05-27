@extends('layouts.app')

@section('title', 'Asked Question')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 col-md-8 offset-md-2 my-5">
        <x-page-card title="Asked Question List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.asked-question.create')" title="Add New asked question" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Question', 'Action']" />
            </div>
            
        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('frontend.asked-question.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'question', name: 'question'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
