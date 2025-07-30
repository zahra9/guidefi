@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Discovery Call Logs</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Type</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if($callLogs->count())
                                    @foreach($callLogs as $callLog)
                                    <tr class="text-black">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $callLog->client->name}}</td>
                                        <td>{{ $callLog->advisor['name'] }}</td>
                                        <td>{{ $callLog->type }}</td>
                                        <td>{{ $callLog->start_time }}</td>
                                        <td>{{ $callLog->end_time }}</td>
                                        <td>{{ \Carbon\Carbon::parse($callLog->created_at)->format('M-d-Y') }}</td>

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
