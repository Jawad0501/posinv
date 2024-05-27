@extends('layouts.app')

@section('title', 'Purchase Details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="Purchase Details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('purchase.index')" title="Back to Purchase" icon="back" />
                @isset($purchase)
                    <x-card-heading-button :url="route('purchase.edit', $purchase->id)" title="Edit Purchase" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <div class="row d-none">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Reference No</h3>
                        <span>{{ $purchase->reference_no }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Supplier</h3>
                        <span>{{ $purchase->supplier->name }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Date</h3>
                        <span>{{ format_date($purchase->date) }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="20%">Reference No</th>
                                    <td>{{ $purchase->reference_no }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier Name</th>
                                    <td>{{ $purchase->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ format_date($purchase->date) }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Type</th>
                                    <td>{{ ucfirst($purchase->payment_type) }}</td>
                                </tr>
                                @if ($purchase->payment_type == 'bank payment')
                                    <tr>
                                        <th>Bank Name</th>
                                        <td>{{ $purchase->bank->name ?? '' }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Total Total</th>
                                    <td>{{ convert_amount($purchase->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Shipping Charge</th>
                                    <td>{{ convert_amount($purchase->shipping_charge) }}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>{{ convert_amount($purchase->discount_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td>{{ convert_amount($purchase->grand_total) }}</td>
                                </tr>
                                <tr>
                                    <th>Paid Amount</th>
                                    <td>{{ convert_amount($purchase->paid_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Due Amount</th>
                                    <td>{{ convert_amount($purchase->due_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Details</th>
                                    <td>{{ $purchase->details }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity/Amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody id="items">
                                @foreach ($purchase->items as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>
                                            {{ $item->ingredient->name }}
                                        </td>
                                        <td>{{ convert_amount($item->unit_price) }}</td>
                                        <td>{{ $item->quantity_amount }} {{ $item->ingredient->unit->name }}</td>
                                        <td>{{ convert_amount($item->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
        </x-page-card>
    </div>

@endsection
