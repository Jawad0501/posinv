<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ uploaded_file(setting('favicon')) }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Home' }} - {{ config('app.name') }}</title>

    <livewire:styles />

    @vite(['resources/css/frontend.css', 'resources/js/frontend/app.js'])

</head>

<body class="font-nunito text-base selection:bg-primary-500 selection:text-white" x-data="{ navIsOpen: false, isModal: 'close' }" :class="navIsOpen || isModal == 'open' ? 'overflow-hidden':''">
    <div class="relative">

        @if (in_array(request()->route()->getName(), ['login','password.request','register']))
            <section class="flex flex-col md:flex-row h-screen items-center">

                <div class="bg-indigo-600 hidden lg:block w-full md:w-1/2 h-screen">
                    <img src="{{ Vite::image('login-bg.jpg') }}" alt class="w-full h-full object-cover">
                </div>

                {{ $slot }}

            </section>
        @else

            @if (!request()->has('is_api'))
                <x-frontend.navbar />
                <x-frontend.aside />
            @endif

            <main class="pb-16 lg:pb-0">
                {{ $slot }}
            </main>

            @if (!request()->has('is_api'))
                <x-frontend.mobile-navbar />

                <x-frontend.footer />

                <livewire:frontend.menu-details />
            @endif
        @endif
    </div>

    @livewireScriptConfig

    @if ($alert = session()->get('alert'))
        <script>
            window.alertNotyf = @json($alert)
        </script>
    @endif
</body>

</html>
