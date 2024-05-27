@extends('layouts.app')

@section('title', 'Addons')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Menu Addons">

            @can('create_addon')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('food.addon.create')" title="Add new addon" icon="add" :addId="true" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No', 'Parent Addon', 'Name', 'Price', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script/>
@endpush
