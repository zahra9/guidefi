@extends('layouts.dashboard')

@section('template_title')
  Showing Payable Clients
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Payable Clients
@endsection

@section('breadcrumbs')
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="{{url('/')}}">
            <span itemprop="name">
                {{ trans('titles.app') }}
            </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="1" />
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/clients" disabled>
            <span itemprop="name">
            Payable Clients List
            </span>
        </a>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')

{{--<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">--}}
{{--    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">--}}
{{--        <h2 class="mdl-card__title-text logo-style">--}}
{{--            @if (sizeof($users) === 1)--}}
{{--                {{ sizeof($users) }} Payable Client total--}}
{{--            @elseif (sizeof($users) > 1)--}}
{{--                {{ sizeof($users) }} Total Payable Clients--}}
{{--            @else--}}
{{--                No Payable Clients--}}
{{--            @endif--}}
{{--        </h2>--}}

{{--    </div>--}}
{{--    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">--}}
{{--        <div class="table-responsive material-table">--}}
{{--            <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">--}}
{{--              <thead>--}}
{{--                <tr>--}}
{{--                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>--}}
{{--                    <th class="mdl-data-table__cell--non-numeric">Nick Name</th>--}}
{{--                    <th class="mdl-data-table__cell--non-numeric">Email</th>                   --}}
{{--                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Register Date</th>--}}
{{--                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package</th>--}}
{{--                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package Price</th>--}}

{{--                </tr>--}}
{{--              </thead>--}}
{{--              <tbody>--}}
{{--              @php $i=0 @endphp--}}
{{--              --}}
{{--                    @foreach ($users as $user)--}}
{{--                        <tr>--}}
{{--                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}">{{$i+1}}</a></td>--}}
{{--                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}"><img src={{$user->image_url}} width="24px">&nbsp;{{$user->name}} </a></td>--}}
{{--                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}">{{$user->email}} </a></td>--}}
{{--                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('clients/' . $user->id) }}">{{date('m-d-Y H:m:s', strtotime($user->created_at))}} </a></td>--}}
{{--                           --}}
{{--                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">--}}
{{--                                {{ sizeof($user->subscribedPlans)>0 ? $user->subscribedPlans[0]->title : "" }}--}}
{{--                            </td>--}}
{{--                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">--}}
{{--                                {{ sizeof($user->subscribedPlans)>0 ? '$'.number_format($user->subscribedPlans[0]->price,2) : "" }}--}}
{{--                            </td>--}}


{{--                        </tr>--}}
{{--                        @php $i++ @endphp--}}
{{--                    @endforeach--}}
{{--              </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <div class="mdl-card__menu" style="top: -4px;">--}}

{{--        @include('partials.mdl-highlighter')--}}

{{--        @include('partials.mdl-search')--}}

{{--        <a href="{{ url('/makebilling') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Make Billing of Subscription">--}}
{{--            <i class="material-icons">add</i>--}}
{{--            <span class="sr-only">Make Billing of Subscription</span>--}}
{{--        </a>--}}


{{--    </div>--}}
{{--</div>--}}
<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h2 class="mdl-card__title-text logo-style">
            @if (sizeof($allinvoices) === 1)
                {{ sizeof($allinvoices) }} Invoices total
            @elseif (sizeof($allinvoices) > 1)
                {{ sizeof($allinvoices) }} Total Invoices
            @else
                No Invoice
            @endif
        </h2>

    </div>
    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
        <div class="table-responsive material-table">
            <table id="example" class="display mdl-data-table mdl-js-data-table" cellspacing="0" style="width:100%" >

                <thead>
                <tr>
                    <th></th>
                    <th class="mdl-data-table__cell--non-numeric">Invoice Number</th>
                    <th></th>
                    <th class="mdl-data-table__cell--non-numeric">Invoice Total</th>
                    @if($user->corporateclient->payment_method  == "Credit Card")
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Card No</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Card Expiry</th>
                    @else
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"></th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"></th>
                    @endif

                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Payment Status</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Payment Type </th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Payment Date</th>


                </tr>
                </thead>
                <tbody>
                {{-- Loop for Get all Sections --}}
                @if(count($allinvoices)>0)
                    @foreach ($allinvoices as $invoice)
                        <tr role="row">
                            <td class=" adminquestion_image"></td>
                            <td class="sorting_1">{{$invoice->id}}</td>
                            <td>
                                <ol type="i">
                                    {{-- Loop for Get all questions of current Section --}}
                                    <table cellpadding="5" cellspacing="0" border="0"  class="mdl-data-table mdl-js-data-table" width="100%">
                                        <tr class="detailborderwithbold">
                                            <td>Sr #</td>
                                            <td>Plan</td>
                                            <td>Amount</td>
                                            <td>Quantity</td>
                                            <td>Total</td>
                                            <td>Pakage Type</td>
                                        </tr>
                                        @if(count($details)>0)
                                            @foreach ($details[$invoice->id] as $key => $invoicedetail)
                                                <tr class="detailborder">
                                                    <td>{{$key+ 1}}</td>
                                                    <td>{{$invoicedetail['title']}}</td>
                                                    <td>${{number_format($invoicedetail['price'],2)}}</td>
                                                    <td>{{$invoicedetail['total']}}</td>
                                                    <td>${{number_format($invoicedetail['total'] * $invoicedetail['price'],2)}}</td>
                                                    <td>{{$invoicedetail['package_type']}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>

                                </ol>
                            </td>
                            <td>${{number_format($invoice->invoice_total,2)}}</td>
                            @if($user->corporateclient->payment_method  == "Credit Card")
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$invoice->card_no}} </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$invoice->card_expiry }}</td>
                            @else
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"></td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"></td>
                            @endif
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$invoice->payment_status}}</td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$invoice->payment_type}} </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$invoice->payment_date}}</td>


                        </tr>
                    @endforeach
                @endif
                </tbody>

            </table>
        </div>
    </div>

        <div class="mdl-card__menu" style="">
            <a href="{{ url('/makebilling') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Make Billing of Subscription">
                <i class="material-icons">add</i>
                <span class="sr-only">Make Billing of Subscription</span>
            </a>
        </div>


</div>

@endsection

@section('footer_scripts')
    @include('scripts.highlighter-script')

    @include('scripts.mdl-datatables')
    <script type="text/javascript">
        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" class="expendedquestion">'+

                '<tr>'+

                '<td>'+d[2]+'</td>'+
                '</tr>'+
                '</table>';
        }

        $(document).ready(function() {
            var table = $('#example').DataTable( {

                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "name" },
                    { "visible": false },
                    { },
                    { },
                    { },
                    { },
                    { },
                    { }


                ],
                "order": [[1, 'asc']]
            } );

            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.adminquestion_image', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
        } );
    </script>
    <script type="text/javascript">
        $( document ).ready(function() {
            @foreach ($users as $a_user)
            $('#{{$a_user->id}}').change(function() {
                if(this.checked) {
                    var returnVal = confirm("Are you sure to Block this User?");
                    $(this).prop("checked", returnVal);
                    var id = {{$a_user->id}};
                    var status = 1;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/clients/blockit',

                        data:{"id":id, "status":1,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert(data.success);

                        }

                    });
                }
                else{
                    //var returnVal = confirm("Are you sure to Un Block this User?");
                    //$(this).prop("", returnVal);
                    var id = {{$a_user->id}};
                    var status = 0;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/clients/blockit',

                        data:{"id":id, "status":0,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert("User Un Blocked");

                        }

                    });
                }

            });
            @endforeach
        });
        
    </script>
@endsection
