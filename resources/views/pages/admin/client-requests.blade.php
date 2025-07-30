@extends('layouts.app')

@section('linked_css')

@endsection
@section('content')

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title font-weight-bold">Client Requests ({{ $client_requests->count() }})</h4>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date Of Birth</th>
                                    <th>Gender</th>
                                    <th>Race</th>
                                    <th>Submission Date</th>

                                    <!--                                    <th>Zip Code</th>-->
                                    <th>Email Validation</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if($client_requests->count())
                                @foreach($client_requests as $request)
                                <tr class="text-black">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ !is_null($request->user) && !is_null($request->user->person) ? $request->user->person->first_name : '-- -- --' }}</td>
                                    <td>{{ !is_null($request->user) && !is_null($request->user->person) ? $request->user->person->last_name : '-- -- --' }}</td>
                                    <td>{{ !is_null($request->user)?$request->user->person->email:'-- -- --' }}</td>
                                    <td>{{ !is_null($request->user)?$request->user->person->phone:'-- -- --' }}</td>
                                    <td>{{ !is_null($request->user) && !is_null($request->user->person) ?
                                            \Carbon\Carbon::parse($request->user->person->birth_date)->format('M-d-Y'): '-- -- --'}}</td>

                                    <td>{{ !is_null($request->user) && !is_null($request->user->person) ? $request->user->person->gender: '-- -- --'}}</td>
                                    <td>{{ !is_null($request->user) && !is_null($request->user->person) ? $request->user->person->race: '-- -- --'}}</td>

                                    <td>{{ !is_null($request->user) && !is_null($request->user->person)? \Carbon\Carbon::parse($request->user->person->created_at)->format('M-d-Y'): '-- -- --'}}</td>

                                {{-- <td>{{ !is_null($request->user) && !is_null($request->user->person) ? $request->user->person->state: '-- -- --'}}</td>--}}
<!--                                    <td>{{ !is_null($request->user)?$request->user->person->zipcode: '-- -- --' }}</td>-->
                                    {{-- <td>{{  $request->description }}</td>--}}
                                    <td>
                                        {{($request->user->activated==1)?"Activated":"Pending"}}
                                    </td>
                                    <td>
                                        <select class="form-control client_request_status{{ !is_null($request->id) ? $request->id : '' }}" name="status" data-id="{{$request->id}}">
                                            <option value="">Select</option>
                                            @foreach($status as $row)
                                            @if($request->status == $row['value'])
                                            <option selected="selected" data-val="{{ $row['value']}}">{{$row['name']}}</option>
                                            @else
                                            <option data-id="{{$request->id}}" data-val="{{ $row['value'] }}">{{$row['name']}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><button class="btn {{($request->user->status =='declined')?'btn-danger':'btn-success'}} client_status_button client_status_button{{ !is_null($request->id) ? $request->id : '' }}" data-id="{{ !is_null($request->id) ? $request->id : '' }}"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                    
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
<script type="text/javascript">
    $(document).on("click", ".client_status_button", function() {
        var $id = $(this).data('id');
        var $selected = $('.client_request_status' + $id).find('option:selected');
        data = {
            '_token': '{{csrf_token()}}',
            'status': $selected.data('val')
        }
        $.ajax({
            type: 'GET',
            url: "{{ url('update_client_status') }}" + '/' + $id,
            data: data,
            beforeSend: function() {
                $('.client_status_button' + $id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            },
            success: function(response) {
                $('.client_status_button' + $id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);

                if (response.code == 1) {
                    toastr.clear();
                    toastr.success(response.message, 'Success', {
                        timeOut: 5000
                    });

                    if(response.status =='declined') {
                        $('.client_status_button' + $id).addClass('btn-danger').removeClass('btn-success');
                    }else{
                        $('.client_status_button' + $id).addClass('btn-success').removeClass('btn-danger');

                    }
                    if(response.status =='active'){
                        location.reload();
                    }

                }
            }
        });
    });
</script>
@endsection