@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                            <h1>Rooms</h1>



                        <!-- Button trigger modal -->
                        <button style="margin-top:20px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Add New Room +
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Room Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <form action="{{route('admin.rooms.store')}}" method="POST">
                                @csrf
                                @method('POST')

                                <input type="text" name="" hidden>

                                <label for="roomarea">Room Area</label>
                                <input type="text" name="roomarea" class="form-control">


                                <label for="roomacapacity">Room Capacity</label>
                                <input type="text" name="roomcapacity" class="form-control">

                                <label for="bldgid">Building ID</label>
                                <input type="text" name="bldgid" id="" class="form-control">

                                <input type="submit" value="Save" class="btn btn-success" style="margin-top:5px;">
                            </form>
                            </div>
                            </div>
                        </div>
                        </div>

                            <table class="table table-striped" style="margin-top: 20px;">
                            <th>Room Area</th>
                            <th>Room Capacity</th>

                            @foreach($roomsrec as $room)
                                <tr>
                                    <td>{{$room->roomarea}}</td>
                                    <td>{{$room->roomcapacity}}</td>
                                <td>
                                    <input type="hidden" name="room_id" value="{{$room->id}}">
                                    <button type="button" class="btn btn-success" onclick="editroom({{$room}})" class="link" data-bs-toggle="modal" data-bs-target="#exampleModal22">edit</button>

                                </tr>
                            @endforeach
                        </table>

                           {{$roomsrec->links()}}



<!-- Edit Modal -->
<div class="modal fade" id="exampleModal22" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Room Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            @csrf
                            @method('PUT')

                        <div class="modal-body">
                            <!-- Your form fields will be here -->
                        </div>
                        </form>
                    </div>
                </div>
            </div>

<script>
    function editroom(room) {
        // Populate modal fields with room data
        document.getElementById('exampleModal22').querySelector('.modal-body').innerHTML = `

                <div class="mb-3">
                    <label for="roomarea" class="form-label">Room Capacity</label>
                    <input type="hidden" class="form-control" name="id" value="${room.id}">
                    <input type="text" class="form-control" name="roomcapacity" value="${room.roomcapacity}">
                </div>
                <div class="mb-3">
                    <label for="bldgstructure" class="form-label">Building ID</label>
                    <input type="text" class="form-control" name="bldgid" value="${room.bldgid}">
                </div>
                <!-- Add more fields as needed -->
                <button type="submit" class="btn btn-primary">Save changes</button>

        `;
    }
</script>
















                        </div>
                    </div>
                </div>
            </div>
        @endsection
@endcan
