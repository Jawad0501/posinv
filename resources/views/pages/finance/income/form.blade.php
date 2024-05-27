@extends('layouts.app')

@section('title', isset($income) ? 'Update income':'Add New income')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">

        <x-page-card :title="isset($income) ? 'Update income':'Add New income'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('income.index')" title="Back to income" icon="back" />
            </x-slot>

            <form id="form" action="{{ isset($income) ? route('income.update', $income->id) : route('income.store')}}" method="post" data-redirect="{{ route('income.index') }}">
                @csrf
                @isset($income)
                    @method('PUT')
                @endisset

                <div class="card-body">
                    <div class="row">
                        <x-form-group
                            name="Responsible Person"
                            for="person"
                            isType="select"
                            class="select2"
                            column="col-md-6"
                        >
                            <option value="">Select person</option>
                            @foreach ($persons as $person)
                                <option value="{{ $person->id }}" @isset($income) @selected($income->staff_id == $person->id ? true:false) @endisset>{{ $person->name }}</option>
                            @endforeach
                        </x-form-group>

                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <x-form-group
                                    name="category"
                                    isType="select"
                                    column="col-11"
                                    class="select2"
                                >
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @isset($income) @selected($income->category_id == $category->id ? true:false) @endisset>{{ $category->name }}</option>
                                    @endforeach
                                </x-form-group>
                                <div class="col-1 mt-3">
                                    <a href="{{ route('income-category.create') }}" class="btn" id="addBtn"><i class="icofont-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <x-form-group
                            name="amount"
                            placeholder="Enter amount"
                            :value="$income->amount ?? ''"
                            column="col-md-6"
                        />

                        <x-form-group
                            name="date"
                            placeholder=" date"
                            :value="$income->date ?? date('Y-m-d')"
                            class="datepicker"
                            column="col-md-6"
                        />

                        <x-form-group
                            name="note"
                            isType="textarea"
                            :required="false"
                            column="col-12"
                            placeholder="Enter..."
                            rows="2"
                        >
                            {{$income->note ?? ''}}
                        </x-form-group>

                        <div class="col-md-6">
                            <x-form-status :status="$income->status ?? true" />
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <x-submit-button :text="isset($income) ? 'Update':'Save'" />
                </div>
            </form>


            {{-- This input use for add category after get all categories --}}
            <input type="hidden" id="get_select2_data" value="category" data-selected="{{ true }}" data-url="{{ route('income.category') }}"  />

        </x-page-card>
    </div>
@endsection
