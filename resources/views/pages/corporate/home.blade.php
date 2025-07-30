@extends('layouts.dashboardcharts')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('header')
    {{ trans('auth.loggedIn', ['name' => Auth::user()->name]) }}
@endsection

@section('breadcrumbs')

    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{url('/')}}">
			<span itemprop="name">
				{{ trans('titles.app') }}
			</span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="1"/>
    </li>
    <li class="active">
        {{ trans('titles.dashboard') }}
    </li>

@endsection
@section('content')
    <script type="text/javascript" src="{{ URL::asset('js/jquery-1.10.2.js') }}"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {

            Morris.Donut({
                element: 'morris-donut-chart',
                data: [{

                    label: "Logged In Employees",
                    value: {{$loggedinclients}}
                }, {
                    label: "Not Logged In Employees",
                    value: {{$notloggedinclients}}
                }],
                colors: [
                    '#00a851',
                    '#FF9800'
                ],
                resize: true
            });


        });
    </script>
    <style>
        .mdl-grid2 {
            display: block !important;

            -webkit-flex-flow: nowrap;
            -ms-flex-flow: row wrap;
            flex-flow: nowrap;
            /* margin: 0 auto; */
            -webkit-align-items: normal;
            -ms-flex-align: normal;
            align-items: normal;
        }

        /*
        Designing help by Mustafeez.
        */
        .mdl-grid .row {
            margin-left: initial;
            margin-right: initial;
        }

        .card-stacked {
            min-width: 180px;
        }

        .col-xs-12.col-sm-12.col-md-7 {
            padding-left: initial;
        }

        .mdl-grid {
            padding: initial !important;
        }

        .row {
            width: 100%;
        }

        .col-xs-12.col-sm-12.col-md-5 {
            padding: initial;
        }

        .col-xs-12.col-sm-6.col-md-7 {
            padding: initial;
        }

        #result, #result1 {
            color: red;
        }

        #resultsuccess {
            color: green;
        }

        #resultsuccess2 {
            color: green;
        }

        #loader {
            display: none;
        }

        #loader2 {
            display: none;
        }
    </style>

    <div class="row">
        <div class="col-xs-12" style="margin-top: 20px">
            <strong>SPENDING STATS</strong>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image red">
                    <i class="material-icons dp48">import_export</i>
                </div>
                <div class="card-stacked red">
                    <div class="card-content">
                        <h5>${{number_format($spending['totalspending'],2)}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>TOTAL SPENDING</strong>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image orange">
                    <i class="material-icons dp48">shopping_cart</i>
                </div>
                <div class="card-stacked orange">
                    <div class="card-content">
                        <h5>${{number_format($spending['thismonthspending'],2)}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>THIS MONTH</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image blue">
                    <i class="material-icons dp48">equalizer</i>
                </div>
                <div class="card-stacked blue">
                    <div class="card-content">
                        <h5>${{number_format($spending['thisweekspending'],2)}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>THIS WEEK</strong>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image green">
                    <i class="material-icons dp48">supervisor_account</i>
                </div>
                <div class="card-stacked green">
                    <div class="card-content">
                        <h5>${{number_format($spending['todayspending'],2)}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>YESTERDAY </strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @if(Auth::user()->corporateclient->payment_method  == "Prepaid Bank Deposit")
        <div class="row">
            <div class="col-xs-12" style="margin-top: 20px">
                <strong>Balance Stats</strong>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">

                <div class="card horizontal cardIcon waves-effect waves-dark">
                    <div class="card-image red">
                        <i class="material-icons dp48">import_export</i>
                    </div>
                    <div class="card-stacked red">
                        <div class="card-content">
                            <h5>${{number_format($spending['balance'],2)}}</h5>
                        </div>
                        <div class="card-action">
                            <strong>Balance</strong>
                        </div>
                    </div>
                </div>

            </div>
            @if($spending['overdue'] < 0)
                <div class="col-xs-12 col-sm-6 col-md-3">

                    <div class="card horizontal cardIcon waves-effect waves-dark">
                        <div class="card-image orange">
                            <i class="material-icons dp48">shopping_cart</i>
                        </div>
                        <div class="card-stacked orange">
                            <div class="card-content">
                                <h5>${{number_format($spending['overdue'],2)}}</h5>
                            </div>
                            <div class="card-action">
                                <strong>OverDue Amount</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif


    <div class="row">
        <div class="col-xs-12" style="margin-top: 20px">
            <strong>EMPLOYEE STATS</strong>
        </div>
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-7">
                <div class="cirStats">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image blue">
                                    <i class="material-icons dp48">supervisor_account</i>
                                </div>
                                <div class="card-stacked light-blue-text">
                                    <div class="card-content">
                                        <h5>{{$notloggedinclients}}</h5>
                                    </div>
                                    <div class="card-action">
                                        <strong>NOT LOGGED IN</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="card horizontal cardIcon waves-effect waves-dark">
                                <div class="card-image red">
                                    <i class="material-icons dp48">supervisor_account</i>
                                </div>
                                <div class="card-stacked red-text">
                                    <div class="card-content">
                                        <h5>{{$loggedinclients}}</h5>
                                    </div>
                                    <div class="card-action">
                                        <strong>LOGGED IN</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.row-->
            <div class="col-xs-12 col-sm-12 col-md-5">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-action">
                                <b>Total Employees:{{$sumofclients}}</b>
                            </div>
                            <div class="card-image donutpad">
                                <div id="morris-donut-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.row-->
        </div>
        <div class="">


        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" style="margin-top: 20px">
            <strong>SUBSCRIPTION STATS</strong>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image red">
                    <i class="material-icons dp48">import_export</i>
                </div>
                <div class="card-stacked red">
                    <div class="card-content">
                        <h5>{{number_format($subscription['totalsubscription'])}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>TOTAL SUBSCRIPTION</strong>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image orange">
                    <i class="material-icons dp48">shopping_cart</i>
                </div>
                <div class="card-stacked orange">
                    <div class="card-content">
                        <h5>{{number_format($subscription['platinium'])}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>PLATINIUM</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image blue">
                    <i class="material-icons dp48">equalizer</i>
                </div>
                <div class="card-stacked blue">
                    <div class="card-content">
                        <h5>{{number_format($subscription['gold'])}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>GOLD</strong>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">

            <div class="card horizontal cardIcon waves-effect waves-dark">
                <div class="card-image green">
                    <i class="material-icons dp48">supervisor_account</i>
                </div>
                <div class="card-stacked green">
                    <div class="card-content">
                        <h5>{{number_format($subscription['basic'])}}</h5>
                    </div>
                    <div class="card-action">
                        <strong>BASIC </strong>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <hr>
    @if(Auth::user()->corporateclient->annonymity==0 || Auth::user()->corporateclient->annonymity==1)

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-12">
                <div class="mdl-grid full-grid margin-top-0 padding-0">
                    <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
                        <div class="mdl-card card-new-user" itemscope itemtype="http://schema.org/Person">

                            <div class="mdl-card__title mdl-card--expand mdl-color--green mdl-color-text--white">
                                <h2 style="width: 78%!important;" class="mdl-card__title-text logo-style">Invite
                                    User</h2>
                                <div class="mdl-color-text--white">
                                    <input type="radio" id="single" name="invite" value="single" checked>
                                    <label style="color: white;!important;" for="single">Input Emails</label>
                                    <input type="radio" id="bulk" name="invite" value="bulk">
                                    <label style="color: white;!important;" for="bulk">Upload csv</label>
                                </div>
                            </div>
                            <div id="single-invite">
                                {!! Form::open(array('id'=>'singleinvite','action' => 'CorporateUsersManagementController@store', 'method' => 'POST', 'role' => 'form')) !!}

                                <div class="mdl-card__supporting-text">
                                    <div class="mdl-grid full-grid padding-0">
                                        <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                                                    {!! Form::text('email', NULL, array('id' => 'email', 'placeholder'=> 'user01@ayanatherapy.com, user02@ayanatherapy.com, user03@ayanatherapy.com',  'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::label('email', trans('auth.email') , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.email') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="mdl-card__actions padding-top-0">
                                    <div class="mdl-grid padding-top-0">
                                        <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">
                                        <span class="save-actions">
                                            {!! Form::button('<i class="material-icons">save</i> Invite New User', array('class' => 'singleuserrequest dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                                        </span>
                                            <span id="loader"><div
                                                        class="mdl-spinner mdl-js-spinner is-active"></div></span>
                                            <span id="result"></span>
                                            <span id="resultsuccess"></span>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div id="bulk-invite" style="display: none">
                                {!! Form::open(array('id'=>'uploadcsv','action' => 'CorporateUsersManagementController@savecsv', 'method' => 'POST', 'role' => 'form','files'=>'true')) !!}
                                <div class="mdl-card__supporting-text">
                                    <div class="mdl-grid full-grid padding-0">
                                        <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                                            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                                                <p>
                                                    <a href="{{ URL::to( '/images/samplecsv.csv/') }}"
                                                       target="_blank">Download Sample CSV File</a> Note: plese
                                                    download file and upload in this format<br>
                                                </p>
                                            </div>

                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('csvfile') ? 'is-invalid' :'' }}">
                                                    {!! Form::file('csvfile', NULL, array('id' => 'csvfile', 'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::label('csvfile', 'CSV File' , array('class' => 'mdl-textfield__label')) !!}
                                                    {!! Form::hidden('created_by',  '' , array('id' => 'created_by', 'class' => 'mdl-textfield__input')) !!}
                                                    <span class="mdl-textfield__error">Upload Valid CSV File</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="mdl-card__actions padding-top-0">
                                    <div class="mdl-grid padding-top-0">
                                        <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">
                                        <span class="save-actions">
                                            {!! Form::button('<i class="material-icons">save</i> Upload CSV File', array('class' => 'csvrequest dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                                        </span>
                                            <span id="loader2"><div
                                                        class="mdl-spinner mdl-js-spinner is-active"></div></span>
                                            <span id="result1"></span>
                                            <span id="resultsuccess2"></span>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('footer_scripts')
    <script>
        // mdl_dialog('.dialog-button-save');
        $('#userTable').hide();
        $(".singleuserrequest").on('click', function () {
            $("#result").empty();
            $('.singleuserrequest').prop('disabled', true);

            $("#loader").css("display", "block");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Get form
            var form = $('#singleinvite')[0];

            // Create an FormData object
            var data = new FormData(form);

            // If you want to add an extra field for the FormData
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                url: '/inviteemployee',

                data: data,

                success: function (data) {

                    console.log("SUCCESS : ", data);
                    $("#resultsuccess").text(data.message);
                    $("#loader").css("display", "none");
                    $('.singleuserrequest').prop('disabled', false);


                },
                error: function (e) {
                    var txt = '';
                    $.each(e.responseJSON.errors, function (i, item) {
                        txt = txt + '<p>' + item[0] + '</p> ';
                        console.log("ERROR : ", item[0]);
                    });
                    $("#result").html(txt);
                    console.log("ERROR : ", e);


                }
            });
        });
        $(".csvrequest").on('click', function () {
            $("#userdata").empty();

            $("#loader2").css("display", "block");
            $("#resultsuccess2").text('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Get form
            var form = $('#uploadcsv')[0];

            // Create an FormData object
            var data = new FormData(form);

            // If you want to add an extra field for the FormData
            data.append("_token", "{{ csrf_token() }}");
            $('.csvrequest').prop('disabled', true);

            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                url: '/savecsv',

                data: data,

                success: function (data) {

                    console.log("SUCCESS : ", data);
                    $("#resultsuccess2").text(data.message);
                    $("#loader2").css("display", "none");
                    $('.csvrequest').prop('disabled', false);

                },
                error: function (e) {

                    $("#result1").text(e.responseJSON.errors.csvfile[0]);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);

                }
            });
        });
        $("input[type='radio']").on('click', function () {
            if ($(this).val() == 'single') {
                $("#single-invite").show();
                $("#bulk-invite").hide();

            } else {
                $("#bulk-invite").show();
                $("#single-invite").hide();
            }
        });
    </script>
@endsection

