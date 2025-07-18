<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <meta http-equiv="refresh" content="0; url=/login"> --}}

        <title>{{ config('app.name', 'CVHRDC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    </head>
    <body class="antialiased">

        <div class="container">



    <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
        <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
            </li>




            @if (Route::has('login'))

                @auth
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                </li>
                @else

                <li class="nav-item">

                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                </li>
                <li class="nav-item">

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    @endif
                </li>
                @endauth
            @endif



            </ul>
        </div>
    </nav>




    <div class="mt-4 p-5 bg-info text-white rounded">
        <h1>Sample Project</h1>
        <p>Using Laravel</p>
    </div>



        </div>






    </body>
</html>
