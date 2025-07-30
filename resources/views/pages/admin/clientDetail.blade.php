@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">clients Details List</h4>
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
                                </tr>
                                </thead>
                                <tbody>
                                    @if($clients->count())
                                    @foreach($clients as $client)
                                    <tr class="text-black">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $client->client->person->first_name}}</td>
                                        <td>{{ $client->client->person->last_name}}</td>
                                        <td>{{ $client->client->email }}</td>
                                        <td>{{ $client->client->person->Phone}}</td>
                                        
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
