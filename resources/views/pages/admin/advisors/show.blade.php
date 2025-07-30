@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />
<Style>
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: solid black 1px;
        outline: 0;
        background: none !important;
        height: 40px !important;
        overflow-y: scroll !important;
    }

    .select2-container--default .select2-selection--multiple {
        background: none !important;
        height: 40px !important;
        border: 1px solid #bce0ea !important;
    }

    .dropdown-menu-right li {
        padding: 0 5px;
        color: #fff;
        font-size: 13px;
    }

    .dropdown-menu-right li a {
        color: #fff;
    }

    .dropdown-menu-right li a i {
        margin-right: 5px;
    }

    td.details-control {
        background: url('../resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url('../resources/details_close.png') no-repeat center center;
    }
</Style>
@endsection
@section('content')

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title ml-2"><strong>Client's Personal Details</strong> </h4>
            <div class="row mt-5">
                @if(!is_null($clientDetail->person))

                @if(!is_null($clientDetail->person->image_url))
                <div class="col-md-12" style="text-align: center;">
                    <img src="{{$clientDetail->person->image_url}}" width="150px" height="150px" style="border-radius: 50%;" alt="">
                </div>
                @else
                <div class="col-md-12" style="text-align: center;">
                    <img src="{{asset('images/default-avatar.png')}}" width="150px" height="150px" style="border-radius: 50%;" alt="">
                </div>
                @endif
                <div class="col-md-12">
                </div>
                <div class="col-md-6 mt-5 ">
                    <div class="col-12 ">
                        <div class="text-black">
                       
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>First Name:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->first_name:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Last Name:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->last_name:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Email:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->email:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Phone No:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->phone:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Date Of Birth:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        {{!is_null($clientDetail->person)?
                                   \Carbon\Carbon::parse($clientDetail->person->birth_date)->format('M-d-Y'):'-- -- --'}}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Age:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->age:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 pl-5">
                        <div class="row ml-3 advisor-list">
                            <div class="col-md-6 advisor-detail">
                                <div class="col-md-6">
                                    <p>Gender:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->gender:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="col-md-6 mt-5">


                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Religion:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->religion:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>State:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->state:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Zip Code:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->zipcode:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Experience level:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->experience_level:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Academic Records:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->academic_records:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Website:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        @if(!is_null($clientDetail->person->website))
                                        <a href="{{url($clientDetail->person->website)}}" target="_blank">{{$clientDetail->person->website}}</a>
                                        @endif
                                    </p>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Client Type:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        {{!is_null($clientDetail->clientRequest->client_type)?$clientDetail->clientRequest->client_type:"--- --- ---"}}

                                    </p>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml-3 advisor-list">
                                <div class="col-md-6 advisor-detail">
                                    <p>Driving License:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->driving_licence:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-12 pl-5">
                        <div class="text-black">
                            <div class="row ml">
                                <div class="col-md-6">
                                    <p>BioGraphy:</p>
                                </div>
                                <div class="col-md-6">
                                    <p>{{!is_null($clientDetail->person)?$clientDetail->person->bio:'-- -- --'}}</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            @else
            <h4 class="text-black">
                No Detail Added Yet!!
            </h4>
@endif





        </div>
    </div>
</div>

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="mt-5 card-title pl-3"><strong>Client's Payment Logs</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>

                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Period Start</th>
                                    <th>Period End</th>
                                    <th>Payment Status</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr class="text-black">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ !is_null($payment->inv_number)?$payment->inv_number:'-- -- --' }}@if(!is_null($payment->invoice_pdf))
                                        <a href="{{url($payment->invoice_pdf)}}">
                                            <i class="ti-download" aria-hidden="true"> </i>
                                        </a>
                                        @endif</td>
                                    <td>{{!is_null($payment->period_start)?
    \Carbon\Carbon::parse($payment->period_start)->format('M-d-Y'):'-- -- --'}}
                                    </td>
                                    <td>
                                        {{!is_null($payment->period_end)?
               \Carbon\Carbon::parse($payment->period_end)->format('M-d-Y'):'-- -- --'}}
                                    </td>
                                    <td>{{ $payment->status}}</td>
                                    <td>{{ Config::get('constants.CURRENCY_SIGN').number_format(!is_null($payment->amount_paid) ? $payment->amount_paid : '0', 2, ',', '.')}}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="pl-3 card-title mt-5"><strong>Client's Call Logs</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Type</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($clientDetail->clientCallLogs->count())
                                @foreach($clientDetail->clientCallLogs as $callLog)
                                <tr class="text-black">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $callLog->client->name}}</td>
                                    <td>{{ $callLog->advisor['name'] }}</td>
                                    <td>{{ $callLog->type }}</td>
                                    <td>
                                        {{!is_null($callLog->start_time)?
                                     \Carbon\Carbon::parse($callLog->start_time)->format('M-d-Y'):'-- -- --'}}
                                    </td>
                                    <td>
                                        {{!is_null($callLog->end_time)?
                                         \Carbon\Carbon::parse($callLog->end_time)->format('M-d-Y'):'-- -- --'}}
                                    </td>

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

<div class="content-wrapper mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-title">
                        <h5 class="pl-3 card-title mt-5"><strong>Client's Advisor Deals</strong> </h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive mt-4">
                        <table id="example" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Offer</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Commision Amount <br>Percentage</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Appointment No.</th>
                                    <th>Appointment Day</th>
                                    <th>Appointment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($deals->count())
                                @foreach($deals as $deal)
                                    <tr class="text-black">
                                        <td>{{ !is_null($deal->offer)?$loop->iteration:'' }}</td>
                                        <td>{{ !is_null($deal->client)?$deal->client->name:''}}</td>
                                        <td>{{ !is_null($deal->advisor)?$deal->advisor->name:'' }}</td>
                                        <td>{{ !is_null($deal->offer)?$deal->offer->offer_title:'' }}</td>
                                        <td>{{ $deal->description }}</td>
                                        <td>
                                            {{prettyPrice(!is_null($deal->deal_amout)?$deal->deal_amout:0)}}
                                        </td>
                                        <td>
                                            {{prettyPrice(!is_null($deal-> commission_amount)?$deal-> commission_amount:0)}}
                                            <br>
                                            {{prettyPricePercent(!is_null($deal-> commission_percentage)?$deal-> commission_percentage:0)}}
                                        </td>
                                        <td>
                                            {{!is_null($deal->deal_start_date)?
                                                \Carbon\Carbon::parse($deal->deal_start_date)->format('M-d-Y'):'-- -- --'}}
                                        </td>
                                        <td>
                                            {{!is_null($deal->deal_end_date)?
                                                   \Carbon\Carbon::parse($deal->deal_end_date)->format('M-d-Y'):'-- -- --'}}
                                        </td>
                                        <td>{{ !is_null($deal->status)?($deal->status == 1)?'Approved':'Ended':'' }}</td>
                                        <td>{{ $deal->appointment->appointment_number }}</td>
                                        <td>{{ $deal->appointment->appointment_day }}</td>
                                        <td>{{ $deal->appointment->appointment_status }}</td>

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


@section('scripts')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
@endsection