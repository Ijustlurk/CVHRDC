@php
use Carbon\Carbon;
@endphp
@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                            <div class="row">
                                <div class="col-sm-8">


                                    <h1>Building Details</h1>

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="col-4">
                                                    Building Name
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgname}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    Description
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->desc}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    Assigned College/Office
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->college_office_assigned}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Name of Campus
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->campus}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Address
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->address}}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    Date completed
                                                </td>
                                                <td>



                                                    {{$bldgrec[0]->date_completed}}


                                                    @php
                                                    $dateStrings = $bldgrec[0]->date_completed;

                                                    $dates = Carbon::createFromFormat('Y-m-d', $dateStrings);

                                                    $currentDates = Carbon::now();

                                                    $totalYears = $currentDates->diffInYears($dates);

                                                    echo "(".$totalYears." years ago)";
                                                     @endphp
                                                </td>
                                            </tr>



                                        </table>
                                    </div>




                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="col-4">
                                                    Floor area
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgfloorarea}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    Building Type
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgtype}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Number of floors
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgnumberoffloors}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Number of rooms
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgnumberofrooms}}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    Number of comfort rooms
                                                </td>
                                                <td>
                                                    {{$bldgrec[0]->bldgnumberofcrs}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    Created at
                                                </td>
                                                <td>
                                                    @php
                                                        $dateString = $bldgrec[0]->created_at;

                                                        $dateTime = new DateTime($dateString);

                                                        $formattedDate = $dateTime->format('F j-D, Y \a\t g:i A');

                                                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);

                                                        $currentDate = Carbon::now();

                                                        $totalDays = $currentDate->diffInDays($date);

                                                        echo $formattedDate." (".$totalDays." days ago)";
                                                    @endphp

                                                </td>
                                            </tr>



                                        </table>
                                    </div>



                                </div>
                                <div class="col-sm-4">
                                    {{-- dito ung pag upload ng mga certs --}}


                                    <h4>Related document/s uploaded</h4>
                                    <hr>
                                    <ul>
                                        <li> Building Cerfificate from the University</li>
                                    </ul>


                                    <div class="ml-4">
                                        <span class="text-sm">File details:</span>
                                            @if ($bldgrec[0]->bldgCSUcertfilename == "" || $bldgrec[0]->bldgCSUcertfilename == NULL)
                                            <span class="badge rounded-pill bg-danger">No file</span>
                                            @else

                                            <div><span><i>Filename:</i></span>
                                                 <span class="text-decoration-underline"> {{ $bldgrec[0]->bldgCSUcertfilename }}</span>
                                               </div>
                                            <div>
                                                <span><i>Uploaded:</i></span>
                                                <span class="text-decoration-underline">

                                                            @php
                                                            $dateString = $bldgrec[0]->created_at;

                                                            $dateTime = new DateTime($dateString);

                                                            $formattedDate = $dateTime->format('F j-D, Y \a\t g:i A');

                                                            $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);

                                                            $currentDate = Carbon::now();

                                                            $totalDays = $currentDate->diffInDays($date);

                                                            echo $formattedDate." (".$totalDays." days ago)";
                                                        @endphp
                                                </span>
                                            </div>



                                            @endif

                                    </div>


                                    <div class="col-sm">
                                    @if ($bldgrec[0]->bldgCSUcertfilename == "" || $bldgrec[0]->bldgCSUcertfilename == NULL)
                                        <form id="uploadbldgfrm" action="{{route('admin.Uploadfile',$bldgrec[0]->id)}}" method="post" enctype="multipart/form-data" class="mt-4">
                                                @csrf
                                                @method('GET')
                                                <label for="">Upload Building Certificate</label>
                                                <input id="fileInput" name="logo" type="file"  class="text-sm mb-2" id="" required>
                                                <input type="submit" value="Upload" id="btnupload1" class="btn btn-sm btn-primary" style="display:none;">


                                        </form>
                                    @else



                                    @endif



                                    <hr>


                                    <ul>
                                        <li> Building permit issued by the LGU</li>
                                    </ul>
                                    <div class="ml-4">
                                        <span class="text-sm">File:</span>
                                            @if ($bldgrec[0]->bldgLGUcertfilename == "" || $bldgrec[0]->bldgLGUcertfilename == NULL)
                                            <span class="badge rounded-pill bg-danger">No file</span>
                                            @else
                                            <br>
                                            <a href="">{{ $bldgrec[0]->bldgLGUcertfilename }}</a>
                                            @endif
                                    </div>





                                    <hr>


                                    <ul>
                                        <li> Fire safety permit issued by the BFP</li>
                                    </ul>
                                    <div class="ml-4">
                                        <span class="text-sm">File:</span>
                                            @if ($bldgrec[0]->bldgFIREcertfilename == "" || $bldgrec[0]->bldgFIREcertfilename == NULL)
                                            <span class="badge rounded-pill bg-danger">No file</span>
                                            @else
                                            <br>
                                            <a href="">{{ $bldgrec[0]->bldgFIREcertfilename }}</a>
                                            @endif
                                    </div>



                                    <hr>



                                    <ul>
                                        <li> Certificate of Occupancy</li>
                                    </ul>
                                    <div class="ml-4">
                                        <span class="text-sm">File:</span>
                                            @if ($bldgrec[0]->bldgOCCUPANCYcertfilename == "" || $bldgrec[0]->bldgOCCUPANCYcertfilename == NULL)
                                            <span class="badge rounded-pill bg-danger">No file</span>
                                            @else
                                            <br>
                                            <a href="">{{ $bldgrec[0]->bldgOCCUPANCYcertfilename }}</a>
                                            @endif
                                    </div>

                                    </div>
                                </div>

                            </div>





                    </div>
                </div>
            </div>
        </div>




        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div id="map"></div>

                        <input type="text" id="mycoordinates" name="" value="{{$locs}}" id="">

                    </div>
                </div>
            </div>
        </div>










        <script>

            var fileInput = document.getElementById('fileInput');
            var submitButton = document.getElementById('btnupload1');

            fileInput.addEventListener('change', function() {
                submitButton.style.display = 'block';
            });





            $('#uploadbldgfrm').submit(function() {

            //upload file
            var spinner =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait  to complete... '

            $("#btnupload1").html(spinner);
            $("#btnupload1").prop("disabled", true);
            });




            // for the map

            var map = L.map('map').setView([18.352151, 121.649866], 17); // aparri cagayan

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



