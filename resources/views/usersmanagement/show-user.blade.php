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

    {{--



    @if ($user->hasRole('user'))
      @php
            $access_level   = 'User';
            $access_class   = 'mdl-color--green-200 mdl-color-text--white';
            $access_icon  = 'lock';
      @endphp
    @elseif ($user->hasRole('editor'))
      @php
            $access_level   = 'Editor';
            $access_class   = 'mdl-color--green-400 mdl-color-text--white';
            $access_icon  = 'lock_outline';
      @endphp
    @elseif ($user->hasRole('administrator'))
      @php
            $access_level   = 'Administrator';
            $access_class   = 'mdl-color--green-600 mdl-color-text--white';
            $access_icon  = 'verified_user';
      @endphp
    @endif

    --}}

    @foreach ($roles as $role)
        @if ($role->name == 'User')
            @php
                $levelIcon        = 'person';
                $levelName        = 'User';
                $levelBgClass     = 'mdl-color--blue-200';
                $leveIconlBgClass = 'mdl-color--blue-500';
            @endphp
        @elseif ($role->name == 'Admin')
            @php
                $levelIcon        = 'supervisor_account';
                $levelName        = 'Admin';
                $levelBgClass     = 'mdl-color--red-200';
                $leveIconlBgClass = 'mdl-color--red-500';
            @endphp
        @elseif ($role->name == 'Unverified')
            @php
                $levelIcon        = 'person_outline';
                $levelName        = 'Unverified';
                $levelBgClass     = 'mdl-color--orange-200';
                $leveIconlBgClass = 'mdl-color--orange-500';
            @endphp
        @else
            @php
                $levelIcon        = 'person_outline';
                $levelName        = 'Unverified';
                $levelBgClass     = 'mdl-color--orange-200';
                $leveIconlBgClass = 'mdl-color--orange-500';
            @endphp
        @endif
    @endforeach
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .profile {
            /*padding: 57px 50px 15px 50px;*/
            width: 80%;
        }

        .profile-photo {
            /*margin-right: 10%;
            margin-bottom: 10px;*/
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

        .mdl-cell {
            padding: unset;
        }

    </style>


    <div class="mdl-grid full-grid margin-top-0 padding-0">
        <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
            <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
                <div class="profile">
                    <div style="float:left;"
                         class="mdl-cell mdl-cell mdl-cell--2-col mdl-cell--2-col-phone mdl-cell--5-col-tablet">
                        <div class="profile-photo"><img src="{{ $user->image_url }}" alt="{{ $user->name }}">
                        </div>
                    </div>
                    <div style="float:left;"
                         class=" mdl-cell--6-col mdl-cell--8-col-phone mdl-cell--7-col-tablet mdl-cell--7-col-desktop">
                        <div class="profile-info">
                            <div class="profile-info">
                                <div class="profile-preword"> @if(isset($packages->title))
                                        <span class="{{ $packages->title}}"> <i class="material-icons">
                                                ballot
                                            </i><i class="fas fa-award"></i></span>
                                    @endif
                                </div>
                                <h1 class="profile-title"><span></span> {{$user->name}}</h1>
                                <h2 class="profile-title"><span></span></h2>
                            </div>
                            <ul class="profile-list">
                                <li class="clearfix">
                                    <strong class="title">E-mail</strong>
                                    <span class="cont"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Package</strong>
                                    <span class="cont">
                                        @if(isset($payments) && sizeof($payments) > 0)
                                            {{ $payments[0]['description']}}
                                        @endif </span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Total Paid</strong>
                                    <span class="cont">
                                        @if(isset($payments) && sizeof($payments) > 0)
                                            ${{ number_format(collect($payments)->sum('amount_paid'), 2)}}
                                        @endif
                                    </span>
                                </li>
                                @if(isset($subscriptionStatus))
                                    <li class="clearfix">
                                        <strong class="title">Status</strong>
                                        <span style="color:{{$subscriptionStatus == 'canceled' ? 'red;font-weight:bold': 'green;font-weight:bold'}}"
                                              class="cont">{{strtoupper($subscriptionStatus) }}</span>
                                    </li>
                                @endif
                                @if(isset($subscriptionStatus) && $subscriptionStatus != 'canceled' && isset($NextPayment))
                                    <li class="clearfix">
                                        <strong class="title">Next Billing Date</strong>
                                        <span style="color:green;"
                                              class="cont">{{$NextBilling }}</span>
                                    </li>
                                    <li class="clearfix">
                                        <strong class="title">Billing Amount</strong>
                                        <span style="color:green;"
                                              class="cont">${{number_format($NextPayment, 2) }}</span>
                                    </li>
                                @endif
                                <li class="clearfix">
                                    <strong class="title">Reg Date</strong>
                                    <span class="cont">{{ \Carbon\Carbon::parse($user->created_at)->format('m/d/Y') }}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Today Appts</strong>
                                    <span class="cont">{{$todayappoinments}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Upcoming Appts</strong>
                                    <span class="cont">{{$upcomingappoinments}}</span>
                                </li>
                                <li class="clearfix">
                                    <strong class="title">Active Therapist</strong>
                                    @foreach ($activetherapist as $therapist)
                                        <span class="cont">{{$therapist->therapist->fullname}}</span>
                                    @endforeach
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
                    @if (Auth::user()->id == $user->id)
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
            <!--
          <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
              <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                  <h2 class="mdl-card__title-text logo-style">
                      @if (sizeof($activetherapist)  === 1)
                {{ sizeof($activetherapist) }} Active Therapists total
                      @elseif (sizeof($activetherapist) > 1)
                {{ sizeof($activetherapist) }} Total Active Therapists
                      @else
                No Active Therapist Found
@endif
                    </h2>

                </div>
                <div class="table-responsive material-table">
                    <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                            <th class="mdl-data-table__cell--non-numeric">Appointment Date</th>
                            <th class="mdl-data-table__cell--non-numeric">Therapist</th>

                        </tr>
                        </thead>
                        <tbody>
@php $i=0 @endphp
            @foreach ($activetherapist as $therapist)
                <tr>
                    <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                              <td class="mdl-data-table__cell--non-numeric">{{$therapist->start_date}} </td>
                              <td class="mdl-data-table__cell--non-numeric">{{$therapist->therapist->fullname}} </td>


                          </tr>
                          @php $i++ @endphp
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        --->
                <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                        <h2 class="mdl-card__title-text logo-style">
                            @if (sizeof($previoustherapists)  === 1)
                                {{ sizeof($previoustherapists) }} Pervious Therapists total
                            @elseif (sizeof($previoustherapists) > 1)
                                {{ sizeof($previoustherapists) }} Total Pervious Therapists
                            @else
                                No Pervious Therapist Found
                            @endif
                        </h2>

                    </div>
                    <div class="table-responsive material-table">
                        <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                                <th class="mdl-data-table__cell--non-numeric">Therapist</th>
                                <th class="mdl-data-table__cell--non-numeric">Start Date</th>
                                <th class="mdl-data-table__cell--non-numeric">End Date</th>
                                <th class="mdl-data-table__cell--non-numeric">No. of Appts</th>


                            </tr>
                            </thead>
                            <tbody>
                            @php $i=0 @endphp
                            @foreach ($previoustherapists as $therapist)
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                                    <td class="mdl-data-table__cell--non-numeric">{{$therapist->therapist->fullname}} </td>
                                    <td class="mdl-data-table__cell--non-numeric">{{ \Carbon\Carbon::parse($therapist->start_date)->format('m/d/Y') }} </td>
                                    <td class="mdl-data-table__cell--non-numeric">{{ \Carbon\Carbon::parse($therapist->end_date)->format('m/d/Y') }} </td>
                                    <td class="mdl-data-table__cell--non-numeric">{{$therapist->no_of_appt}}</td>


                                </tr>
                                @php $i++ @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
                    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
                        <h2 class="mdl-card__title-text logo-style">
                            @if (sizeof($payments)  === 1)
                                {{ sizeof($payments) }} Payments total
                            @elseif (sizeof($payments) > 1)
                                {{ sizeof($payments) }} Total Payments
                            @else
                                No Payments Found
                            @endif
                        </h2>

                    </div>
                    <div class="table-responsive material-table">
                        <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
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
                            @foreach ($payments as $key => $payment)
                                @if($payment['status'] == 'Refunded')
                                @endif
                                <tr style="background-color:{{$payment['status'] == 'Refunded' ? '#ffeed9;': ''}}">
                                    <td class="mdl-data-table__cell--non-numeric">{{$key+1}}</td>
                                    <td class="mdl-data-table__cell--non-numeric">{{$payment['number']}} </td>
                                    <td class="mdl-data-table__cell--non-numeric">${{$payment['description']}} </td>
                                    <td class="mdl-data-table__cell--non-numeric">${{number_format($payment['status'] == 'Refunded' ? $payment['refunded_amount'] : $payment['amount_paid'],2)}} </td>
                                    <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($payment['period_start'])->format('m/d/Y') .' -- '.\Carbon\Carbon::parse($payment['period_end'])->format('m/d/Y') }} </td>
                                    <td class="mdl-data-table__cell--non-numeric">{{\Carbon\Carbon::parse($payment['created'])->format('m/d/Y') }}</td>
                                    <td class="mdl-data-table__cell--non-numeric"><span style="color: {{$payment['status'] == 'paid' ? 'green; font-weight:bold;': 'red; font-weight:bold;'}}">{{strtoupper($payment['status'])}} </span> </td>
                                    <td class="mdl-data-table__cell--non-numeric"><a href="{{$payment['invoice_pdf']}}" title="View Invoice">View Invoice </a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @section('footer_scripts')
            @include('scripts.highlighter-script')
            @include('scripts.mdl-datatables')
        @endsection

        @endsection

        @section('footer_scripts')

            @include('scripts.google-maps-geocode-and-map')

            <script type="text/javascript">

                mdl_dialog('.dialog-button-delete', '.dialog-delete-close', '#dialog_delete');

            </script>

@endsection