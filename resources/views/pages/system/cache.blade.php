@extends('layouts.app')

@section('title', 'Clear System Cache')

@section('content')
    <div class="col-lg-8 mx-auto">
        <x-page-card title="Clear System Cache">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Compiled views will be cleared')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Application cache will be cleared')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Route cache will be cleared')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Configuration cache will be cleared')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Compiled services and packages files will be removed')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span><i class="fa-solid fa-check-double text-primary-500"></i> @lang('Caches will be cleared')</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <form id="form" action="{{ route('system.cache') }}" method="post" data-redirect="{{ route('system.cache') }}">
                                        @csrf
                                        <x-submit-button text="Click to Clear" class="w-100" />
                                    </form>
                                </th>
                            </tr>
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </x-page-card>
    </div>
@endsection
