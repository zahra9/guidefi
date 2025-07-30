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
                        {!! Form::open(array('url' => '/emailverfication', 'method' => 'POST', 'class' => 'auth-form', 'id' => 'reset')) !!}
                        {{ csrf_field() }}
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
                            {!! Form::email('email', null, array('id' => 'email', 'class' => 'mdl-textfield__input', 'placeholder'=>'Enter Email Address' , 'required'=>'required' )) !!}
                            <span class="mdl-textfield__error">@if ($errors->has('email')){{{ $errors->first('email') }}} @endif</span>
                        </div>

                        @if(config('settings.reCaptchStatus'))
                        <div class="g-recaptcha" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>
                        @endif

                        {!! Form::button('<span class="mdl-spinner-text">'.trans('auth.sendResetLink').'</span>
                        <div class="mdl-spinner w-100 mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'w-100 mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--primary mdl-color-text--white mdl-button--raised full-span margin-bottom-1 margin-top-2','type' => 'submit','id' => 'submit')) !!}

                        {!! Form::close() !!}

                        <a class="form-control btn btn-primary mt-4 p-3">Back to login</a>
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