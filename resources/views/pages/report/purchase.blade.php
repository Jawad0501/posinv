@extends('layouts.app')

@section('title', 'Purchase Report')

@push('css')
    <x-datatable.style />
@endpush

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Filter Data">
            <div class="card-body">
                <form action="/" method="GET" id="filterData">
                    <div class="row">
                        <x-form-group name="from" class="datepicker" placeholder="Start date" column="col-md-3" :islabel="false" />
                        <x-form-group name="to" class="datepicker" placeholder="End date" column="col-md-3" :islabel="false" />
                        <div class="col-md-3">
                            <x-submit-button text="Search" :isReport="true" />
                        </div>
                    </div>
                </form>
            </div>
        </x-page-card>

        <div class="my-4"></div>

        <x-page-card title="Purchase Report">

            <x-slot:cardButton>
                <x-card-heading-button title="Reload" icon="reload" id="reloadData" />
            </x-slot>

            <div class="card-body">
                <x-table :items="['Sl No', 'Reference No', 'Supplier', 'Date', 'Grand Total', 'Due Amount', 'Action']">
                    <x-slot name="tfoot">
                        <th colspan="4" class="text-end">Total:</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </x-slot>
                </x-table>
            </div>

        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
@endpush
