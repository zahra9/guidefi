@extends('layouts.newpassword')

@section('template_title')
Client Account Activated
@endsection

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="mdl-layout mdl-js-layout mdl-color--grey-100 mdl-auth-form">
    <main class="mdl-layout__content_auth">
        <div class="mdl-card mdl-shadow--2dp">
            <div class="mdl-card__title mdl-color--cyan mdl-color-text--white">
                <h2 class="mdl-card__title-text text-center full-span block">

                    <img src="{{asset('assets/images/image-1.png')}}" width="100%" alt="">

                </h2>
            </div>

            <div>
                <div class="succussmsg">
                    @if(session('success'))
                        {{session('success')}}
                    @endif
                </div>
            </div>
            <div class="show-client-footer">
                <div style="display:flex;float:right">
                    <h5 style="margin: 0px;color:#fff;">GUIDEFI</h5>
                    <p style="margin-left: 20px;margin-right:33px;"><a href="#" style="color: #fff;">info@guidefi.com</a></p>
                </div>
                <div>
                    <p style="font-size: 10px;line-height:10px;color:#fff;display:inline-block;float:right;">500 S. Grand Avenue, Suite 1800 <br> Los Angeles,CA 90071.</p>
                </div>
                <div style="margin-left:10px;margin-top:13%;">
                    <a href="https://www.instagram.com/guidefi" target="_blank" style="text-decoration: none;"><i style="color: #FFFFFF;" class="fa fa-instagram" aria-hidden="true"></i></a>
                    <a href="http://www.facebook.com/guidefi" target="_blank"><i style="color: #FFFFFF;" class="fa fa-facebook-square" aria-hidden="true"></i></a>
                    <a href="http://www.twitter.com/getguidefi" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true" style="color: #FFFFFF;"></i></a>
                    <a href="http://www.linkedin.com/in/guidefi" style="text-decoration: none;" target="_blank"><i style="color: #FFFFFF;" class="fa fa-linkedin-square" aria-hidden="true"></i></a>
                </div>
            </div>


            {{-- <div class="mdl-card__supporting-text" style="text-align: center; margin-top: -10px;"><a style="width: 180px;" class="mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--cyan mdl-color-text--white mdl-button--raised " href="com.guidefi.app://launch" target="_blank"><strong>Click here to open app</strong></a></div>--}}
        </div>
    </main>
</div>

@endsection

@section('footer_scripts')
{!! HTML::script('https://www.google.com/recaptcha/api.js', array('type' => 'text/javascript')) !!}
@endsection