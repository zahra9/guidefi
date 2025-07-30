<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BlogEtcPost Blog Admin - {{ config('app.name') }}</title>
    <script src="{{asset('/vendors/js/vendor.bundle.base.js')}}"></script>
   
    <script>
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            //>=, not <=
            if (scroll >= 30) {
                //clearHeader, not clearheader - caps H
                $(".scroll").addClass("fixed-scroll");
            } else {
                $(".scroll").removeClass("fixed-scroll");
            }
        });

        
    </script>
    <style>
        .alert-success{
            background-color: #81daa7 !important;
        }
    </style>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <link href="{{ asset('/vendors/ti-icons/css/themify-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.4.1/css/all.min.css">
    <!-- Styles -->
    @if(file_exists(public_path('blogetc_admin_css.css')))
    <link href="{{ asset('blogetc_admin_css.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-blog-header.css') }}" rel="stylesheet">

    @else
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{--Edited your css/app.css file? Uncomment these lines to use plain bootstrap:--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">--}}
    @endif
</head>

<body>
    <div id="app">
        <nav class="navbar p-0 d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="ti-align-left"></span>
                </button>
                <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="#"><img src="{{asset('assets/images/logo-01.png')}}" width="15%" alt="logo" /></a>
                    <a class="navbar-brand brand-logo-mini" href="#"><img src="{{asset('assets/images/logo-01.png')}}" width="15%" alt="logo" /></a>
                </div>
                <ul class="navbar-nav navbar-nav-right ml-auto">
                   <!-- <li class="nav-item dropdown">
                        <a class="nav-link count-indicator  d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="ti-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-gradient-success">
                                        <i class="ti-info mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-gradient-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Private message
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-gradient-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        2 days ago
                                    </p>
                                </div>
                            </a>
                        </div>
                    </li>-->
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="https://via.placeholder.com/30x30" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a href="{{url('/setting-list')}}" class="dropdown-item">
                                <i class="ti-settings text-primary pr-2"></i>
                                Settings
                            </a>
                            <a href="{{url('/logout')}}" class="dropdown-item">
                                <i class="ti-new-window text-primary pr-2"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="ti-menu"></span>
                </button>
            </div>
        </nav>

        <main class=" blogAdmin" style="height: 100vh;">

            <div class="container-fluid  blogAdmin">
                <div class="row">
                    <div class="col-md-3 scroll">
                        @include("blogetc_admin::layouts.sidebar")
                    </div>
                    <div class="col-md-9 pl-0" style="margin-left: -40px;">


                        @if (isset($errors) && count($errors))
                        <div class="alert alert-danger">
                            <b>Sorry, but there was an error:</b>
                            <ul class="m-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {{--REPLACING THIS FILE WITH YOUR OWN LAYOUT FILE? Don't forget to include the following section!--}}
                        @if(\WebDevEtc\BlogEtc\Helpers::hasFlashedMessage())
                        <div class="alert alert-success">
                            <h3>{{\WebDevEtc\BlogEtc\Helpers::pullFlashedMessage() }}</h3>
                        </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- <div class="text-center pt-5 pt-5 pb-3 footer-color text-muted">
<small><a href="https://webdevetc.com/">Laravel Blog Package provided by Webdevetc</a></small>
</div> -->


    @if( config("blogetc.use_wysiwyg") && config("blogetc.echo_html") && (in_array( Request::route()->getName() ,[ 'blogetc.admin.create_post' , 'blogetc.admin.edit_post' ])))
    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js" integrity="sha256-wURXWeMdyFKDl3v/rGzRT42o2lslbozA3ppL/M7ZVGI=" crossorigin="anonymous"></script>

    <script>
        if (typeof(CKEDITOR) !== 'undefined') {
            CKEDITOR.replace('post_body');
        }

        //   $(window).scroll(function() {
        //     // var header = $('.horizontal-menu');
        //     var container = $('.scroll');
        //     if ($(window).scrollTop() >= 30) {
        //     //   $(header).addClass('fixed-on-scroll');
        //       $(container).addClass('fixed-container');
        //     } else {
        //     //   $(header).removeClass('fixed-on-scroll');
        //       $(container).removeClass('fixed-container');
        //     }

        // });
    </script>
    @endif
</body>

</html>