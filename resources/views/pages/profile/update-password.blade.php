@extends('layouts.app')

@section('title', 'Update Password')

@section('content')

    <div class="col-12 col-md-6 offset-md-3 my-5">

        <x-page-card :title="'Update Password'" route="staff.profile.index" btnTitle="Back to Profile">

            <form id="form" action="{{ route('staff.password.update') }}" method="post" data-redirect="{{ route('staff.login') }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <x-form-group
                        name="current_password"
                        type="password"
                        placeholder="*******"
                    />

                    <x-form-group
                        name="password"
                        type="password"
                        placeholder="*******"
                    />


                    <x-form-group
                        name="Confirm Password"
                        for="password_confirmation"
                        type="password"
                        placeholder="*******"
                    />

                </div>
                <div class="card-footer text-end p-4">
                    <x-submit-button text="Update" />
                </div>
            </form>

        </x-page-card>
    </div>

@endsection
