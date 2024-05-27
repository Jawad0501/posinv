
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('build/assets/backend/plugins/summernote/summernote-lite.min.css') }}" type="text/css" rel="stylesheet" />

@stack('css')

@routes

@vite(['resources/css/backend.css','resources/sass/backend/app.scss'])
