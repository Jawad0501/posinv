@extends('layouts.app')

@section('title', 'Waste Details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="Waste Details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('waste.index')" title="Back to waste" icon="back" />
                @isset($waste)
                    <x-card-heading-button :url="route('waste.edit', $waste->id)" title="Edit waste" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Reference No</h3>
                        <span>{{ $waste->reference_no }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Responsible Person</h3>
                        <span>{{ $waste->staff->name }}</span>
                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <h3>Date</h3>
                        <span>{{ date('d/m/Y', strtotime($waste->date)) }}</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Food Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Loss Amount</th>
                                </tr>
                            </thead>

                            <tbody id="items">
                                @foreach (json_decode($waste->items, true) as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td> {{ $item['food_name'] }}</td>
                                        <td> {{ $item['quantity'] }}</td>
                                        <td> {{ convert_amount($item['price']) }}</td>
                                        <td> {{ convert_amount($item['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <h3>Note</h3>
                    <p>{{ $waste->note }}</p>
                </div>

            </div>
            
        </x-page-card>
    </div>

@endsection
