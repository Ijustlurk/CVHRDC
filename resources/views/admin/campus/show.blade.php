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

                                <h4>Campus: {{ $buildingsofcampus[0]->campus }}</h4>

                            <table class="table">
                                <thead>
                                    <th></th>
                                    <th>Building</th>
                                    <th>Floor area <br>
                                        (square meters)
                                    </th>
                                    <th>Type</th>
                                    <th>Level/Floors</th>
                                    <th>Department/office
                                        <br>in-charge</th>

                                </thead>
                                @php $c = 0; @endphp
                                @foreach ($buildingsofcampus as $campus)
                                    @php $c = $c+1; @endphp
                                    <tr>
                                        <td>{{ $c }}</td>
                                        <td>{{ $campus->bldgname }}</td>
                                        <td>{{ $campus->bldgfloorarea }}</td>
                                        <td>{{ $campus->bldgtype }}</td>
                                        <td>{{ $campus->bldgnumberoffloors }}</td>
                                        <td>{{ $campus->college_office_assigned }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td class="text-right">Total square meter</td>
                                    <td>{{ $totfloorarea[0]->totalfloorareas }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>



                    </div>
                </div>
            </div>
        </div>



        @endsection
@endcan



