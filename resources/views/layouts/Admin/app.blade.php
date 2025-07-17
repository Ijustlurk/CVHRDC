<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CSU BIMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


        <!-- bootstrap v5.3.2 -->
        <link rel="stylesheet" href="{{asset('build/bootstrap/bootstrap.v5.3.2.min.css')}}">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script src="{{ asset('build/jquery/jquery-v3.7.1.js') }}"></script>
        <style>
            ul {
              list-style-type: square; /* Change bullet style to square */
            }



                #map { height: 600px; } /* for the map */

        </style>

{{-- leafletJS for map shit --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
crossorigin=""/>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>


    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <!-- renders here the navigation -->
            @include('layouts.Admin.navigation')

            {{-- <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif --}}

            <!-- Page Content -->
            <main>
                <!-- starts to render the blade content -->
                @yield('content')
            </main>
        </div>




        <div class="container pt-4" style="font-size: 10px;">
            <div class="row">
                <div class="col-sm-4">
                            <h4>Vision</h4>
                            <p class="font-justify ">CSU is a University with global stature in the arts, culture, agriculture and fisheries, the sciences as well as technological and professional fields</p>
                </div>

                <div class="col-sm-5">
                        <h4>Mission</h4>
                        <p class="font-justify ">
                            Cagayan State University shall produce globally competent graduates through excellent instruction, innovative and creative research, responsive public service and productive industry and community engagement.
                        </p>
                </div>


                <div class="col-sm-3">
                    <h4>Core Values</h4>
                    <ul>
                        <li>Competence</li>
                        <li>Social Responsibility</li>
                        <li>Unifying Presence</li>
                    </ul>

                </div>
            </div>




                </div>
            </div>
        </div>



    </body>
</html>



<script>

    function editbldg()
    {
        document.getElementById('bid').value;
    }



</script>
