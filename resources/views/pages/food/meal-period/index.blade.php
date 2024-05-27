@extends('layouts.app')

@section('title', 'Service')

@push('css')
    <x-datatable.style/>
@endpush

@section('content')

    <div class="col-12 col-md-12 col-lg-12 my-5">

        <x-page-card title="Service">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('food.meal-period.create')" title="New Service" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Name', 'Start Time', 'End Time', 'Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')

    <x-datatable.script/>
@endpush
