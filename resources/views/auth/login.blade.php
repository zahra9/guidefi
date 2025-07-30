{{-- @extends('layouts.auth')--}}
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
                        <h4 class="login">Log In</h4>
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{!! \Session::get('error') !!}</li>
                                </ul>
                            </div>
                        @endif
                            {!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'pt-3', 'id' => 'login', 'role' => 'form']) !!}
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('email') ? 'is-invalid' :'' }}">
                            @if ($errors->has('email'))
                                    <p class="error alert alert-danger" >{{ trans('auth.emailLoginError') }}</p>
                                @endif
                                <input type="email" class="form-control mail form-control-lg" id="exampleInputEmail1" placeholder="Username" name="email">
                               
                            </div>
                            <div class="form-group  {{ $errors->has('password') ? 'is-invalid' :'' }}">
                                <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password">
                                @if ($errors->has('password'))
                                    <span class="error">{{ trans('auth.pwLoginError') }}</span>
                                @endif
                            </div>

                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    {{--<label class="form-check-label text-muted" style="color:black !important;">
                                        Keep me signed in

                                        {!! Form::checkbox('remember', 'remember', null, ['id' => 'remember', 'class' => 'form-check-input', old('remember') ? 'checked' : '']); !!}

                                    </label>--}}
                                </div>
                                <a href="/resetpassword" class="auth-link" style="color:#04b4f0;">Forgot password?</a>
                            </div>
                            <div class="mt-3">
                                {!! Form::button('<span class="mdl-spinner-text">'.trans('auth.login').'</span><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'btn btn-block btn-lg login-button font-weight-medium auth-form-btn','type' => 'submit','id' => 'submit','style' => "background:#04b4f0;")) !!}

                            </div>
                            <div class="mt-3">
{{--                                <p style="color: #296a81;font-size: 14px; text-align: center;">Already have an account? <a href="" style="color: #04b4f0;"> Register</a></p>--}}
                            </div>
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