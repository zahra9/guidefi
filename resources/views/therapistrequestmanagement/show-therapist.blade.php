@extends('layouts.dashboard')

@section('template_title')
  Showing User {{ $therapists->fullname }}
@endsection

@section('header')
  Showing {{ $therapists->fullname }}
@endsection



@section('breadcrumbs')
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="{{url('/')}}">
      <span itemprop="name">
Ayana      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="1" />
  </li>
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/therapistrequests">
      <span itemprop="name">
        Therepist Request Detail      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/users/{{ $therapists->id }}">
      <span itemprop="name">
        {{ $therapists->fullname }}
      </span>
    </a>
    <meta itemprop="position" content="3" />
  </li>
@endsection

@section('content')








<div class="mdl-grid full-grid margin-top-0 padding-0">
  <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
      <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
      <div class="mdl-user-avatar">

      </div>
{{--
        <div class="mdl-card__title" @if ($user->profile->user_profile_bg != NULL) style="background: url('{{$user->profile->user_profile_bg}}') center/cover;" @endif>
            <h3 class="mdl-card__title-text mdl-title-username">
              {{ $therapists->fullname }}
            </h3>
        </div>
    --}}
        <div class="mdl-card__supporting-text">
        <div class="mdl-grid full-grid padding-0">
          <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
              <div><h4 style="font-weight: bold;color: black;">Personal Detail</h4></div>

              <ul class="demo-list-icon mdl-list">



                  <li class="mdl-list__item mdl-typography--font-light">
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon" style="color: #00acc1">person</i>
                      <span itemprop="name">
                        {{ $therapists->fullname }}
                      </span>
                    </div>
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon" style="color: #00acc1">email</i>
                      <span itemprop="email">
                    {{ $therapists->email }}
                  </span>
                    </div>
                  </li>


                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color: #00acc1">contact_mail</i>
                    <span itemprop="email">
                    {{ $therapists->address }}
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                    <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color: #00acc1">phone</i>
                    <span itemprop="email">
                    {{ $therapists->phone }}
                  </span>
                  </div>
                    
                  </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color: #00acc1">language</i>
                    <span itemprop="email">
                    {{ $therapists->webaddress }}
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color: #00acc1">school</i>
                    <span itemprop="email">
                    {{ $therapists->education }}
                  </span>
                  </div>
                </li>
                 <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color: #00acc1">contact_mail</i>
                    @if($therapists->cvfile)
                    <span itemprop="email">
                    <a href="{{ asset("cvfiles/".$therapists->cvfile) }}" target="_blank"><span class="material-icons">
cloud_download
</span> Download CV </a>
                  </span>
                  @endif
                  </div>
                </li>
              </ul>
                <div><h4 style="font-weight: bold;color: black;">Questions Detail</h4></div>
                <ul class="demo-list-icon mdl-list">
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">

                    <span itemprop="email">
                    <strong style="font-weight: 500">Please provide your licensing information including the state(s) you are licensed in, the license number(s), and the license expiration date(s):</strong><br><br>
                      <div><i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->licensedetail }}</span></div>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">

                    <span itemprop="email">
                   <strong style="font-weight: 500">Please provide contact information of at least one licensed therapist who can be your reference.</strong><br><br>


                     <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->contactinformation }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">

                      <span itemprop="email">
                                              <strong style="font-weight: 500">Please provide the names of the states where you are certified to provide telemental health care.</strong><br><br>

                        <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;"> {{ $therapists->states }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                                            <strong style="font-weight: 500">Are you willing to conduct live video counseling sessions?</strong><br><br>

                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->videocounseling }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Do you have experience in providing counseling online? If so, please tell us about it.?</strong><br><br>
                       <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;"> {{ $therapists->counselingonline }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">How much time do you think you can devote to working with AYANA?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->timeatayana }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Which operating system and browser do you use on your computer?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->computerdetail }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Which operating system and browser do you use on your phone?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->osonphone }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">How many years have you been practicing counseling? </strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->experience }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Do you have at least 2,000 hours of providing counseling services?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->atleatexperience }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Have you ever been subject to an investigation or review by your licensing board? If so, please provide details.</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->investigation }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Have you ever been subject to discipline actions by your licensing board? If so, please provide details.</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->disciplineaction }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Are you able to provide services in another language other than English? If so, which one(s)?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->otherlanguages }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">What types of modalities do you use? (CBT, christian counseling, narrative based therapy)?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;"> {{ $therapists->modalities }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong>Q:Please provide some details about your counseling style and approach.</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->counselingstyle }}</span>
                  </span>
                  </div>
                </li>

                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                   <span itemprop="email">
                       <strong style="font-weight: 500">What are your specialties? (ADHD, Alzheimer's, anxiety, autism) </strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->specialties }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">Do you have any experience working with Lesbian, Gay, Bisexual, Trans, Queer and Non Gender conforming clients?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->experiencewithgay }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                      <strong style="font-weight: 500">Do you accept insurance? If so what kind?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;">{{ $therapists->acceptinsurance }}</span>
                  </span>
                  </div>
                </li>
                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <span itemprop="email">
                        <strong style="font-weight: 500">How did you hear about AYANA?</strong><br><br>
                      <i class="material-icons mdl-list__item-icon" style="color:green">done</i><span style="position: relative;bottom: 5px;"> {{ $therapists->hearabout }}</span>
                  </span>
                  </div>
                </li>
             
        </div>
        </div>

        <div class="mdl-card__menu">
        @if($therapists->status!=='Approved')
        <a href="{{ URL::to('therapistrequests/accepttherapist/' . $therapists->id . '') }}" class="" title="Accept" style="color: #00acc1">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">
          Accept
        </button>
      </a>
      <a href="{{ URL::to('therapistrequests/rejecttherapist/' . $therapists->id . '') }}" class="" title="Accept" style="color: #00acc1">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--red">
          Reject
        </button>
        </a>
        @else
        
          <i class="material-icons ">verified_user</i>
       
        @endif






        </div>
      </div>
  </div>
</div>






















{{--



  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="panel @if ($user->activated == 1) panel-success @else panel-danger @endif">

          <div class="panel-heading">
            <a href="/users/" class="btn btn-primary btn-xs pull-right">
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

                      <a href="/users/{{$user->id}}/edit" class="btn btn-sm btn-warning">
                        <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm hidden-md"> {{ trans('usersmanagement.editUser') }} </span>
                      </a>

                      {!! Form::open(array('url' => 'users/' . $user->id, 'class' => 'form-inline')) !!}
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



  <script type="text/javascript">

    mdl_dialog('.dialog-button-delete','.dialog-delete-close','#dialog_delete');

  </script>

@endsection