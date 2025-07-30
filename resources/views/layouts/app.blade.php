<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- base:css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('title')))@yield('title')
        | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
    <meta name="description" content="">
    <meta name="author" content="Abdullah">
    <link rel="shortcut icon" href="/favicon.ico">


    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


    <![endif]-->

    <link href="{{ asset('/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendors/ti-icons/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendors/css/vendor.bundle.base.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.4.1/css/all.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdl-themes/material.min.css') }}" id="user_theme_link">


    {{-- Fonts --}}
    @yield('fonts')

    {{-- MDL CSS Library --}}
  

    <link rel="stylesheet" type="text/css" href="{{ asset('css/mdl-themes/material.min.css') }}"
              id="user_theme_link">



    @yield('linked_css')
   

    <style type="text/css">
        @yield('template_fastload_css')

                .user-avatar-nav {
            background: url({{ Gravatar::get(Auth::check() ? Auth::user()->email : 'user@gmail.com') }}) 50% 50% no-repeat;
            background-size: auto 100%;
        }

    </style>

    {{-- Scripts --}}
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png')}}"/>

</head>
@yield('head')

<body>
<div id="app" class="container-scroller">

    <div class="container-fluid page-body-wrapper">
@if(Auth::check())

    @include('partials.sidebar_skins')

    <!-- partial:partials/_navbar.html -->
    @include('partials.nav')

    @include('partials.navHead')
@endif
    <!-- partial -->

        <div class="main-panel">

            @yield('content')

            @include('partials.footer')

        </div>
        <!-- content-wrapper ends -->

    </div>


</div>

<script src="{{asset('/vendors/js/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="{{asset('/js/off-canvas.js')}}"></script>
<script src="{{asset('/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('/js/template.js')}}"></script>
<script src="{{asset('/js/settings.js')}}"></script>
<script src="{{asset('/js/todolist.js')}}"></script>
<script src="{{asset('/vendors/chart.js/Chart.js')}}"></script>
<script src="{{asset('/js/dashboard2.js')}}"></script>
<script src="{{asset('/vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('/js/data-table.js')}}"></script>
<script src="{{asset('/js/popper.js')}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
             $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        var container = $('.container-scroller');
        if ($(window).scrollTop() >= 30) {
          $(header).addClass('fixed-on-scroll');
          $(container).addClass('fixed-container');
        } else {
          $(header).removeClass('fixed-on-scroll');
          $(container).removeClass('fixed-container');
        }
      }
    });


            </script>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/mdl.js') }}"></script>
@yield('scripts')

</body>
</html>