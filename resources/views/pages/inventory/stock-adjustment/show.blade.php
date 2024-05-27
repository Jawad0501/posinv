@extends('layouts.app')

@section('title', 'Stock Adjustment Details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="Stock Adjustment Details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('stock-adjustment.index')" title="Back to stock adjustment" icon="back" />
                @isset($stockAdjustment)
                    <x-card-heading-button :url="route('stock-adjustment.edit', $stockAdjustment->id)" title="Edit stock adjustment" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Reference No</h3>
                        <span>{{ $stockAdjustment->reference_no }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Responsible Person</h3>
                        <span>{{ $stockAdjustment->staff->name }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Date</h3>
                        <span>{{ format_date($stockAdjustment->date) }}</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Ingredient(Code)</th>
                                    <th>Quantity/Amount</th>
                                    <th>Consumption Status</th>
                                </tr>
                            </thead>

                            <tbody id="items">
                                @foreach ($stockAdjustment->items as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>
                                            {{ $item->ingredient->name }} ({{ $item->ingredient->code }})
                                        </td>
                                        <td>{{ $item->quantity_amount }} {{ $item->ingredient->unit->name }}</td>
                                        <td>{{ $item->consumption_status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <h3>Note</h3>
                    <p>{{ $stockAdjustment->note }}</p>
                </div>

            </div>
            
        </x-page-card>
    </div>

@endsection
