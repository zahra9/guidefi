@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title font-weight-bold">Clients List</h4>
            <h6 class="card-title">No. of Clients not login yet:{{$noOfNonLogin}}</h6>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date Of Birth</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Race</th>
                                    <th>Submission Date</th>
                                    <!--                                    <th>Zip Code</th>-->
                                    <th>Asset Level</th>
                                    <th>Income Level</th>

                                </tr>
                            </thead>
                            <tbody>

                                @if($clients->count())
                                @foreach($clients as $client)
                                <tr class="text-black">
                                    {{-- <td><a href="{{ url('/client-details', $client->id) }}">{{ $loop->iteration }}</a></td>--}}
                                    {{-- <td><a href="{{ url('/client-details', $client->id) }}">{{ $client->name}}</a></td>--}}
                                    {{-- <td><a href="{{ url('/client-details', $client->id) }}">{{ $client->email }}</a></td>--}}
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ $loop->iteration }}</a></td>
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->person->first_name)?$client->person->first_name:'-- -- --'}}</a></td>
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->person->last_name)?$client->person->last_name:'-- -- --'}}</a></td>
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->person->email)?$client->person->email: '-- -- --'}}</a></td>
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->person->phone)?$client->person->phone:'-- -- --' }}</a></td>

                                    <td>
                                        <a href="{{ url('/client-detail', $client->id) }}">
                                            {{!is_null($client->person->birth_date) ? \Carbon\Carbon::parse($client->person->birth_date)->format('M-d-Y'):'-- -- --'}}
                                        </a>
                                    </td>
                                    <td><a href="{{ url('/advisor-details', $client->id) }}"></a>{{ !is_null($client->person->gender)?$client->person->gender:'-- -- --' }}</td>
                                    <td><a href="{{ url('/advisor-details', $client->id) }}"></a>{{ !is_null($client->person->age)?$client->person->age:'-- -- --' }}</td>
                                    <td><a href="{{ url('/advisor-details', $client->id) }}"></a>{{ !is_null($client->person->race)?$client->person->race:'-- -- --' }}</td>
                                    <td>{{ !is_null($client->person) ? \Carbon\Carbon::parse($client->person->created_at)->format('M-d-Y'): '-- -- --'}}</td>

                                <!--                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->person->zipcode)?$client->person->zipcode:'-- -- --' }}</a></td>-->
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->clientRequest)?$client->clientRequest->assets:'-- -- --' }}</a></td>
                                    <td><a href="{{ url('/client-detail', $client->id) }}">{{ !is_null($client->clientRequest)?$client->clientRequest->income_level:'-- -- --' }}</a></td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->

@endsection