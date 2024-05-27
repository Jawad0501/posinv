<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @routes

    @vite(['resources/css/backend.css','resources/sass/backend/app.scss'])
</head>

<body>

    <nav class="navbar auth-navbar py-4">
        <div class="container">
            <a href="/" class="navbar-brand logo-details">
                <img src="{{ uploaded_file(setting('dark_logo')) }}" class="img-fluid" alt="Logo" width="100px" />
            </a>

            <div class="header-icon theme-mode ms-md-2" id="switchTheme" title="Switch Theme">
                <img src="{{ asset('build/assets/backend/images/icons/moon.svg') }}" alt="Moon" />
            </div>
        </div>
    </nav>

    <section class="section auth-section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-md-7 col-lg-5 col-xl-4 mx-auto">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    <input type="hidden" name="dark_mode_logo" value="{{ uploaded_file(setting('dark_logo')) }}">
    <input type="hidden" name="light_mode_logo" value="{{ uploaded_file(setting('light_logo')) }}">

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{{ asset('build/assets/backend') }}/plugins/toastr/toastr.min.js" type="text/javascript"></script>

    @vite('resources/js/backend/app.js')
</body>

</html>
