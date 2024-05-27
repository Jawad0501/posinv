@extends('layouts.app')

@section('title', 'Profit Loss Report')

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
                        <x-form-group name="to" class="datepicker" placeholder="End date" column="col-md-3" :value="date('Y-m-d')" :islabel="false" />

                        <div class="col-md-3">
                            <x-submit-button text="Search" :isReport="true" />
                        </div>
                    </div>
                </form>

            </div>

        </x-page-card>
    </div>

    <div class="col-12 my-3 mx-auto">

        <x-page-card title="Profit Loss Report">

            <x-slot:cardButton>
                <x-card-heading-button title="Reload" icon="reload" id="reloadData" />
                <x-card-heading-button title="Print" icon="print" id="printData" />
            </x-slot>

            <div class="card-body" id="loss_profit_report"></div>

        </x-page-card>

        <x-page-card class="mt-4">

            <div class="card-body">
                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                    @php
                        $tabs = ['profit-by-product', 'profit-by-invoice', 'profit-by-customer', 'profit-by-date'];
                    @endphp
                    @foreach ($tabs as $key => $tab)
                        <li class="nav-item" role="presentation">
                            <button @class(['nav-link', 'active' => $key == 0]) id="pills-{{ $tab }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $tab }}" type="button" role="tab" aria-controls="pills-{{ $tab }}" aria-selected="true">{{ str_replace('-', ' ', $tab) }}</button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-{{ $tabs[0] }}" role="tabpanel" aria-labelledby="pills-{{ $tabs[0] }}-tab" tabindex="0">
                        <x-table :items="['Sl No', 'Product', 'Lot', 'Purchase Price', 'Purchase Quantity', 'Sell Quantity', 'Gross Profit']">
                            <x-slot name="tfoot">
                                <th colspan="6" class="text-end">Total:</th>
                                <th></th>
                            </x-slot>
                        </x-table>
                    </div>
                    <div class="tab-pane fade" id="pills-{{ $tabs[1] }}" role="tabpanel" aria-labelledby="pills-{{ $tabs[1] }}-tab" tabindex="0">
                        <x-table :items="['Sl No', 'Invoice', 'Gross Profit']" id="invoice_table">
                            <x-slot name="tfoot">
                                <th colspan="2" class="text-end">Total:</th>
                                <th></th>
                            </x-slot>
                        </x-table>
                    </div>
                    <div class="tab-pane fade" id="pills-{{ $tabs[2] }}" role="tabpanel" aria-labelledby="pills-{{ $tabs[2] }}-tab" tabindex="0">
                        <x-table :items="['Sl No', 'Customer', 'Gross Profit']" id="customer_table">
                            <x-slot name="tfoot">
                                <th colspan="2" class="text-end">Total:</th>
                                <th></th>
                            </x-slot>
                        </x-table>
                    </div>
                    <div class="tab-pane fade" id="pills-{{ $tabs[3] }}" role="tabpanel" aria-labelledby="pills-{{ $tabs[3] }}-tab" tabindex="0">
                        <x-table :items="['Sl No', 'Date', 'Gross Profit']" id="date_table">
                            <x-slot name="tfoot">
                                <th colspan="2" class="text-end">Grand Total:</th>
                                <th></th>
                            </x-slot>
                        </x-table>
                    </div>
                </div>
            </div>
        </x-page-card>
    </div>

@endsection

@push('js')
    <x-datatable.script />
@endpush
