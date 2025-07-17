@can('campus-access')
    @extends('layouts.Campus.app')

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
                                                </td>
                                            </tr>



                                        </table>
                                    </div>




                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="col-4">
                                                    Building Floor area <br> (square meters)
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

                                        </table>
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <h4>Related document/s uploaded</h4>
                                    <hr>
                                    <ul>
                                        <li> Building Cerfificate Issued by the University</li>
                                    </ul>



                                    <div class="ml-4">
                                        <span class="text-sm">File:</span>
                                            @if ($bldgrec[0]->bldgCSUcertfilename == "" || $bldgrec[0]->bldgCSUcertfilename == NULL)
                                            <span class="badge rounded-pill bg-danger">No file</span>
                                            @else
                                            <br> {{ $bldgrec[0]->bldgCSUcertfilename }}
                                            @endif

                                    </div>
                                    <div class="mt-4 alert alert-info text-sm p-1">To be provided by the university</div>




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

                                                @if ($bldgrec[0]->bldgLGUcertfilename == "" || $bldgrec[0]->bldgLGUcertfilename == NULL)
                                                <form id="uploadbldgfrm" action="{{route('campus.LGUbldgpermitUploadfile',$bldgrec[0]->id)}}" method="post" enctype="multipart/form-data" class="mt-4">

                                                    @csrf
                                                    @method('GET')
                                                    <label for="">Upload building permit issued by the LGU</label>
                                                    <input id="fileInput" name="LGUcert" type="file"  class="text-sm mb-2" id="" required>
                                                    <input type="submit" value="Upload" id="btnupload1" class="btn btn-sm btn-primary" style="display:none;">

                                                </form>
                                                @else @endif


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

                                                            @if ($bldgrec[0]->bldgFIREcertfilename == "" || $bldgrec[0]->bldgFIREcertfilename == NULL)
                                                            <form id="uploadbldgfrm" action="{{route('campus.FireldgpermitUploadfile',$bldgrec[0]->id)}}" method="post" enctype="multipart/form-data"  class="mt-4">

                                                                @csrf
                                                                @method('GET')
                                                                <label for="">Upload fire safety permit issued by the BFP</label>
                                                                <input id="fileInput" name="BFPcert" type="file"  class="text-sm mb-2" id="" required>
                                                                <input type="submit" value="Upload" id="btnupload1" class="btn btn-sm btn-primary" style="display:none;">

                                                            </form>
                                                            @else @endif




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

                                                @if ($bldgrec[0]->bldgOCCUPANCYcertfilename == "" || $bldgrec[0]->bldgOCCUPANCYcertfilename == NULL)
                                                <form id="uploadbldgfrm" action="{{route('campus.OccupancyUploadfile',$bldgrec[0]->id)}}" method="post" enctype="multipart/form-data"  class="mt-4">

                                                    @csrf
                                                    @method('GET')
                                                    <label for="">Upload certificate of occupancy</label>
                                                    <input id="fileInput" name="occupancy" type="file"  class="text-sm mb-2" id="" required>
                                                    <input type="submit" value="Upload" id="btnupload1" class="btn btn-sm btn-primary" style="display:none;">

                                                </form>
                                                @else @endif




                                    <hr>


                                    {{-- instruction dito  --}}
                                    @if ($bldgrec[0]->bldgOCCUPANCYcertfilename == "" || $bldgrec[0]->bldgOCCUPANCYcertfilename == NULL)
                                    <div id="alert1" class="alert alert-sm alert-success text-center text-sm mt-2">
                                        Upload your building certificates, just click
                                        <input id="fileInput" name="LGUcert" type="file"  class="text-sm mb-2" id="" required>
                                    </div>
                                    @else

                                    @endif



                                </div>
                            </div>











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





            $(document).ready(function(){

                setTimeout(function() {
                $("#alert1").slideUp();
                }, 6000);

            });

        </script>






        @endsection
@endcan
