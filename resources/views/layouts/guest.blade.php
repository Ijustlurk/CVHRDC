<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CVHRDC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">
    <!--  -->
    <!-- Add this in your main layout's <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->

    <style>
        .body{
            font-family: 'Nunito';
        }
        .btn-primary {
            color: #fff !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        .btn-primary:hover {
            color: #fff !important;
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        .btn-primary:disabled {
            color: #fff !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            opacity: 0.65;
        }

    </style>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="body text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        {{--
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>
        --}}

        <div class="w-full max-w-4xl mt-6 px-0 py-0 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
        @stack('scripts')
    </div>
</body>

</html>
