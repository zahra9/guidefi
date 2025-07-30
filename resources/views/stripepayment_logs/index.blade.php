@extends('layouts.app')

@section('linked_css')

@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Payment Logs</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="col-md-3 user-type-section">
                            <div>
                            <label class="">User Type</label>
                            </div>
                            <div class="payment-sec">
                            <select class="form-control" id="dropdown1">
                                <option value=""></option>
                                @if(!is_null($allRoles))
                                @foreach($allRoles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            </div>
                        </div>
                        <div class="table-responsive">

                            <table id="payment-logs" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Period Start</th>
                                    <th>Period End</th>
                                    <th>Invoice</th>
                                    <th>Role</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if($paymentLogs->count())
                                    @foreach($paymentLogs as $paymentLog)
                                        <tr class="text-black">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ !is_null($paymentLog->customer_email)?$paymentLog->customer_email:'' }}</td>
                                            <td>{{ !is_null($paymentLog->number)?$paymentLog->number:'' }}</td>
                                            <td>{{ !is_null($paymentLog->plan)?$paymentLog->plan:'' }}</td>
                                            <td>
                                                {{ Config::get('constants.CURRENCY_SIGN').number_format(!is_null($paymentLog->amount) ? $paymentLog->amount : '0', 2, ',', '.')}}

                                            </td>
                                            <td>{{ !is_null($paymentLog->period_start)?\Carbon\Carbon::parse($paymentLog->period_start)->format('M-d-Y'):'' }}</td>
                                            <td>{{ !is_null($paymentLog->period_end)?\Carbon\Carbon::parse($paymentLog->period_end)->format('M-d-Y'):'' }}</td>
                                            <td>
                                                @if(!is_null($paymentLog->invoice_pdf))
                                                    <a href="{{url($paymentLog->invoice_pdf)}}">
                                                        <i class="ti-download" aria-hidden="true"> </i>
                                                    </a>
                                                @else
                                                    -- -- --
                                                @endif
                                            </td>
                                            <td>{{ $paymentLog->role }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr class="head-color">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

@endsection

