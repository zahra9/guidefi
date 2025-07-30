@extends('layouts.app')

@section('linked_css')

    <link href="{{asset('upload-progressbar/uploadfile.css')}}" rel="stylesheet">
   <style>
   .ajax-upload-dragdrop{
       width: 450px !important;
   }
   .ajax-file-upload-statusbar{
       width: 450px !important;
       margin-left: 0px !important;
   }
   .ajax-upload-dragdrop span b {
       font-size: .8125rem;
       color: #000 !important;
       font-weight: initial;
   }
       #eventsmessage{
           color: #000 !important;
       }
       .ajax-file-upload-filename{
           color: #000;
       }
   </style>
    {{--<link rel="stylesheet" type="text/css" href="css/sheet.css">--}}
    {{--<link rel="stylesheet" href="css/bootstrap.min.css">--}}

    {{--<link rel="icon" href="../../images/Benchmark-fevicon.png" type="image/gif" sizes="16x16">--}}

    @endsection
@section('content')
    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title font-weight-bold">Create Video</h4>
                <div class="col-12" style="background: #fff">
                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row pt-4">
                            <div class="col-md-6">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class=" {{ $errors->has('title') ? 'is-invalid' :'' }}">
                                        <label for="">Title</label>
                                        {!! Form::text('title', old('title', null), ['class' => 'mdl-textfield__input title-input', 'required' => 'required','placeholder'=>'Title','maxlength'=>'50']) !!}
                                    </div>
                                </div>
                            </div>

                            {{--<div class="col-md-6">--}}
                            {{--<div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">--}}
                            {{--<label for="">Upload Video</label>--}}
                            {{--<input name="video" type="file" class="mdl-textfield__input" required><br/>--}}
                            {{--</div>--}}
                            {{--</div>--}}


                        </div>

                        {{--<div class="pt-3 pl-2">--}}
                            {{--{{ Form::submit('Save', ['class' => 'btn btn-success']) }}--}}

                        {{--</div>--}}
                        {{ csrf_field() }}
                    </form>

                <section class="detail" >
                    <br/>
                    {{--<h1 style="color: #800000;line-height: 25px;margin-top: 10px;" >Upload File</h1>--}}

                    <!--        <div class="row">-->
                    <!--    <body>-->
                    <div id="fileuploader" class="ml-2">Upload</div>

                    {{ link_to_route('videos.index', 'Cancel', [], ['class' => 'btn btn-primary']) }}
                    <button class="btn btn-success" id="extrabutton">Start Upload</button>
                    <div id="eventsmessage"></div>
                    <!--        </div>-->
                    <br/>
                    <h4 class="submit-text" style="color: #800000;line-height: 25px;margin-top: 10px;padding-bottom:5%;display:none;">Click the below button for submittion</h4>
                    <button onclick="myFunction()"style="background-color: #800000;display:none;
            padding: 4px 15px;
            border-radius: 3px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            margin-left: 0px;
            transition: all 200ms;
            letter-spacing: 1px;color:white;" class="submit-button">Submit Order</button>
                    <div id="submit-message"></div>

                    <!-- <a style="background-color: #800000;display:none;
                         padding: 4px 15px;
                         border-radius: 3px;
                         color: #fff;
                         text-decoration: none;
                         font-size: 13px;
                         margin-left: 0px;
                         transition: all 200ms;
                         letter-spacing: 1px;color:white;" href="submit-job.php?detail=0a8e1d01-a43b-eb11-bf6f-00224801cd21" target="_self" class="submit-button">Submit Order</a>
             -->
                </section>
                </div>
                <div class="row mb-5 mr-3 ml-3 pb-4 " style="background-color: white;color: black;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{asset('upload-progressbar/jquery.uploadfile.min.js')}}"></script>
    <script>
        //  $(document).ready(function(){
        function myFunction($){
//   alert('****');
            jQuery.ajax({
                url: '{{ route('videos.store') }}',//'submit-job.php?detail='+'0a8e1d01-a43b-eb11-bf6f-00224801cd21',
                /*  data: {
                    format: 'json'
                  },*/
                error: function() {
                    alert('error');
                    // $('#info').html('<p>An error has occurred</p>');
                },
//   dataType: 'jsonp',
                success: function(data) {
                    alert(data);
                    if(data =="Accepted"){
                        location.assign('main');

                        window.location.href("main");
                    }else{
                        jQuery("#submit-message").html(data);

                    }
                },
                type: 'POST'
            });

        }
        //   });

    </script>
    <script>

        $(document).ready(function()

        {


            var extraObj = $("#fileuploader").uploadFile(
               /* $.ajaxSetup({
                    headers: {
                        'X-XSRF-TOKEN': $.cookie("XSRF-TOKEN")
                    }
                }),*/
                {
                   /* formData: [{
                        name: 'X-CSRF-TOKEN',
                        value: getAntiCSRFToken()
                    }],*/
                // var extraObj = $("#extraupload").uploadFile({
                // url:"upload.php",
                // fileName:"myfile",
                url:'{{ route('videos.store') }}',//"upload.php",
                fileName:"video",
                // multiple:true,
                // maxFileCount:1,
                dragDrop:true,
                showPreview:true,
                previewHeight: "100px",
                previewWidth: "100px",
                // showDownload:true,
                statusBarWidth:600,
                dragdropWidth:600,
                dataType    : 'json',
                encode          : true,
                dynamicFormData: function()
                {
                    var data ={ title:$('.title-input').val(),  '_token':'{{csrf_token()}}'}
                    return data;
                },
                onSuccess:function(files,data,xhr,pd)
                {
                    console.log('******');
                    console.log(data.status);
                    console.log(data.message);


                    $("#eventsmessage").html($("#eventsmessage").html()+"<br/> "+data.message);
                    console.log(xhr.responseText === "Thank you. Your job has been uploaded successfully. Please check status in few minutes.");

                    /*	setTimeout(function(){ window.location.assign("main"); }, 3000);*/
                },

                autoSubmit:false
            });
            $("#extrabutton").click(function()
            {

                extraObj.startUpload();
            });

        })
    </script>
    @endsection
