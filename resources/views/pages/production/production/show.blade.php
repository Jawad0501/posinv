@extends('layouts.app')

@section('title', 'Production details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="Production details">
            
            <x-slot:cardButton>
                <x-card-heading-button :url="route('production.index')" title="Back to production" icon="back" />
                @isset($production)
                    @can('edit_production')
                        <x-card-heading-button :url="route('production.edit', $production->id)" title="Edit production" icon="edit" />
                    @endcan
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="15%">Food Name</th>
                                    <td>{{ $production->unit->food->food_name }}</td>
                                </tr>
                                <tr>
                                    <th>Variant Name</th>
                                    <td>{{ $production->unit->variant->name }}</td>
                                </tr>
                                <tr>
                                    <th>Production Date</th>
                                    <td>{{ format_date($production->production_date) }}</td>
                                </tr>
                                <tr>
                                    <th>Expire Date</th>
                                    <td>{{ format_date($production->expire_date) }}</td>
                                </tr>
                                <tr>
                                    <th>Serving Unit</th>
                                    <td>{{ $production->serving_unit }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>SN</th>
                                    <th>Ingredient(Code)</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                </tr>
                                @foreach ($production->unit->items as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>
                                            {{ $item->ingredient->name }} ({{ $item->ingredient->code }})
                                        </td>
                                        <td>{{ $item->quantity.'*'.$production->serving_unit}} = {{ ($item->quantity * $production->serving_unit) }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ convert_amount($item->price * $production->serving_unit) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <th>{{ convert_amount($production->unit->items->sum('price') * $production->serving_unit) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
        </x-page-card>
    </div>

@endsection
