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





                        <table id="campusTable" class="table table-bordered" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    <th>Campus</th>
                                    <th>Lists of buildings</th>
                                    <th>Address</th>
                                    <th>Option/s</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table content will be inserted here -->
                            </tbody>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

            // slide up effects here
            setTimeout(function() {
            $("#alert1").slideUp();
            }, 6000);







    });




    // this will do the autodisplay of the records in the table
        $(document).ready(function(){

            // Call updateTable function initially to populate the table
            updateTable(); // Populate the table on page load

            // Set interval to update the table content periodically
            setInterval(function() {
                updateTable();
            }, 5000); // Update every 5 seconds (adjust interval as needed)
        });


        // Make AJAX request to fetch campus data
        function updateTable() {
                $.ajax({
                    url: '{{ route("admin.admin.building.listbldg") }}',
                    type: 'GET',
                    success: function(response) {
                        // Clear existing table rows
                        $('#campusTable tbody').empty();

                        response.campuses.forEach(function(campus) {
                            campus.buildings.forEach(function(building) {

                                var row = '<tr>' +
                                    '<td><a href="{{ route("admin.dsplyselcam", "") }}/' + campus.campus + '">' + campus.campus + '</a></td>' +
                                            '<td>' + building.bldgname + '</td>' +
                                            '<td>' + building.address + '</td>' +
                                            '<td>' +
                                                '<form action="{{ route("admin.ViewBldg", "") }}/' + building.id + '" method="post">' +
                                                            '@csrf' +
                                                            '@method("GET")' +
                                                            '<button type="submit" class="btn btn-sm btn-primary  text-white">view</button>' +
                                                        '</form>' +
                                                    '</td>' +
                                                '</tr>';
                                $('#campusTable tbody').append(row);
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
    // this will do the autodisplay of the records in the table
</script>









        @endsection
@endcan
