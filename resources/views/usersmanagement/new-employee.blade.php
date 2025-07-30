<!DOCTYPE html>
<html>
<head>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>


    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet"
          href="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/css/intlTelInput.css"/>


    <script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>

    <script>
        $("#phone").intlTelInput();
        $(document).ready(function () {
            $("#registerform").validate();
        });

    </script>
    <style type="text/css">
        label.error {
            color: red;
            font-size: 1rem;
            display: block;
            margin-top: 5px;
        }

        footer > a {
            color: #0f0f0f !important;
        }

        input.error, textarea.error {
            border: 1px dashed red;
            font-weight: 300;
            color: red;
        }

        .panel-title {
            display: inline;
            font-weight: bold;
        }

        .display-table {
            display: table;
        }

        .display-tr {
            display: table-row;
        }

        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }

        .navbar-right .dropdown-menu {
            right: inherit !important;
        }

        .navbar-right li a {
            border-right: solid 1px #2b2b29;
            padding-top: 0px;
            margin-top: 10px;
            padding-bottom: 0px;
        }

        .navbar-right li:nth-child(7) a {
            border: 0;
        }

        .navbar-right li a:hover {
            background-color: #000;
        }

        .nav-item a {
            border: none !important;


        }

        .navigation {
            background-color: #44DBBD;
            padding: 20px 0px;
            height: 100px;
        }

        .bs-example-navbar-collapse-1, ul .nav-item {
            background-color: #fff;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin: 6px 5px;
        }

        .bs-example-navbar-collapse-1, ul .nav-item a {
            margin-top: -6px;
            margin-left: -5px;

        }

        i.fa {
            line-height: 3;
        }

        .border-r {
            /*border-right: 1px solid black !important;*/

        }

        footer {
            background-color: #ECECEC;
            letter-spacing: 0.2em;
            font-size: 12px;
            color: black;
        }

        footer .row {
            padding-top: 20px;
        }

        footer .broder_r {
            padding: 10px;
            border: solid;
        }

        footer a {
            color: #2B2B29;
            text-decoration: underline;
        }

        footer a:hover {
            color: grey;
            text-decoration: underline;
        }

        footer span {
            color: grey;
        }

        footer input {
            width: 100%;
            border: 1px solid;
        }

        footer input:hover {
            border: solid;
        }

        footer input:invalid {
            border-width: 3px;
            background-color: rgba(255, 255, 255, 1);
            border-style: solid;
            border-color: rgba(255, 64, 64, 1);
        }

        footer input:focus {
            border-width: 3px;
            background-color: rgba(255, 255, 255, 1);
            border-style: solid;
            border-color: rgba(43, 43, 41, 1);
        }

        footer .btn-outline-success {
            background-color: rgba(68, 219, 189, 1);
            border: solid transparent 2px;
            cursor: pointer !important;
            color: #fff;
            height: 23px;
            font-size: 12px;
            padding-top: 2px;
            margin-top: 10px;
            border-radius: unset;
        }

        footer .btn-outline-success:hover {
            background-color: white;
            border-color: #44DBBD;
            color: #44DBBD;
        }

        footer .fa {
            font-size: 20px;
        }

        footer .mar {
            margin-top: -50px;
        }

        footer .mar-bt {
            margin-top: -30px;
        }

        .about-to-contact li {
            list-style: none;
            margin-bottom: 25px !important;
        }

        .social-icons li {
            list-style: none;
        }

        footer form {
            padding-top: 40px !important;
        }

        footer p {
            color: grey;
        }

        form input {
            height: 30px;
            padding: 0 17px;
        }

        .mt-3 {
            margin-top: 10px;
        !important;
        }

        .mb-3 {
            margin-bottom: 15px !important;
        }

        .mt-4 {
            margin-bottom: 20px !important;
        }

        .fa-icons a {
            color: grey;
            margin: 10px;
        }

        .panel {
            margin-bottom: 80px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        panel-body {
            padding: 20px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>


<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12"><h2 class="font_2" style="margin:20px 0px 80px 0px;">Employee Application</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body">


                    @if (\Session::has('success'))
                        <div class="alert alert-success text-center">
                            <p> {!! \Session::get('success') !!}</p>

                        </div>
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger text-center">
                            <p> {!! \Session::get('error') !!}</p>

                        </div>
                    @endif

                        @if($isCorpEmail->is_corp_email==1)

                    {!! Form::open(array('id'=>'registerform','enctype'=>'multipart/form-data','action' => 'RestoreUserController@saveemployee', 'method' => 'POST', 'role' => 'form')) !!}
                    @else
                        {!! Form::open(array('id'=>'registerform','enctype'=>'multipart/form-data','action' => 'RestoreUserController@saveEmployeeWithoutDomain', 'method' => 'POST', 'role' => 'form')) !!}
                    @endif
                            @csrf

                    <div class='form-row row'>
                        <div class='col-xs-12 col-md-12 col-lg-12 form-group required {{ $errors->has('name') ? 'is-invalid' :'' }}'>
                            <label class='control-label'>Nick Name *</label>

                            @if (\Session::has('fullname'))

                                <input class='form-control' size='4' type='text' name="fullname" id="fullname" required
                                       autofocus value="{!! \Session::get('fullname') !!}" style="width: 200px;">
                            @else
                                <input class='form-control' size='4' type='text' name="fullname" id="fullname" required
                                       autofocus value="{{old('fullname') }}" style="width: 200px;">

                            @endif
                            @if($errors->has('fullname'))
                                <div class="error">{{ $errors->first('fullname') }}</div>
                            @endif
                        </div>
                        <div class='col-xs-12 col-md-12 col-lg-12 form-group required {{ $errors->has('email') ? 'is-invalid' :'' }}'>
                            <label class='control-label'>Email *</label>
                            @if (\Session::has('email'))

                                <input class='form-control' size='4' type='email' name="email" id="email" required
                                       value="{!! \Session::get('email') !!}" style="width: 200px;">
                            @else
                                <input class='form-control' size='4' type='email' name="email" id="email" required
                                       value="{{old('email') }}" style="width: 200px;">

                            @endif

                            <input class='form-control' size='4' type='hidden' name="created_by" id="created_by"
                                   value="{{$created_by}}">
                            @if($errors->has('email'))
                                <div class="error">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        @if($isCorpEmail->is_corp_email==0)
                            <div class='col-xs-12 col-md-12 col-lg-12 form-group required {{ $errors->has('sign_up_code') ? 'is-invalid' :'' }}'>

                                <label class='control-label'>Signup Code *</label>
                                <input class='form-control' type='text' name="sign_up_code" id="sign_up_code" required
                                       value="{{old('sign_up_code') }}" style="width: 200px;">
                                <input class='form-control' size='4' type='hidden' name="id" id="id"
                                       value="{{$isCorpEmail->id}}">
                            </div>
                        @endif
                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-md-4 col-lg-4 form-group "></div>
                        <div class="col-xs-12 col-md-4 col-lg-4 form-group">
                            <button class="btn btn-primary btn-lg btn-block" style="margin-top: 20px;" type="submit">
                                Save
                            </button>
                        </div>
                        <div class="col-xs-12 col-md-4 col-lg-4 form-group "></div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

</div>
<footer style="margin-top: 5%">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <ul class="about-to-contact">
                    <li><a href="https://www.ayanatherapy.com/contact-us">CONTACT</a></li>
                    <li><a href="https://www.ayanatherapy.com/about">FAQ</a></li>
                    <li><a href="https://www.ayanatherapy.com/privacy-policy">PRIVACY POLICY</a></li>
                    <li><a href="https://www.ayanatherapy.com/privacy-policy">TERMS OF USE</a></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="social-icons text-center">
                    <li class="broder_r"><a href="#">If you or a loved one are in a crisis, click here for a list of
                            resources</a></li>
                    <ul class="mt-4 fa-icons">
                        <a target="_blank" href="https://www.facebook.com/ayanatherapy/" class="nav-link"><i
                                    class="fa fa-facebook"></i></a>
                        <a target="_blank" href="https://twitter.com/AyanaTherapy" class="nav-link"><i
                                    class="fa fa-twitter"></i></a>
                        <a target="_blank" href="https://www.instagram.com/ayana_therapy/" class="nav-link"><i
                                    class="fa fa-instagram"></i></a>
                        <a target="_blank" href="https://www.linkedin.com/company/ayanatherapy/" class="nav-link"><i
                                    class="fa fa-linkedin"></i></a>
                    </ul>

                    <li class="mt-3 mb-3"><a style="color: grey;" href="mailto:info@ayanatherapy.com">info@ayanatherapy.com</a>
                    </li>
                    <li class="mt-4 mb-3"><span>500 S. Grand Avenue, Suite 1800<br>Los Angeles, CA 90071</span></li>
                    <span>© 2020 AYANA Therapy</span>

                </ul>
            </div>
            <div class="col-md-3">
                <p>Subscribe to our prelaunch list</p>
                <p>Receive updates of blogs, launch info, and more! </p>
                <form>
                    <input type="email" name="" placeholder="Enter Your Email Here*">
                    <input type="submit" class="btn btn-outline-success mt-2" name="" value="Subscribe Now">
                </form>

            </div>
        </div>
    </div>

</footer>

</body>


</html>