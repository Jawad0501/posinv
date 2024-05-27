@extends('layouts.app')

@section('title', 'Menu Cost Details')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">
        <x-page-card title="Menu Cost Details">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('production-unit.index')" title="Back to menu cost" icon="back" />
                @isset($productionUnit)
                    <x-card-heading-button :url="route('production-unit.edit', $productionUnit->id)" title="Edit menu cost" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="15%">Food Name</th>
                                    <td>{{ $productionUnit->food->name }}</td>
                                </tr>
                                <tr>
                                    <th>Variant Name</th>
                                    <td>{{ $productionUnit->variant->name }}</td>
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
                                @foreach ($productionUnit->items as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>
                                            {{ $item->ingredient->name }} ({{ $item->ingredient->code }})
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ convert_amount($item->price) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <th>{{ convert_amount($productionUnit->items->sum('price')) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </x-page-card>
    </div>

@endsection
