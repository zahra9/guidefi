@extends('layouts.app')

@section('linked_css')
    <link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Advisors List</h4>
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
                                    <th>Date OF Birth</th>
                                    <th>Zip Code</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if($advisors->count())
                                    @foreach($advisors as $advisor)
                                        <tr class="text-black">
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{ $loop->iteration }}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{  !is_null($advisor->person->first_name)?$advisor->person->first_name:'-- -- --'}}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{  !is_null($advisor->person->last_name)?$advisor->person->last_name:'-- -- --'}}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{  !is_null($advisor->person->email)?$advisor->person->email: '-- -- --'}}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{ !is_null($advisor->person->phone)?$advisor->person->phone:'-- -- --' }}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{!is_null($advisor->person->birth_date)?
    \Carbon\Carbon::parse($advisor->person->birth_date)->format('M-d-Y'):'-- -- --'}}</a></td>
                                            <td><a href="{{ url('/advisor-list-detail', $advisor->id) }}">{{ !is_null($advisor->person->zipcode)?$advisor->person->zipcode:'-- -- --' }}</a></td>
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
