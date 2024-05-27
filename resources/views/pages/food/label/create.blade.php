@extends('layouts.app')

@section('title', isset($expense) ? 'Update expense':'Add New expense')

@push('css')
    <style>
        .search-menu {
            top: 40px;
        }
        .search-menu li {
            cursor: pointer;
            transition: color 0.3s ease-in;
        }
        .search-menu li:hover {
            background-color: #fbfbfb;
        }
    </style>
@endpush

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Add products to generate Labels">
            <div class="card-body">
                <form action="{{ route('food.printlabel.print') }}" method="get" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-xl-10 mx-xl-auto">
                            <div class="position-relative">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control" name="keyword" id="seach-menu" placeholder="Enter products name to print labels" autocomplete="off" />
                                </div>
                                <div class="position-absolute w-100 bg-white shadow rounded search-menu" style="top: 40px">
                                    <ul class="list-unstyled p-0 m-0" id="searched-menus">

                                    </ul>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="labelMenus">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th>Variant</th>
                                            <th>No. of labels</th>
                                            <th>Packing Date</th>
                                            <th>Exp Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <x-form-group name="barcode_setting" isType="select">
                                    @foreach ($barcodes as $barcode)
                                        <option value="{{ $barcode->id }}">{{ "$barcode->name, $barcode->description" }}</option>
                                    @endforeach
                                </x-form-group>
                            </div>

                            <div class="">
                                <x-submit-button text="Preview" :disabled="true" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </x-page-card>
    </div>
@endsection
