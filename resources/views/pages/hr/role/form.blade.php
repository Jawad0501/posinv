@extends('layouts.app')

@section('title', isset($role) ? 'Update Role':'Add New Role')

@section('content')

    <div class="col-12 mx-auto my-5">

        <x-page-card :title="isset($role) ? 'Update Role':'Add New Role'">

            <x-slot:cardButton>
                <x-card-heading-button :url="route('role.index')" title="Back to role" icon="back" />
            </x-slot>

            <form id="form" action="{{isset($role) ? route('role.update', $role->id) : route('role.store')}}" method="post" data-redirect="{{ route('role.index') }}">
                @csrf
                @isset($role)
                    @method('PUT')
                @endisset

                <div class="card-body">

                    <x-form-group name="name" placeholder="Role Name" :value="$role->name ?? ''" />

                    <x-form-group name="description" isType="textarea" :required="false" rows="2" placeholder="Role Description">
                        {{$role->description ?? ''}}
                    </x-form-group>

                    <div class="table-responsive">
                        <table class="table table-flush-spacing">
                            <tbody>
                                <tr>
                                    <td class="text-nowrap fw-semibold">
                                        Administrator Access
                                        <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="selectAll">
                                            <label class="form-check-label" for="selectAll">Select All</label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($modules as $module)
                                    <tr>
                                        <td class="text-nowrap fw-semibold">{{ $module->name }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @foreach ($module->permissions as $permission)
                                                    <div class="form-check form-switch me-3 me-lg-5 w-25">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" role="switch" name="permissions[]" id="permissions_{{ $permission->slug }}" value="{{ $permission->id }}" @checked(isset($role) && in_array($permission->slug, $role->permissions->pluck('slug')->toArray()))>
                                                        <label class="form-check-label" for="permissions_{{ $permission->slug }}">{{ $permission->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer text-end p-4">
                    <x-submit-button :text="isset($role) ? 'Update':'Save'" />
                </div>
            </form>

        </x-page-card>
    </div>

@endsection
