@can('campus-access')
    @extends('layouts.Campus.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                            <h1>Building Records</h1>



                        <!-- Button trigger modal -->
                        <button style="margin-top:20px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add New Building +
                        </button>

                            <div id="alert1" class="text-sm alert alert-sm alert-success text-center mt-2">
                                Start adding building inventory to your account, just click <button  type="button" class="ml-4 btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Add New Building +
                                    </button>
                            </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Building Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                            <form action="{{route('campus.bldg.store')}}" method="POST" class="was-validated" id="addnewbuildingfrm">
                            @csrf
                            @method('POST')




                            <label for="bldgname">Building Name</label>
                            <input type="text" name="bldgname" class="form-control mb-4">

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                            <label for="desc">Description</label>
                            <textarea name="desc" id=""  class="form-control mb-4"></textarea>

                            <label for="datecompleted">Date of Completed</label>
                            <input type="date" name="datecompleted" id="" class="form-control mb-4" >

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>



                                @if (Auth::user()->address == "")
                                <div id="alert1" class="text-sm alert alert-sm alert-success text-center mt-2">
                                    Please update your address immediately,
                                    <a href="/profile">click here</a>
                                </div>
                                @else

                            <input type="submit" id="ongoingaddnewbuilding" value="Save" class="btn btn-success" style="margin-top:5px;">

                            @endif


                        </form>
                            </div>
                            </div>
                        </div>
                        </div>



                        <table class="table table-striped" style="margin-top:20px;">
                            <tr>

                            <th>Bldg name</th>
                            <th>Description</th>
                            <th>Date Completed</th>
                            <th>Option/s</th>
                            </tr>

                            @foreach($bldgrec as $bldg)


                            <tr>

                                <td>{{$bldg->bldgname}}</td>
                                <td>{{$bldg->desc}}</td>
                                <td>{{$bldg->date_completed}}</td>

                                <td>
                                    <input type="hidden" name="bldg_id" value="{{$bldg->id}}">

                                    <a href="" class="link text-white bg-primary p-2 text-decoration-none" onclick="editBldg({{$bldg}})" data-bs-toggle="modal" data-bs-target="#exampleModal22">Edit</a>

                                    <a href="{{route('campus.bldg.show',$bldg->id)}}" class="link text-white bg-primary p-2 text-decoration-none">View</a>


                                </td>
                            </tr>

                            @endforeach
                        </table>

                           {{$bldgrec->links()}}





                </div>
            </div>










            <!-- Edit Modal -->
            <div class="modal fade" id="exampleModal22" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Building Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('campus.bldgupdate1')}}" method="POST" class="was-validated">
                            @csrf
                            @method('POST')

                        <div class="modal-body">
                            <!-- Your form fields will be here -->
                        </div>
                        </form>
                    </div>
                </div>
            </div>




<script>
    function editBldg(bldg) {
        // Populate modal fields with building data
        document.getElementById('exampleModal22').querySelector('.modal-body').innerHTML = `

                <div class="mb-4">
                    <label for="bldgname" class="form-label">Building Name</label>
                    <input type="hidden" class="form-control" name="bldgid" value="${bldg.id}">
                    <input type="text" class="form-control" name="bldgname" value="${bldg.bldgname}">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>


                </div>
                <div class="mb-4">
                    <label for="bldgstructure" class="form-label">Building Structure</label>
                    <textarea name="desc" id=""  class="form-control mb-4">${bldg.desc}</textarea>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="mb-4">
                    <label for="date_completed" class="form-label">Date completed</label>
                    <input type="text" class="form-control" name="date_completed" value="${bldg.date_completed}">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>


                    </div>



                <div class="mb-4">
                    <label for="bldgfloorarea" class="form-label">Building floor area (square meters)</label>
                    <input type="text" class="form-control" name="bldgfloorarea" value="${bldg.bldgfloorarea}">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                </div>


                <div class="mb-4">
                    <label for="bldgtype" class="form-label">Building Type <br>
                        (e.g., residential, commercial, industrial)</label>
                    <input type="text" class="form-control" name="bldgtype" value="${bldg.bldgtype}">

                    <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                </div>


                <div class="mb-4">
                    <label for="bldgnumberoffloors" class="form-label">Number of floors</label>
                    <input type="text" class="form-control" name="bldgnumberoffloors" value="${bldg.bldgnumberoffloors}">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>



                </div>


                <div class="mb-4">
                    <label for="bldgnumberofrooms" class="form-label">Number of rooms</label>
                    <input type="text" class="form-control" name="bldgnumberofrooms" value="${bldg.bldgnumberofrooms}">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                </div>


                <div class="mb-4">
                    <label for="bldgnumberofcrs" class="form-label">Number of comfort rooms</label>
                    <input type="text" class="form-control" name="bldgnumberofcrs" value="${bldg.bldgnumberofcrs}">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <div class="mb-4">
                    <label for="college_office_assigned" class="form-label">College/Office assiged</label>
                    <input type="text" class="form-control" name="college_office_assigned" value="${bldg.college_office_assigned}">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>



                <div class="mb-4">
                    <label for="lat" class="form-label">Latitude</label>
                    <input type="text" class="form-control" name="lat" id="lat" value="${bldg.lat}">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="mb-4">
                    <label for="long" class="form-label">Longtitude</label>
                    <input type="text" class="form-control" name="long" id="long" value="${bldg.long}">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>


                <!-- Add more fields as needed -->
                <button type="submit" class="btn btn-primary">Save changes</button>

        `;
    }




    $('#addnewbuildingfrm').submit(function() {

        //submit to add new building info
        var spinner =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait  to complete... '

        $("#ongoingaddnewbuilding").html(spinner);
        $("#ongoingaddnewbuilding").prop("disabled", true);
    });

$(document).ready(function(){

    setTimeout(function() {
    $("#alert1").slideUp();
    }, 6000);

});



</script>









        @endsection
@endcan
