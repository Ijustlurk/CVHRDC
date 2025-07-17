@can('admin-access')
    @extends('layouts.Admin.app')

        @section('content')




asdasdasdasdas


                            <div class="row">
                                <div class="col-sm-7">



            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">


                                <table class="table">
                                    <thead>
                                        <th>Name of Campus</th>
                                        <th>Total Land Area</th>
                                        <th>Total <br>building</th>
                                        <th>Total building <br>floor area
                                        <br> (square meters)
                                        </th>
                                        <th>Total <br>laboratories</th>

                                    </thead>
                                    @foreach ($allcampuses as $campus)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.dsplyselcampusmap', $campus->campus) }}">
                                                    {{ $campus->campus }}
                                                </a>


                                            </td>
                                            <td></td>
                                            <td>
                                                {{ $campus->total }}
                                            </td>
                                            <td>{{ $campus->totalfloorareas }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                </div>



                                </div>
                            </div>





        @endsection
@endcan
