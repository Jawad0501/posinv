@extends('layouts.app')

@section('title', 'Ads List')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Ads List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('frontend.ad.create')" title="Add New ad" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Image', 'Title', 'Link', 'Type', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script />

<script>
    var table = $('#table').DataTable({
        ajax: '{!! route('frontend.ad.index') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'id', searchable: false},
            {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row) {
                    return `<img src="${showUploadedFile(data)}" alt="${row.name}" width="70px" />`;
                }},
            {data: 'title', name: 'title'},
            {data: 'link', name: 'link'},
            {data: 'type', name: 'type'},
            {data: 'action', searchable: false, orderable: false}
        ]
    });
</script>
@endpush
