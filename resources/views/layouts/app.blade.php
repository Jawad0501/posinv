<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ uploaded_file(setting('favicon')) }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    @include('layouts.partials.stylesheet')

</head>

<body>

    <div class="preloader" id="preloader">
        <div class="lds-grid">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>


    <div class="wrapper sidebar-hovered toggled">

        <!-- Sidebar Start -->
        @if (!is_kitchen_panel())
            @include('layouts.partials.aside')
        @endif
        <!-- Sidebar End -->

        <!-- Header Start -->
        @include('layouts.partials.header')
        <!-- Header End -->

        <!-- Main Content Start -->

        <div class="page-wrapper" @if (is_kitchen_panel()) style="margin-left: 0;" @endif>
            <div class="page-content">
                <div class="row">

                    @yield('content')

                    <input type="hidden" name="dark_mode_logo" value="{{ uploaded_file(setting('dark_logo')) }}">
                    <input type="hidden" name="light_mode_logo" value="{{ uploaded_file(setting('light_logo')) }}">
                    <input type="hidden" name="currency_position" value="{{ setting('currency_position') }}">
                    <input type="hidden" name="currency_symbol" value="{{ setting('currency_symbol') }}">
                    <input type="hidden" name="format_date" value="{{ setting('format_date') }}">

                </div>
            </div>
        </div>

        <div class="overlay toggle-icon"></div>
    </div>

    @include('layouts.partials.script')

</body>

</html>
