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
    <meta itemprop="position" content="1" />
  </li>
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/clients">
      <span itemprop="name">
        Clients List
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/clients/{{ $user->id }}">
      <span itemprop="name">
        {{ $user->name }}
      </span>
    </a>
    <meta itemprop="position" content="3" />
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
        text-decoration:none;
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
    .mdl-cell{
        padding:10px;}
    .Gold{
        color: #d58512;
    }
    .Silver{
        color: #636b6f;
    }
    .Basic{
        color:#1a237e;
    }

</style>


<div class="mdl-grid full-grid margin-top-0 padding-0">
  <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
      <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
          <div class="profile">
              <div style="float:left;" class="mdl-cell mdl-cell mdl-cell--6-col mdl-cell--6-col-phone mdl-cell--5-col-tablet mdl-cell--5-col-desktop">
                <div class="profile-photo"><img src="{{ $user->image_url }}" alt="{{ $user->name }}">
                </div>
              </div>
              <div style="float:left;" class="mdl-cell mdl-cell mdl-cell--6-col mdl-cell--6-col-phone mdl-cell--7-col-tablet mdl-cell--7-col-desktop"><div class="profile-info">
                      <div class="profile-info">
                          <div class="profile-preword"> @if(isset($packages->title))
                                  <span class="{{ $packages->title}}"> <i class="material-icons">
ballot

</i><i class="fas fa-award"></i></span>
                                  @endif

</div>
                      <h1 class="profile-title"><span></span> {{$user->name}}</h1>
                      <h2 class="profile-title"><span></span> </h2>
                      </div>
                  <ul class="profile-list">

                      <li class="clearfix">
                          <strong class="title">E-mail</strong>
                          <span class="cont"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
                      </li>
                      <li class="clearfix">
                          <strong class="title">Package</strong>
                          <span class="cont">
                          @if(isset($packages->title))
                                  {{ $packages->title}}
                                   @endif </span>
                      </li>
                      <li class="clearfix">
                          <strong class="title">Reg Date</strong>
                          <span class="cont">{{ $user->created_at }}</span>
                      </li>
                      <li class="clearfix">
                          <strong class="title">Today Appts</strong>
                          <span class="cont">{{$todayappoinments}}</span>
                      </li>
                      <li class="clearfix">
                          <strong class="title">Upcoming Appts</strong>
                          <span class="cont">{{$upcomingappoinments}}</span>
                      </li>
                  </ul>
              </div></div>

          </div>



      <div class="mdl-user-avatar">

        <span itemprop="image" style="display:none;">{{ Gravatar::get($user->email) }}</span>
      </div>
{{--
        <div class="mdl-card__title" @if ($user->profile->user_profile_bg != NULL) style="background: url('{{$user->profile->user_profile_bg}}') center/cover;" @endif>
            <h3 class="mdl-card__title-text mdl-title-username">
              {{ $user->name }}
            </h3>
        </div>
    --}}
        <div class="mdl-card__supporting-text">
        <div class="mdl-grid full-grid padding-0">
          <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--6-col-desktop">


        </div>
        </div>
        @if (Auth::user()->id == $user->id)
          <div class="mdl-card__actions">
          <div class="mdl-grid full-grid">
            <div class="mdl-cell mdl-cell--12-col">
              <a href="/profile/{{ Auth::user()->name }}/edit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-shadow--3dp mdl-button--raised mdl-button--primary mdl-color-text--white">
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
                      @foreach ($previoustherapists as $therapist)
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
                  <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                          <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                          <th class="mdl-data-table__cell--non-numeric">Payment</th>
                          <th class="mdl-data-table__cell--non-numeric">payment Date</th>

                      </tr>
                      </thead>
                      <tbody>
                      @php $i=0 @endphp
                      @foreach ($payments as $payment)
                          <tr>
                              <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                              <td class="mdl-data-table__cell--non-numeric">{{$payment->amount}} </td>
                              <td class="mdl-data-table__cell--non-numeric">{{$payment->created_at}} </td>


                          </tr>
                          @php $i++ @endphp
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





















{{--



  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="panel @if ($user->activated == 1) panel-success @else panel-danger @endif">

          <div class="panel-heading">
            <a href="/clients/" class="btn btn-primary btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">{{ trans('usersmanagement.usersBackBtn') }}</span>
            </a>
            {{ trans('usersmanagement.usersPanelTitle') }}
          </div>
          <div class="panel-body">

            <div class="well">
              <div class="row">
                <div class="col-sm-6">
                  <img src="@if ($user->profile && $user->profile->avatar_status == 1) {{ $user->profile->avatar }} @else {{ Gravatar::get($user->email) }} @endif" alt="{{ $user->name }}" id="" class="img-circle center-block margin-bottom-2 margin-top-1 user-image">
                </div>

                <div class="col-sm-6">
                  <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                    {{ $user->name }}
                  </h4>
                  <p class="text-center text-left-tablet">
                    <strong>
                      {{ $user->first_name }} {{ $user->last_name }}
                    </strong>
                    <br />
                    {{ HTML::mailto($user->email, $user->email) }}
                  </p>

                  @if ($user->profile)
                    <div class="text-center text-left-tablet margin-bottom-1">

                      <a href="{{ url('/profile/'.$user->name) }}" class="btn btn-sm btn-info">
                        <i class="fa fa-eye fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> {{ trans('usersmanagement.viewProfile') }}</span>
                      </a>

                      <a href="/clients/{{$user->id}}/edit" class="btn btn-sm btn-warning">
                        <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> {{ trans('usersmanagement.editUser') }} </span>
                      </a>

                      {!! Form::open(array('url' => 'clients/' . $user->id, 'class' => 'form-inline')) !!}
                        {!! Form::hidden('_method', 'DELETE') !!}
                        {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md">' . trans('usersmanagement.deleteUser') . '</span>' , array('class' => 'btn btn-danger btn-sm','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user?')) !!}
                      {!! Form::close() !!}

                    </div>
                  @endif

                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @if ($user->name)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelUserName') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->name }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->email)

            <div class="col-sm-5 col-xs-6 text-larger">
              <strong>
                {{ trans('usersmanagement.labelEmail') }}
              </strong>
            </div>

            <div class="col-sm-7">
              {{ HTML::mailto($user->email, $user->email) }}
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @endif

            @if ($user->first_name)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelFirstName') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->first_name }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->last_name)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelLastName') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->last_name }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            <div class="col-sm-5 col-xs-6 text-larger">
              <strong>
                {{ trans('usersmanagement.labelRole') }}
              </strong>
            </div>

            <div class="col-sm-7">
              @foreach ($user->roles as $user_role)

                @if ($user_role->name == 'User')
                  @php $labelClass = 'primary' @endphp

                @elseif ($user_role->name == 'Admin')
                  @php $labelClass = 'warning' @endphp

                @elseif ($user_role->name == 'Unverified')
                  @php $labelClass = 'danger' @endphp

                @else
                  @php $labelClass = 'default' @endphp

                @endif

                <span class="label label-{{$labelClass}}">{{ $user_role->name }}</span>

              @endforeach
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            <div class="col-sm-5 col-xs-6 text-larger">
              <strong>
                {{ trans('usersmanagement.labelStatus') }}
              </strong>
            </div>

            <div class="col-sm-7">
              @if ($user->activated == 1)
                <span class="label label-success">
                  Activated
                </span>
              @else
                <span class="label label-danger">
                  Not-Activated
                </span>
              @endif
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            <div class="col-sm-5 col-xs-6 text-larger">
              <strong>
                {{ trans('usersmanagement.labelAccessLevel')}} {{ $levelAmount }}:
              </strong>
            </div>

            <div class="col-sm-7">

              @if($user->level() >= 5)
                <span class="label label-primary margin-half margin-left-0">5</span>
              @endif

              @if($user->level() >= 4)
                 <span class="label label-info margin-half margin-left-0">4</span>
              @endif

              @if($user->level() >= 3)
                <span class="label label-success margin-half margin-left-0">3</span>
              @endif

              @if($user->level() >= 2)
                <span class="label label-warning margin-half margin-left-0">2</span>
              @endif

              @if($user->level() >= 1)
                <span class="label label-default margin-half margin-left-0">1</span>
              @endif

            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            <div class="col-sm-5 col-xs-6 text-larger">
              <strong>
                {{ trans('usersmanagement.labelPermissions') }}
              </strong>
            </div>

            <div class="col-sm-7">
              @if($user->canViewUsers())
                <span class="label label-primary margin-half margin-left-0"">
                  {{ trans('permsandroles.permissionView') }}
                </span>
              @endif

              @if($user->canCreateUsers())
                <span class="label label-info margin-half margin-left-0"">
                  {{ trans('permsandroles.permissionCreate') }}
                </span>
              @endif

              @if($user->canEditUsers())
                <span class="label label-warning margin-half margin-left-0"">
                  {{ trans('permsandroles.permissionEdit') }}
                </span>
              @endif

              @if($user->canDeleteUsers())
                <span class="label label-danger margin-half margin-left-0"">
                  {{ trans('permsandroles.permissionDelete') }}
                </span>
              @endif
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @if ($user->created_at)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelCreatedAt') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->created_at }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->updated_at)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelUpdatedAt') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->updated_at }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->signup_ip_address)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelIpEmail') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->signup_ip_address }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->signup_confirmation_ip_address)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelIpConfirm') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->signup_confirmation_ip_address }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->signup_sm_ip_address)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelIpSocial') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->signup_sm_ip_address }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->admin_ip_address)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelIpAdmin') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->admin_ip_address }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($user->updated_ip_address)

              <div class="col-sm-5 col-xs-6 text-larger">
                <strong>
                  {{ trans('usersmanagement.labelIpUpdate') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $user->updated_ip_address }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif


          </div>

        </div>
      </div>
    </div>
  </div>

--}}


@endsection

@section('footer_scripts')

  @include('scripts.google-maps-geocode-and-map')

  <script type="text/javascript">

    mdl_dialog('.dialog-button-delete','.dialog-delete-close','#dialog_delete');

  </script>

@endsection
