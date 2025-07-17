@php
use Carbon\Carbon;
@endphp
@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')



        <div class="row">
            <div class="col-sm-4">


            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">

                                <h4>Campus: {{ $id }}</h4>

                            <table class="table">
                                <thead>
                                    <th></th>
                                    <th>Building</th>
                                    <th>Floor area <br>
                                        (square meters)
                                    </th>

                                </thead>
                                @php $c = 0; @endphp
                                @foreach ($locs as $campus)
                                    @php $c = $c+1; @endphp
                                    <tr>
                                        <td>{{ $c }}</td>
                                        <td>{{ $campus->bldgname }}</td>
                                        <td>{{ $campus->bldgfloorarea }}</td>

                                    </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td class="text-right">Total square meter</td>
                                    <td>{{ $totfloorarea[0]->totalfloorareas }}</td>

                                </tr>
                            </table>



                    </div>
                </div>
            </div>
        </div>



    </div>
    <div class="col-sm-8">

        <div id="map" class="mt-4"></div>

        <input type="hidden" id="mycoordinates" name="" value="{{$locs}}" id="">


    </div>
</div>



<script>
// dito ung mapings shit

var map = L.map('map').setView([18.352151, 121.649866], 16); // aparri cagayan

var coordinatesJSON = document.getElementById('mycoordinates').value;



var jsonData = JSON.parse(coordinatesJSON);



    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

jsonData.forEach(function(item) {
    // Push coordinates to the array for debugging purposes
    var marker = L.marker([parseFloat(item.lat), parseFloat(item.long)]).addTo(map);

    marker.bindPopup(item.bldgname);
});

</script>
        @endsection
@endcan



