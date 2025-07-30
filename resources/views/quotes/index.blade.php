@extends('layouts.app')

@section('linked_css')
    <link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    @endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                {{ link_to_route('quotes.create', 'Add New', [], ['class' => 'btn video btn-info btn-lg']) }}

                    <h4 class="card-title font-weight-bold">Quotes</h4>
                    {{--                    <button type="button" class="btn video btn-info btn-lg" >Add New</button>--}}

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if($quotes->count())
                                    @foreach($quotes as $quote)
                                        <tr class="text-black video-index{{ !is_null($quote->id) ? $quote->id : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $quote->title }}</td>
                                            <td style="white-space: inherit;">
                                            {{$quote->description}}
                                            </td>
                                            <td>{{ !is_null($quote->user) ?$quote->user->name:'' }}</td>

                                            <td>{{ !is_null($quote) ? \Carbon\Carbon::parse($quote->created_at)->format('M-d-Y'): '-- -- --'}}</td>

                                            <td>

                                            <select class="form-control quote_status{{ !is_null($quote->id) ? $quote->id : '' }}" name="status">
                                                    <option value="">Select</option>
                                                @foreach($privacyStatus as $status)
                                                @if($quote->admin_status == $status)
                                                <option data-id="{{$quote->id}}" selected="selected" data-val="{{$status}}">{{$status}}</option>
                                                @else
                                                <option data-id="{{$quote->id}}"  data-val="{{ $status}}">{{$status}}</option>
                                                @endif
                                                @endforeach

                                                </select>
                                        </td>
                                            <td>
                                                <button id="admin-status" class="btn {{($quote->admin_status == 'approved')?'btn-success':'btn-danger'}} quote_status_button quote_status_button{{ !is_null($quote->id) ? $quote->id : '' }}"
                                                        data-id="{{ !is_null($quote->id) ? $quote->id : '' }}">
                                                    <i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                                <a href="{{ route('quotes.edit', $quote->id) }}" style="cursor: pointer;color:#FE0957;font-size: 17px;" ><i class="fas fa-edit"></i> </a>
                                                <a title="Delete" style="cursor: pointer;color:#FE0957;font-size: 17px;" class="DeleteQuote  DeleteQuote{{ !is_null($quote->id) ? $quote->id : '' }}" data-id="{{ !is_null($quote->id) ? $quote->id : '' }}"><i class="ti-trash"></i></a>

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
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

        $(document).on("click", ".DeleteQuote", function() {
            if (confirm('Are you sure you want to delete?')) {
                var id = $(this).data('id');
                var url = '{!! url('quotes')!!}'+"/"+id;
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
                    beforeSend: function() {
                        $('.DeleteQuote' + id).html('<i class="fas fa-cog fa-spin" aria-hidden="true"></i>');
                    },
                    success: function(response) {
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

    <script>
        $(function() {
            $('.toggle-event').change(function() {
                var $status = $(this).prop('checked');
                var $id = $(this).data('id');
                data = {
                    '_token':'{{csrf_token()}}',
                    'status': $status
                }
                $.ajax({
                    type:'GET',
                    url:"{{ url('update_quote_status') }}"+'/'+$id,
                    data:data,
                    success:function(response) {
                        if(response.code == 1){
                            toastr.clear();
                            toastr.success(response.message, 'Error', {timeOut: 5000});
                        }
                    }
                });
            })
        })
        
    </script>
<script>
    $(document).on("click", ".quote_status_button", function(){
        var $id = $(this).data('id');
        var $selected = $('.quote_status'+$id).find('option:selected');
        var data = {
            '_token':'{{csrf_token()}}',
            'status': $selected.data('val')
        };
        $.ajax({
            type:'POST',
            url:"{{ url('update_quote_status') }}"+'/'+$id,
            data:data,
            beforeSend: function() {
                $('.quote_status_button'+$id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            },
            success:function(response)
            {
                if(response.code == 1){
                    $('.quote_status_button'+$id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);
                    if(response.status == 'approved'){
                        $('.quote_status_button'+$id).removeClass('btn-danger');
                        $('.quote_status_button'+$id).addClass('btn-success');
                    }else{
                        $('.quote_status_button'+$id).removeClass('btn-success');
                        $('.quote_status_button'+$id).addClass('btn-danger');
                    }
                }else{

                }
            }
        });
    });
</script>
@endsection