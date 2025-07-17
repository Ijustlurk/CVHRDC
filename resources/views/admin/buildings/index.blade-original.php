@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                            <h1>Building records per campus</h1>
                            <div id="alert1" class="text-sm alert alert-sm alert-success text-center mt-2">
                                Please wait for the campuses to upload building inventory
                            </div>


                        <table class="table table-bordered" style="margin-top:20px;">
                            <tr>
                            <th>Campus</th>
                            <th>Lists of buildings</th>
                            <th>Address</th>
                            {{-- <th>Building Certificate</th> --}}
                            <th>Option/s</th>
                            </tr>

                            

                            @foreach($campuses as $campus)


                            <tr>
                                <td>{{$campus->campus}}</td>
                                <td>
                                    @php
                                        $bldgs = DB::table('buildings')
                                        ->select('*')
                                        ->where('campus',$campus->campus)
                                        ->get();
                                        $cntr = 0;
                                    @endphp

                                    <ul>
                                        @foreach ($bldgs as $bldg)
                                        @php $cntr = $cntr+1; @endphp
                                        <li>

                                            <div class="row">
                                                <div class="col-sm">
                                                    <form action="{{ route('admin.ViewBldg',$bldg->id) }}" method="post">
                                                        @csrf
                                                        @method('GET')
                                                        <button class="link text-decoration-underline text-primary">{{$cntr}}. {{ $bldg->bldgname}}</button>
                                                    </form>


                                                </div>
                                            </div>





                                        </li>
                                        @endforeach
                                    </ul>

                                </td>


                                <td>
                                    @php
                                    $campusadd = DB::table('buildings')
                                    ->select('*')
                                    ->where('campus',$campus->campus)
                                    ->limit(1)

                                    ->get();

                                    echo $campusadd[0]->address;
                                @endphp
                                </td>

                                <td>
                                  Print

                                </td>

                            </tr>

                            @endforeach
                        </table>







                </div>
            </div>










            <!-- Edit Modal -->
            <div class="modal fade" id="exampleModal22" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Building</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.bldgupdate1')}}" method="POST">
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

                <div class="mb-3">
                    <label for="bldgname" class="form-label">Building Name</label>
                    <input type="hidden" class="form-control" name="bldgid" value="${bldg.id}">
                    <input type="text" class="form-control" name="bldgname" value="${bldg.bldgname}">
                </div>
                <div class="mb-3">
                    <label for="bldgstructure" class="form-label">Building Structure</label>
                    <input type="text" class="form-control" name="bldgstructure" value="${bldg.bldgstructure}">
                </div>
                <div class="mb-3">
                    <label for="bldgincharge" class="form-label">Building In-charge</label>
                    <input type="text" class="form-control" name="bldgincharge" value="${bldg.bldgincharge}">
                </div>
                <!-- Add more fields as needed -->
                <button type="submit" class="btn btn-primary">Save changes</button>

        `;
    }




    $(document).ready(function(){

    setTimeout(function() {
    $("#alert1").slideUp();
    }, 6000);

    });
</script>









        @endsection
@endcan
