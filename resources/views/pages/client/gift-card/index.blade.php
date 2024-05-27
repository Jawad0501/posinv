@extends('layouts.app')

@section('title', 'Gift Card')

@push('css')
    <x-datatable.style />
@endpush

@section('content')
    <div class="col-12 my-5">
        <x-page-card title="Gift card payment">
            @can('create_gift_card')
                <x-slot:cardButton>
                    <x-card-heading-button :url="route('client.gift-card.create')" title="Add New gift card" icon="add" :addId="true" />
                </x-slot>
            @endcan

            <div class="card-body">
                <x-table :items="['Sl No','Customer Name','Amount','Status','TRX ID', 'BTC Wallet','Action']" />
            </div>
        </x-page-card>
    </div>
@endsection

@push('js')
    <x-datatable.script />
@endpush

