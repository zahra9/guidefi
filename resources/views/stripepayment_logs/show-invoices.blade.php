@extends('layouts.dashboard')

@section('template_title')
    Showing Invoices
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Invoices
@endsection
@section('content')

    <div class="row">
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="row" style="height: 40px !important; margin-left: 13px;">
                <form action="/invoices/filter" name="filters" id="filters" method="POST"
                      style="margin-top: 24px !important;">
                    @csrf
                    Filter By
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="all" <?php echo ($filter == 'all') ? 'checked' : '' ?>>All</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="paid"<?php echo ($filter == 'paid') ? 'checked' : '' ?>>Paid</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="open"<?php echo ($filter == 'open') ? 'checked' : '' ?>>Open</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="Refunded"<?php echo ($filter == 'Refunded') ? 'checked' : '' ?>>Refunded</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="void"<?php echo ($filter == 'void') ? 'checked' : '' ?>>Void</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter"
                                                           name="filter"
                                                           value="canceled"<?php echo ($filter == 'canceled') ? 'checked' : '' ?>>Canceled</label>

                    <div class="" style="float: right; margin-left: 306px;">

                        <label class="form-check-label"> <input type="text" placeholder="Customer Email" name="customer_email" class=""
                                                                id="customer_email"
                                                                value={{$filter=='inputfilter' && old('customer_email') != '' ? old('customer_email'): ''}}></label>

                        <label class="form-check-label"> <input type="text" placeholder="From Date" name="from" class=""
                                                                id="fromdatepicker"
                                                                value={{$filter=='inputfilter' && old('from') != '' ? old('from'): ''}}></label>
                        <label class="form-check-label"> <input type="text" placeholder="To Date" name="from" class=""
                                                                id="todatepicker"
                                                                value={{$filter=='inputfilter' && old('to') != '' ? old('to'): ''}}></label>
                        <span class="form-check-label localfilter"
                              style="cursor: pointer; color: blueviolet"> Filter </span>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($invoices)  === 1)
                    {{ sizeof($invoices) }} Invoices total
                @elseif (sizeof($invoices) > 1)
                    {{ sizeof($invoices) }} Invoices Packages
                @else
                    No Invoices Found
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                        <th class="mdl-data-table__cell--non-numeric">Email</th>
                        <th class="mdl-data-table__cell--non-numeric">Invoice No.</th>
                        <th class="mdl-data-table__cell--non-numeric">Plan</th>
                        <th class="mdl-data-table__cell--non-numeric">Amount</th>
                        <th class="mdl-data-table__cell--non-numeric">Billing Period</th>
                        <th class="mdl-data-table__cell--non-numeric">Invoice Date</th>
                        <th class="mdl-data-table__cell--non-numeric">Status</th>
                        <th class="mdl-data-table__cell--non-numeric">View</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoices as $key => $invoice)
                        <tr style="background-color: {{$invoice['status'] == 'Refunded' ? '#ffeed9;': ''}}">
                            <td class="mdl-data-table__cell--non-numeric">{{$key+1}}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{$invoice['customer_email']}} </td>
                            <td class="mdl-data-table__cell--non-numeric">{{$invoice['number']}} </td>
                            <td class="mdl-data-table__cell--non-numeric">{{$invoice['description']}} </td>
                            <td class="mdl-data-table__cell--non-numeric">${{number_format($invoice['status'] == 'Refunded' ? $invoice['refunded_amount'] : $invoice['amount_paid'],2)}} </td>
                            <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($invoice['period_start'])->format('m/d/Y') .' -- '.\Carbon\Carbon::parse($invoice['period_end'])->format('m/d/Y') }} </td>
                            <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($invoice['created'])->format('m/d/Y') }}</td>
                            <td class="mdl-data-table__cell--non-numeric">
                                <span style="color: {{$invoice['status'] == 'paid' ? 'green; font-weight:bold;': 'red; font-weight:bold;'}}">
                                    {{strtoupper($invoice['status'])}}
                                </span>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{$invoice['invoice_pdf']}}"
                                                                             title="View Invoice">View Invoice </a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $('.localfilter').click(function(){
                var filterValue = $(this).val();
                if(filterValue != ''){
                    $("#customer_email").val('');
                    $("#fromdatepicker").val('');
                    $("#todatepicker").val('');
                }
                $('#filters').submit();
            });

            $("#fromdatepicker").on("change", function () {
                //$( "#fromdatepicker" ).datepicker( "option", "dateFormat", 'mm-dd-yy' );
            });
            $("#todatepicker").on("change", function () {
                // $( "#todatepicker" ).datepicker( "option", "dateFormat", 'mm-dd-yy' );
            });
        });
    </script>
    <script>
        $(function () {
            $("#fromdatepicker").datepicker();
        });
        $(function () {
            $("#todatepicker").datepicker();
        });
    </script>
@endsection


