<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@500&family=Nunito:wght@400;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>@yield('title') - {{ config('app.name') }}</title>

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #fff;
            color: #000;
            font-family: 'Nunito', sans-serif;
        }
        .logo {
            width: 100px;
        }
        .content {
            padding: 20px;
            max-width: 800px;
            margin: auto
        }
        .logo-area {
            text-align: center;
        }
        /* .logo-area img {
            width: 50px;
        } */
        .details p {
            margin: 2px 0px;
        }

        .social {
            display: flex;
            align-items: center;
        }
        .social a {
            color: #000;
        }
        .social a:not(:first-child) {
            margin-left: 8px;
        }

    </style>
</head>
<body>
    <main class="content">

        <div class="logo-area">
            <img src="{{ uploaded_file(setting('light_logo')) }}" alt="Logo" class="logo" />
        </div>

        @yield('content')

        <div>
            <p>
                Best Regards,
                <br/>
                <strong>{{ config('app.name') }}</strong>
            </p>

            <div class="social">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

    </main>
</body>
</html>
