{{--@extends('layouts.auth')--}}

{{--@section('template_title')--}}
{{--    Reset Password--}}
{{--@endsection--}}

{{--@section('content')--}}

{{--    <div class="mdl-layout mdl-js-layout mdl-color--grey-100 mdl-auth-form">--}}
{{--        <main class="mdl-layout__content_auth">--}}
{{--            <div class="mdl-card mdl-shadow--2dp">--}}
{{--                <div class="mdl-card__title mdl-color--primary mdl-color-text--white">--}}
{{--                    <h2 class="mdl-card__title-text text-center full-span block">--}}
{{--                        {{ trans('titles.resetPword') }}--}}
{{--                    </h2>--}}
{{--                </div>--}}
{{--                <div class="mdl-card__supporting-text">--}}

{{--                    {!! Form::open(['route' => 'savepassword', 'method' => 'POST', 'class' => 'auth-form', 'id' => 'reset']) !!}--}}

{{--                        <input type="hidden" name="token" value="{{ $userinfo->token }}">--}}

{{--                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">--}}
{{--                                {!! Form::email('email', $userinfo->email, array('readonly'=>'readonly','id' => 'email', 'class' => 'mdl-textfield__input', )) !!}--}}
{{--                                {!! Form::label('email', trans('auth.email') , array('class' => 'mdl-textfield__label')); !!}--}}
{{--                                <span class="mdl-textfield__error">@if ($errors->has('email')){{{ $errors->first('email') }}} @endif</span>--}}
{{--                            </div>--}}

{{--                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password') ? 'is-invalid' :'' }}">--}}
{{--                                {!! Form::password('password', array('id' => 'userpass', 'class' => 'mdl-textfield__input'  )) !!}--}}
{{--                                {!! Form::label('password', trans('auth.password') , array('class' => 'mdl-textfield__label')); !!}--}}
{{--                                @if ($errors->has('password'))--}}
{{--                                    <span class="mdl-textfield__error">{{ trans('auth.pwLoginError') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password_confirmation') ? 'is-invalid' :'' }}">--}}
{{--                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'mdl-textfield__input')) !!}--}}
{{--                                {!! Form::label('password_confirmation', trans('auth.ph_password_conf') , array('class' => 'mdl-textfield__label')); !!}--}}
{{--                                @if ($errors->has('password_confirmation'))--}}
{{--                                    <span class="mdl-textfield__error">{{ trans('auth.pwLoginError') }}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                        @if(config('settings.reCaptchStatus'))--}}
{{--                            <div class="g-recaptcha" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>--}}
{{--                        @endif--}}

{{--                        {!! Form::button('<span class="mdl-spinner-text">'.trans('auth.resetPassword').'</span><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--primary mdl-color-text--white mdl-button--raised full-span margin-bottom-1 margin-top-2','type' => 'submit','id' => 'submit')) !!}--}}

{{--                    {!! Form::close() !!}--}}

{{--                </div>--}}
{{--                <div class="mdl-card__actions mdl-card--border">--}}

{{--                    {!! HTML::link(url('/login'), trans('auth.login'), array('id' => 'login', 'class' => 'mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect right')) !!}--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </main>--}}
{{--    </div>--}}

{{--@endsection--}}

@extends('layouts.app')
@section('content')
    <style>
        .main-panel {
            transition: width 0.25s ease, margin 0.25s ease;
            width: 100%;
            margin-left: 0px;
            min-height: calc(100vh - 106px);
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: column;
            flex-direction: column;
        }
        .mdl-color--primary {
            background-color: #04B4F0 !important;
        }
    </style>

    <div class="container-scroller" style="background: url('/assets/img/page_bg.png') !important;">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row  w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">


                                <img src="{{ asset('assets/images/logo.svg')}}" alt="logo">

                            </div>
                            <h4 class="login">{{ trans('titles.resetPword') }}</h4>
                            {!! Form::open(['route' => 'savepassword', 'method' => 'POST', 'class' => 'auth-form', 'id' => 'reset']) !!}--}}
                            <input type="hidden" name="token" value="{{ $userinfo->token }}">
                            @if (\Session::has('error'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{!! \Session::get('error') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <ul>
                                        <li>{!! \Session::get('success') !!}</li>
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group {{ $errors->has('email') ? 'is-invalid' :'' }}">
{{--                                {!! Form::email('email', null, array('id' => 'email', 'class' => 'mdl-textfield__input', 'placeholder'=>'Enter Email Address' , 'required'=>'required' )) !!}                                 --}}
                                {!! Form::email('email', $userinfo->email, array('readonly'=>'readonly','id' => 'email', 'class' => 'mdl-textfield__input', )) !!}
                                <span class="text-danger">@if ($errors->has('email')){{{ $errors->first('email') }}} @endif</span>

                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'is-invalid' :'' }}">
                                {!! Form::password('password', array('id' => 'userpass', 'class' => 'mdl-textfield__input' ,'placeholder' => 'New Password')) !!}
                                <span class="text-danger">@if ($errors->has('password')){{{ $errors->first('password') }}} @endif</span>
{{--                                @if ($errors->has('password'))--}}
{{--                                <span class="text-danger">{{ trans('auth.pwLoginError') }}</span>--}}
{{--                                @endif--}}
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'is-invalid' :'' }}">
                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'mdl-textfield__input','placeholder' => 'Confirm Password')) !!}
                                <span class="text-danger">@if ($errors->has('password_confirmation')){{{ $errors->first('password_confirmation') }}} @endif</span>
{{--                                @if ($errors->has('password_confirmation'))--}}
{{--                                <span class="text-danger">{{ trans('auth.pwLoginError') }}</span>--}}
{{--                                @endif--}}
                            </div>

                            @if(config('settings.reCaptchStatus'))
                                <div class="g-recaptcha" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>
                            @endif

                            {!! Form::button('<span class="mdl-spinner-text">'.trans('auth.resetPassword').'</span><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--primary mdl-color-text--white mdl-button--raised full-span margin-bottom-1 margin-top-2','type' => 'submit','id' => 'submit')) !!}


                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

@endsection

@section('footer_scripts')
    {!! HTML::script('https://www.google.com/recaptcha/api.js', array('type' => 'text/javascript')) !!}
@endsection