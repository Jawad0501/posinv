@extends('layouts.app')

@section('title', 'Customer')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Customer List">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('client.customer.create')" title="Add New customer" icon="add" :addId="true" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No','Name','Email','Phone','Action']" />
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
    <script>

        // function cities() {
        //     var options = {
        //         types: ['(postal_code)']
        //     };
        //     console.log(google.maps);
        //     var location = document.getElementById('location');
        //     var autoComplete = new google.maps.places.Autocomplete(location)
        // }
    </script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.map_api_key') }}&libraries=places&callback=cities" type="text/javascript"></script> --}}
@endpush

