@extends('layouts.dashboard')

@section('template_title')
    Showing User {{ $user->name }}
@endsection

@section('header')
    Showing {{ $user->name }}
@endsection

@php
    $levelAmount = trans('usersmanagement.labelUserLevel');
    if ($user->level() >= 2) {
        $levelAmount = trans('usersmanagement.labelUserLevels');
    }
@endphp

@section('breadcrumbs')
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="{{url('/')}}">
      <span itemprop="name">
        {{ trans('titles.app') }}
      </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="1"/>
    </li>
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/users">
      <span itemprop="name">
        Clients List
      </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="2"/>
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/users/{{ $user->id }}">
      <span itemprop="name">
        {{ $user->name }}
      </span>
        </a>
        <meta itemprop="position" content="3"/>
    </li>
@endsection

@section('content')



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .profile {
            padding: 57px 50px 15px 50px;
        }

        .profile-photo {
            margin-right: 10%;
            margin-bottom: 10px;
        }

        .profile-info {
            color: #3d4451;
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #dedede;
        }

        .profile-preword {
            margin-bottom: 28px;
        }

        .profile-preword span:before {
            content: '';
            width: 0;
            height: 0;
            top: 100%;
            left: 5px;
            display: block;
            position: absolute;
            border-style: solid;
            border-width: 0 0 8px 8px;
            border-color: transparent;
        }

        .profile-title {
            font-size: 36px;
            line-height: 1.1;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-title span {
            font-weight: 300;
        }

        .profile-position {
            font-size: 18px;
            font-weight: 400;
            line-height: 1.1;
            margin-bottom: 0;
        }

        .profile-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .profile-list li {
            margin-bottom: 13px;
        }

        .clearfix:before, .clearfix:after {
            content: " ";
            display: table;
        }

        .profile-list .title {
            display: block;
            width: 115px;
            float: left;
            color: #333333;
            font-size: 12px;
            font-weight: 700;
            line-height: 20px;
            text-transform: uppercase;
        }

        .profile-list .cont {
            display: block;
            margin-left: 125px;
            font-size: 15px;
            font-weight: 400;
            line-height: 20px;
            color: #757575;
        }

        .profile-list .cont a {

            color: #757575;
            text-decoration: none;
        }

        .clearfix:after {
            clear: both;
        }

        .profile-photo img {
            width: 100%;
            display: block;
        }

        img {
            border: 0;
            height: auto;
            max-width: 100%;
            vertical-align: middle;
        }

        .mdl-cell {
            padding: 10px;
        }

        .Gold {
            color: #d58512;
        }

        .Silver {
            color: #636b6f;
        }

        .Basic {
            color: #1a237e;
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


    <div class="mdl-grid full-grid margin-top-0 padding-0">
        <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
            <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
                <div class="profile">

                    <div style="float:left;"
                         class="mdl-cell mdl-cell mdl-cell--6-col mdl-cell--6-col-phone mdl-cell--7-col-tablet mdl-cell--7-col-desktop">
                        <div class="profile-info">
                            <div class="profile-info">
                                <div class="profile-preword"> @if(isset($packages->title))
                                        <span class="{{ $packages->title}}"> <i class="material-icons">
ballot

</i><i class="fas fa-award"></i></span>
                                    @endif

                                </div>
                                <h1 class="profile-title"><span></span> {{$userObj->name}}</h1>
                                <h2 class="profile-title"><span></span></h2>
                            </div>
                            <ul class="profile-list">
                                @if(!is_null($userObj->corporateclient) && $userObj->corporateclient->payment_method=='Prepaid Bank Deposit')
                                    <li class="clearfix">
                                        <strong class="title">Balance </strong>
                                        <span class="cont">${{number_format($balance - $invoiceTotal,2)}}</span>
                                    </li>
                                @endif
                                <li class="clearfix">
                                    <strong class="title">Company Phone </strong>
                                    <span class="cont">{{$userObj->corporateclient->company_phone}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Company Address</strong>
                                    <span class="cont">
                          {{$userObj->corporateclient->company_address}} </span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Contact Person</strong>
                                    <span class="cont">{{$userObj->corporateclient->contact_person_name}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Contact Person Phone</strong>
                                    <span class="cont">{{$userObj->corporateclient->contact_person_phone}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Contact Person Address</strong>
                                    <span class="cont">{{$userObj->corporateclient->contact_person_address}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Invitation Link</strong>
                                    <span class="cont"><a
                                                href={{url('/newuser/'.$userObj->corporateclient->invitation_link)}} target="_blank"
                                                style="color: blue;">View Link</a></span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Anonymity</strong>
                                    <span class="cont">{{$userObj->corporateclient->annonymity==1 ? 'Yes':'No'}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Corporate Email</strong>
                                    <span class="cont">{{$userObj->corporateclient->is_corp_email==1 ? 'Yes':'No'}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Payment Method</strong>
                                    <span class="cont">{{$userObj->corporateclient->payment_method}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>


                <div class="mdl-user-avatar">

                    <span itemprop="image" style="display:none;">{{ Gravatar::get($user->email) }}</span>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid full-grid padding-0">
                        <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--6-col-desktop">


                        </div>
                    </div>
                    @if (Auth::user()->id == $userObj->id)
                        <div class="mdl-card__actions">
                            <div class="mdl-grid full-grid">
                                <div class="mdl-cell mdl-cell--12-col">
                                    <a href="/profile/{{ Auth::user()->name }}/edit"
                                       class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-shadow--3dp mdl-button--raised mdl-button--primary mdl-color-text--white">
                                        <i class="material-icons padding-right-half-1">edit</i>
                                        {{ Lang::get('titles.editProfile') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mdl-card__menu">


                    </div>
                </div>
                <div class="mdl-card__menu" style="top: 24px;">

                    <a href="{{ url('/dowloadcsvcodes/'.$userObj->corporateclient->id) }}"
                       class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white"
                       title="Download CSV">
                        <i class="material-icons">system_update_alt</i>
                        <span class="sr-only">Download  CSV </span>
                    </a>


                </div>
            </div>

            <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                    <h2 class="mdl-card__title-text logo-style">
                        {{$allclients}} Client total
                    </h2>

                </div>
                <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
                    <div class="table-responsive material-table">
                        <table id="user_table" class="mdl-data-table mdl-js-data-table data-table"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                                <th class="mdl-data-table__cell--non-numeric">Login Count</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php $i=0 @endphp

                            - @foreach ($allusers as $user)


                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                                    <td class="mdl-data-table__cell--non-numeric">{{$user->no_of_logins}}</td>

                                </tr>
                                @php $i++ @endphp
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mdl-card__menu" style="top: -4px;">


                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-9">
                    <div>
                        <div class="mdl-grid full-grid margin-top-0 padding-0">
                            <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
                                <div class="mdl-card card-new-user" itemscope
                                     itemtype="http://schema.org/Person">

                                    <div class="mdl-card__title mdl-card--expand mdl-color--green mdl-color-text--white">
                                        <h2 style="width: 80% !important;"
                                            class="mdl-card__title-text logo-style">Invite User</h2>

                                        <input type="radio" id="single" name="invite" value="single"
                                               checked>
                                        <label for="single">Input Emails</label>

                                        <input type="radio" id="bulk" name="invite" value="bulk">
                                        <label for="bulk">Upload csv</label>

                                    </div>

                                    <div id="single-invite">
                                        {!! Form::open(array('id'=>'singleinvite','action' => 'CorporateUsersManagementController@store', 'method' => 'POST', 'role' => 'form')) !!}

                                        <div class="mdl-card__supporting-text">
                                            <div class="mdl-grid full-grid padding-0">
                                                <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                                                    <div class="mdl-grid ">
                                                        {!! Form::hidden('corporate', $userObj->id, array('id' => 'corporate', 'class' => 'mdl-textfield__input')) !!}
                                                        <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                                {!! Form::text('email', NULL, array('id' => 'email', 'placeholder'=> 'user01@ayanatherapy.com, user02@ayanatherapy.com, user03@ayanatherapy.com',  'class' => 'mdl-textfield__input')) !!}
                                                                {!! Form::label('email', trans('auth.email') , array('class' => 'mdl-textfield__label')); !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="mdl-card__actions padding-top-0">
                                            <div class="mdl-grid padding-top-0">
                                                <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">
                                                        <span class="save-actions">
                                                            {!! Form::button('<i class="material-icons">save</i> Invite New User', array('id' =>'singleinv',  'class' => 'singleuserrequest mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
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
                                                               target="_blank">Download Sample CSV File</a>
                                                            Note: plese download file and upload in this
                                                            format<br>
                                                        </p>
                                                    </div>

                                                    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('csvfile') ? 'is-invalid' :'' }}">
                                                            {!! Form::hidden('corporate', $userObj->id, array('id' => 'corporate', 'class' => 'mdl-textfield__input')) !!}
                                                            {!! Form::file('csvfile', NULL, array('id' => 'csvfile', 'class' => 'mdl-textfield__input')) !!}
                                                            {!! Form::label('csvfile', 'CSV File' , array('class' => 'mdl-textfield__label')) !!}
                                                            {!! Form::hidden('created_by',  '', array('id' => 'created_by', 'class' => 'mdl-textfield__input')) !!}
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
                                                            {!! Form::button('<i class="material-icons">save</i> Upload CSV File', array('class' => 'csvrequest mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                                                        </span>
                                                    <span id="loader2"><div
                                                                class="mdl-spinner mdl-js-spinner is-active"></div></span>
                                                    <span id="result"></span>
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
            </div>

            @if(!is_null($userObj->corporateclient) && $userObj->corporateclient->payment_method=='Prepaid Bank Deposit')
                <div class="mdl-grid full-grid margin-top-0 padding-0">
                    <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">

                        <div class="mdl-card card-new-user" itemscope itemtype="http://schema.org/Person">

                            <div class="mdl-card__title mdl-card--expand mdl-color--primary mdl-color-text--white">
                                <h2 class="mdl-card__title-text logo-style">Add New Prepaid Payment</h2>
                            </div>

                            {!! Form::open(array('action' => 'CorporateClientController@addpayment', 'method' => 'POST', 'role' => 'form')) !!}

                            <div class="mdl-card__supporting-text">
                                <div class="mdl-grid full-grid padding-0">
                                    <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                                        <div class="mdl-grid ">
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="datepicker mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('prepaid_amount') ? 'is-invalid' :'' }}">
                                                    {!! Form::hidden('user_id', $userid, array('id' => 'user_id', 'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::hidden('corp_client_id', $corp_id, array('id' => 'corp_client_id', 'class' => 'mdl-textfield__input')) !!}
                                                    <input type="number" placeholder="0.00"
                                                           name="prepaid_amount" id="prepaid_amount" min="0"
                                                           value="{{old('prepaid_amount')}}" step="0.01"
                                                           class="mdl-textfield__input" title="Amount"
                                                           pattern="^\d+(?:\.\d{1,2})?$">
                                                    {!! Form::label('prepaid_amount', "Amount($)" , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Add Valid Amount</span>
                                                </div>
                                            </div>
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                                                </div>
                                            </div>
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('payment_date') ? 'is-invalid' :'' }}">
                                                    {!! Form::text('payment_date', old('payment_date'), array('placeholder'=>"",'id' => 'payment_date', 'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::label('payment_date', "Payment Date" , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Add Payment Date</span>
                                                </div>
                                            </div>
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                                                </div>
                                            </div>
                                            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('notes') ? 'is-invalid' :'' }}">
                                                            <textarea class="mdl-textfield__input" type="text" rows="5"
                                                                      id="notes" name="notes"></textarea>

                                                    {!! Form::label('notes', "Notes" , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Add Notes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mdl-card__actions padding-top-0">
                                <div class="mdl-grid padding-top-0">
                                    <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">

                                        {{-- SAVE BUTTON--}}
                                        <span class="save-actions">
                  {!! Form::button('<i class="material-icons">save</i> Save Payment', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ','type'=>'submit')) !!}
                </span>

                                    </div>
                                </div>
                            </div>

                            <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Save New User')) !!}
              </span>


                            </div>

                            @include('dialogs.dialog-save')

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                        <h2 class="mdl-card__title-text logo-style">
                            {{count($allpayments)}} Payments
                        </h2>

                    </div>
                    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
                        <div class="table-responsive material-table">
                            <table id="payments_table" class="mdl-data-table mdl-js-data-table data-table"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                                    <th class="mdl-data-table__cell--non-numeric">Payment Date</th>
                                    <th class="mdl-data-table__cell--non-numeric">Amount</th>


                                </tr>
                                </thead>
                                <tbody>
                                @php $i=0 @endphp

                                - @foreach ($allpayments as $payment)


                                    <tr>
                                        <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                                        <td class="mdl-data-table__cell--non-numeric">{{$payment->payment_date}}</td>
                                        <td class="mdl-data-table__cell--non-numeric">
                                            ${{number_format($payment->total_amount, 2)}}</td>

                                    </tr>
                                    @php $i++ @endphp
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mdl-card__menu" style="top: -4px;">

                        @include('partials.mdl-highlighter')

                        @include('partials.mdl-search')

                    </div>
                </div>
            @else
                <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                        <h2 class="mdl-card__title-text logo-style">
                            {{count($allpayments)}} Client Utilizing.
                        </h2>

                    </div>
                    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
                        <div class="table-responsive material-table">
                            <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                                    @if($userObj->corporateclient->annonymity==0)
                                        <th class="mdl-data-table__cell--non-numeric">Account</th>
                                    @endif
                                    <th class="mdl-data-table__cell--non-numeric">Invoice No.</th>
                                    <th class="mdl-data-table__cell--non-numeric">Plan</th>
                                    <th class="mdl-data-table__cell--non-numeric">Amount</th>
                                    <th class="mdl-data-table__cell--non-numeric">Billing Period</th>
                                    <th class="mdl-data-table__cell--non-numeric">Invoice Date</th>
                                    <th class="mdl-data-table__cell--non-numeric">Status</th>
{{--                                    <th class="mdl-data-table__cell--non-numeric">View</th>--}}

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($allpayments as $key1 => $person)
                                    @foreach ($person as $key2 => $payment)
                                        <tr>
                                            <td class="mdl-data-table__cell--non-numeric">{{$key2+1}}</td>
                                            @if($userObj->corporateclient->annonymity==0)
                                                <td class="mdl-data-table__cell--non-numeric">{{$payment['customer_email']}} </td>
                                            @endif
                                            <td class="mdl-data-table__cell--non-numeric">{{$payment['number']}} </td>
                                            <td class="mdl-data-table__cell--non-numeric">
                                                ${{$payment['description']}} </td>
                                            <td class="mdl-data-table__cell--non-numeric">
                                                ${{number_format($payment['amount_paid'],2)}} </td>
                                            <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($payment['period_start'])->format('m/d/Y') .' -- '.\Carbon\Carbon::parse($payment['period_end'])->format('m/d/Y') }} </td>
                                            <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($payment['created'])->format('m/d/Y') }}</td>
                                            <td class="mdl-data-table__cell--non-numeric"><span
                                                        style="color: {{$payment['status'] == 'paid' ? 'green; font-weight:bold;': 'red; font-weight:bold;'}}">{{strtoupper($payment['status'])}} </span>
                                            </td>
                                            {{--<td class="mdl-data-table__cell--non-numeric">
                                                <span class="cont"><a
                                                            href={{$payment['invoice_pdf']}} target="_blank"
                                                            style="color: blue;">View Invoice</a>
                                                </span>
                                            </td>--}}
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mdl-card__menu" style="top: -4px;">

                        @include('partials.mdl-highlighter')

                        @include('partials.mdl-search')

                    </div>
                </div>

            @endif

        </div>
    </div>
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')

    @include('scripts.mdl-required-input-fix')
    @include('scripts.gmaps-address-lookup-api3')

    <script type="text/javascript">
        mdl_dialog('.dialog-button-save');
        mdl_dialog('.dialog-button-icon-save');
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(function () {
            $("#payment_date").datepicker();
            $("#payment_date").on("change", function () {
                $("#payment_date").datepicker("option", "dateFormat", 'yy-mm-dd');
            });

        });

        $('#userTable').hide();
        $("#singleinv").on('click', function () {
            $("#loader").css("display", "block");
            $('#singleinv').prop('disabled', true);

            if ($("#email").val() == '') {
                $("#resultsuccess").css('color', 'red');
                $("#resultsuccess").text('Input comma seperated emails to invite.');
                $("#loader").css("display", "none");
                return false;
            } else {
                $("#resultsuccess").css('color', 'green');
                $("#resultsuccess").text('');

            }
            $("#resultsuccess").text('');
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
                url: '/admininviteemployee',
                data: data,
                success: function (data) {
                    $("#email").val('');
                    $("#resultsuccess").text(data.message);
                    $("#loader").css("display", "none");
                    $('#singleinv').prop('disabled', false);

                },
                error: function (e) {
                    var txt = '';
                    $.each(e.responseJSON.errors, function (i, item) {
                        txt = txt + '<p>' + item[0] + '</p> ';
                        console.log("ERROR : ", item[0]);
                    });
                    $("#result").html(txt);
                    $('#singleinv').prop('disabled', false);

                    console.log("ERROR : ", e);
                }
            });
        });
        $(".csvrequest").on('click', function () {
            $("#loader2").css("display", "block");
            $("#resultsuccess2").text('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Get form
            var form = $('#uploadcsv')[0];
            $('.csvrequest').prop('disabled', true);

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
                url: '/savecsvfile',
                data: data,
                success: function (data) {
                    $('.csvrequest').prop('disabled', false);

                    console.log("SUCCESS : ", data);
                    $("#resultsuccess2").text(data.message);
                    $("#loader2").css("display", "none");
                    /* var len = data.data.length;
                     for(var i=1; i<len; i++){
                         var id = data.data[i].id;
                         var username = data.data[i][0];
                         var name = data.data[i][1];
                         var email = data.data[i][2];
                         var userstatus = data.data[i][3];
                         var emailstatus = data.data[i][4];


                         var tr_str = "<tr>" +
                             "<td align='center'>" + (i+1) + "</td>" +
                             "<td align='center'>" + username + "</td>" +
                             "<td align='center'>" + name + "</td>" +
                             "<td align='center'>" + email + "</td>" +
                             "<td align='center'>" + userstatus + "</td>" +
                             "<td align='center'>" + emailstatus + "</td>" +
                             "</tr>";

                         $("#userTable tbody").append(tr_str);
                     }
                     $('#userTable').show();*/
                },
                error: function (e) {
                    $('.csvrequest').prop('disabled', false);

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




@endsection

