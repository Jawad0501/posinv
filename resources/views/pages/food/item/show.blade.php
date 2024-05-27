@extends('layouts.app')

@section('title', 'Menu Details')

@section('content')

    <div class="col-12 my-5">
        <x-page-card title="Menu Details">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('food.menu.index')" title="Back to menu" icon="back" />
                @isset($food)
                    <x-card-heading-button :url="route('food.menu.edit', $food->id)" title="Edit menu" icon="edit" />
                @endisset
            </x-slot>

            <div class="card-body mt-3">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="10%">Name:</td>
                            <td>{{$food->name}}</td>
                        </tr>
                        <tr>
                            <td>Price:</td>
                            <td>{{ convert_amount($food->price) }}</td>
                        </tr>

                        @if ($food->ingredient_id == null)
                            <tr>
                                <td>Calorie:</td>
                                <td>{{ $food->calorie }}</td>
                            </tr>

                            <tr>
                                <td>Processing Time:</td>
                                <td>{{ $food->processing_time }}</td>
                            </tr>
                        @endif


                        <tr>
                            <td>Vat:</td>
                            <td>{{$food->tax_vat}}</td>
                        </tr>
                        <tr>
                            <td>Description:</td>
                            <td>{!! $food->description !!}</td>
                        </tr>

                        <tr>
                            <td>Categories:</td>
                            <td>
                                <ul>
                                    @foreach($food->categories as $category)
                                        <li>{{ $category->name }}</li>
                                    @endforeach
                                </ul>

                            </td>
                        </tr>
                        @if ($food->ingredient_id == null)
                            <tr>
                                <td>Meal Periods:</td>
                                <td>
                                    <ul>
                                        @foreach($food->mealPeriods as $mealPeriod)
                                            <li>{{ $mealPeriod->name }}</li>
                                        @endforeach
                                    </ul>

                                </td>
                            </tr>
                            <tr>
                                <td>Addons:</td>
                                <td>
                                    <ul>
                                        @foreach($food->addons as $addon)
                                            <li>
                                                Name : {{$addon->name }}
                                                Price : {{ convert_amount($addon->price) }}
                                            </li>
                                        @endforeach
                                    </ul>

                                </td>
                            </tr>
                            <tr>
                                <td>Allergies:</td>
                                <td>
                                    <ul>
                                        @foreach($food->allergies as $allergy)
                                            <li class="mb-2">
                                                <img width="34" height="31" src="{{ uploaded_file($allergy->image) }}">
                                                {{$allergy->name}}
                                            </li>
                                        @endforeach
                                    </ul>

                                </td>
                            </tr>
                            <tr>
                                <td>Ingredient:</td>
                                <td>{{$food->ingredient->name ?? ''}}</td>
                            </tr>
                        @endif

                        <tr>
                            <td>Image:</td>
                            <td>
                                <img width="300" height="300" src="{{ uploaded_file($food->image) }}">
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </x-page-card>
    </div>

@endsection
