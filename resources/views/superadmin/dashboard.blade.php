@can('campus-access')
    @extends('layouts.Campus.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                            <h1>Welcome to CSU-BIMS</h1>


                            @if (Auth::user()->address == "")
                                <div id="alert1" class="text-sm alert alert-sm alert-success text-center mt-2">
                                    Please update your address immediately,
                                    <a href="/profile">click here</a>
                                </div>
                            @else

                            @endif



                        </div>
                    </div>
                </div>
            </div>




            <script>
                $(document).ready(function(){

                    setTimeout(function() {
                    $("#alert1").slideUp();
                    }, 6000);

                });

            </script>
        @endsection
@endcan
