@extends('layouts.app')

@section('title', isset($expense) ? 'Update expense':'Add New expense')

@section('content')

    <div class="col-12 col-lg-8 offset-lg-2 my-5">

        <x-page-card :title="isset($expense) ? 'Update expense':'Add New expense'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('expense.index')" title="Back to expense" icon="back" />
            </x-slot>

            <form id="form" action="{{ isset($expense) ? route('expense.update', $expense->id) : route('expense.store')}}" method="post" data-redirect="{{ route('expense.index') }}">
                @csrf
                @isset($expense)
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
                                <option value="{{ $person->id }}" @isset($expense) @selected($expense->staff_id == $person->id ? true:false) @endisset>{{ $person->name }}</option>
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
                                        <option value="{{ $category->id }}" @isset($expense) @selected($expense->category_id == $category->id ? true:false) @endisset>{{ $category->name }}</option>
                                    @endforeach
                                </x-form-group>
                                <div class="col-1 mt-3">
                                    <a href="{{ route('expense-category.create') }}" class="btn" id="addBtn"><i class="icofont-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <x-form-group
                            name="amount"
                            placeholder="Enter amount"
                            :value="$expense->amount ?? ''"
                            column="col-md-6"
                        />

                        <x-form-group
                            name="date"
                            placeholder=" date"
                            :value="$expense->date ?? date('Y-m-d')"
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
                            {{$expense->note ?? ''}}
                        </x-form-group>

                        <div class="col-md-6">
                            <x-form-status :status="$expense->status ?? true" />
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <x-submit-button :text="isset($expense) ? 'Update':'Save'" />
                </div>
            </form>


            {{-- This input use for add category after get all categories --}}
            <input type="hidden" id="get_select2_data" value="category" data-selected="{{ true }}" data-url="{{ route('expense.category') }}"  />

        </x-page-card>
    </div>
@endsection
