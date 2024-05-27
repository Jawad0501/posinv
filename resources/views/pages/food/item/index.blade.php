@extends('layouts.app')

@section('title', 'Menus')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Menus">

            @can('create_food')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('food.menu.create')" title="Add new menu" icon="add" :addId="true" />
                    <x-card-heading-button :url="route('food.menu.upload')" title="Upload menu" icon="upload" :addId="true" class="dark-bg" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Price', 'Vat(%)', 'Image', 'Action']" />
            </div>
        </x-page-card>
    </div>
@endsection

@push('js')
    <x-datatable.script/>
@endpush
