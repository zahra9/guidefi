@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">

@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title font-weight-bold">Sign Up Requests</h4>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Financial Professional</th>
                                    <th>Interested In</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @if($requests->count())
                                    @foreach($requests as $request)
                                    <tr class="text-black">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->name}}</td>
                                        <td>{{ $request->email }}</td>
                                        <td>{{ $request->phone }}</td>
                                        <td>{{ $request->financial_professional }}</td>
                                        <td>{{ $request->interested_in }}</td>
                                        <td>{{ !is_null($request->created_at)?\Carbon\Carbon::parse($request->created_at)->format('M-d-Y'):'' }}</td>


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


 @endsection
@section('scripts')

@endsection
