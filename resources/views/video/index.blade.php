@extends('layouts.app')

@section('linked_css')
    <link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
    <link rel="stylesheet" src="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <!--                <button type="button" class="btn video btn-info btn-lg" >Add New</button>-->
                {{ link_to_route('videos.create', 'Add New', [], ['class' => 'btn Addvideo btn-info btn-lg']) }}
                <div>

                    <h4 class="card-title font-weight-bold">Videos</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Title</th>
                                    <th>Video</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if($videos->count())
                                    @foreach($videos as $video)
                                        <tr class="text-black video-index{{ !is_null($video->id) ? $video->id : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ !is_null($video->title) ?$video->title:'' }}</td>
                                            <td>
                                                @if(!is_null($video->url))
                                                    <a href="{{ $video->url}}" target="_blank"><img
                                                                src="{{ $video->thumbnail}}"></a>
                                                @endif
                                            </td>

                                            <td>{{ !is_null($video->user) ?$video->user->name:'' }}</td>

                                            <td>{{ !is_null($video) ? \Carbon\Carbon::parse($video->created_at)->format('M-d-Y'): '-- -- --'}}</td>

                                            <td>
                                                <select class="form-control video_status{{ !is_null($video->id) ? $video->id : '' }}"
                                                        name="status">
                                                    <option value="">Select</option>
                                                    @foreach($privacyStatus as $status)
                                                        @if($video->admin_status == $status)
                                                            <option data-id="{{$video->id}}" selected="selected"
                                                                    data-val="{{$status}}">{{$status}}</option>
                                                        @else
                                                            <option data-id="{{$video->id}}"
                                                                    data-val="{{ $status}}">{{$status}}</option>
                                                        @endif
                                                    @endforeach

                                                </select>

                                            </td>
                                            <td>
                                                <button id="admin-status"
                                                        class="btn {{($video->admin_status == 'approved')?'btn-success':'btn-danger'}} video_status_button video_status_button{{ !is_null($video->id) ? $video->id : '' }}"
                                                        data-id="{{ !is_null($video->id) ? $video->id : '' }}">
                                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                </button>
                                                <a title="Delete" style="cursor: pointer;color:#FE0957;font-size: 17px;"
                                                   class="DeleteVideo  DeleteVideo{{ !is_null($video->id) ? $video->id : '' }}"
                                                   data-id="{{ !is_null($video->id) ? $video->id : '' }}">
                                                    <i class="ti-trash">
                                                    </i>
                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('js/nprogress.js')}}"></script>
    @include('scripts.status-change')
    <script>
        $(document).on("click", ".DeleteVideo", function () {
            if (confirm('Are you sure you want to delete?')) {
                var id = $(this).data('id');
                var url = '{!! url('videos')!!}' + "/" + id;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    dataType: 'json',
                    data: {
                        id: id,
                        _method: 'DELETE'
                    },
                    beforeSend: function () {
                        $('.DeleteVideo' + id).html('<i class="fa fa-cog" aria-hidden="true"></i>');
                    },
                    success: function (response) {
                        if (response.status) {
                            $('.video-index' + id).remove();
                        }
                    },
                    error:function (response){
                       console.log('error');
                       console.log(response);
                    },

                });
            }
        });

    </script>


@endsection