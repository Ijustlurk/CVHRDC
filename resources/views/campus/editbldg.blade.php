@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                           
                            
                            <h1>Building</h1>

                        

                    <!-- Edit Modal -->
                     <!-- Modal -->
                     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Building Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form action="{{route('admin.bldg.update',$id)}}" method="POST">
                            @csrf
                            @method('POST')

                            <input type="text" name="" hidden>

                            <label for="bldgname">Building Name</label>
                            <input type="text" name="bldgname" value="{{$editbldg->bldgname}}" class="form-control" >

                            <label for="bldgstructure">Building Structure</label>
                            <input type="text" name="bldgstructure" class="form-control" >

                            <label for="bldgtype">Building Type</label>
                            <input type="text" name="bldgtype" class="form-control" >

                            <label for="bldgarea">Building Area</label>
                            <input type="text" name="bldgarea" class="form-control" >

                            <label for="lotarea">Lot Area</label>
                            <input type="text" name="lotarea" class="form-control" >

                            <label for="bldglocation">Building Location</label>
                            <input type="text" name="bldglocation" class="form-control" >

                            <label for="bldgincharge">Building In-Charge</label>
                            <input type="text" name="bldgincharge" class="form-control" >

                            <label for="datecompleted">Date of Completion</label>
                            <input type="date" name="datecompleted" id="" class="form-control" >

                            <input type="submit" value="Save" class="btn btn-success" style="margin-top:5px;">
                        </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div>






                        <table class="table table-striped" style="margin-top:20px;">
                            <tr>
                            <th>Building Name</th>
                            <th>Structure</th>
                            <th>Type</th>
                            <th>Building Area</th>
                            <th>Lot Area</th>
                            <th>Building Location</th>
                            <th>Building In-Charge</th>
                            <th>Date of Establishment</th>
                            </tr>

                            @foreach($bldgrec as $bldg)
                                <tr>
                                    <td>{{$bldg->bldgname}}</td>
                                    <td>{{$bldg->bldgstructure}}</td>
                                    <td>{{$bldg->bldgtype}}</td>
                                    <td>{{$bldg->bldgarea}}sqm</td>
                                    <td>{{$bldg->lotarea}}sqm</td>
                                    <td>{{$bldg->bldglocation}}</td>
                                    <td>{{$bldg->bldgincharge}}</td>
                                    <td>{{$bldg->datecompleted}}</td>

                                    <td><a href="{{route('admin.bldg.edit',$bldg->id)}}">Edit</a></td>
                                </tr>
                            @endforeach
                        </table>   

                           {{$bldgrec->links()}}
                        
                        </div>
                    </div>
                </div>
            </div>
        @endsection
@endcan