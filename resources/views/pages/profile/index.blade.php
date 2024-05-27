@extends('layouts.app')

@section('title', 'Update Profile')

@section('content')

    <div class="col-12 my-5">

        <x-page-card title="Update Profile">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('dashboard')" title="Back to Home" icon="back"/>
            </x-slot>
            <form id="fileForm" action="{{ route('staff.profile.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="text-center mb-5">
                        <img src="{{ uploaded_file($staff->image) }}" class="img-fluid" id="show_image" />
                    </div>
                    <div class="row">
                        <x-form-group name="name" placeholder="Enter Name..." :value="$staff->name" column="col-md-6" />

                        <x-form-group name="email" type="email" placeholder="Enter email..." :value="$staff->email" column="col-md-6" />
                        <x-form-group name="phone" placeholder="Enter phone..." :value="$staff->phone" column="col-md-6" :required="false" />
                        <x-form-group name="image" type="file" column="col-md-6" accept="image/*" data-show-image="show_image" :required="false" />
                        <x-form-group name="address" isType="textarea" :required="false" column="col-md-6" rows="2" placeholder="Enter address...">{{$staff->address ?? ''}}</x-form-group>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Module</th>
                                    <th>Can Create</th>
                                    <th>Can Show</th>
                                    <th>Can Edit</th>
                                    <th>Can Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($modules as $key => $module)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>{{ $module->name }}</td>
                                        @for ($i = 0; $i <= 3; $i++)
                                            <td>
                                                <input type="checkbox" class="form-check-input" value="{{ $module->permissions[$i]->id }}" @foreach($staff->role->permissions as $permission) {{$module->permissions[$i]->id == $permission->id ? 'checked' : ''}} @endforeach @disabled(true) />
                                            </td>
                                        @endfor
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center primary-text">Data not available!</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer text-end p-4">
                    <x-submit-button text="Update" />
                </div>
            </form>

        </x-page-card>
    </div>

@endsection
