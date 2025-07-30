@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title font-weight-bold">Advisor Applications  ({{ $advisor_requests->count() }}) </h4>
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
                                    <th>Age</th>
                                    <th>Race</th>
                                    <th>Zip Code</th>
                                    <th>Submission Date</th>
                                    <th>Email Validation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($advisor_requests as $key => $advisor)
                                    @if(!is_null($advisor->user) && !is_null($advisor->user->person))
                                        <tr class="text-black">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->first_name : '-- -- --' }}</td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->last_name:'-- -- --'}}</td>
                                            <td>{{ !is_null($advisor->user) ? $advisor->user->email: '-- -- --'}}</td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->phone: '-- -- --'}}</td>
                                            <td>
                                                {{ !is_null($advisor->user) && !is_null($advisor->user->person) ? \Carbon\Carbon::parse($advisor->user->person->birth_date)->format('M-d-Y'): '-- -- --'}}
                                            </td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->gender: '-- -- --'}}</td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->age: '-- -- --'}}</td>
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->race: '-- -- --'}}</td>
{{--                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->state: '-- -- --'}}</td>--}}
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->zipcode: '-- -- --'}}</td>
{{--                                            <td>{{ !is_null($advisor->created_at) ? Carbon\Carbon::parse($advisor->created_at)->format('d-M-Y') : '-- -- --' }}</td>--}}
                                            <td>{{ !is_null($advisor->user) && !is_null($advisor->user->person) ? \Carbon\Carbon::parse($advisor->user->person->created_at)->format('M-d-Y'): '-- -- --'}}</td>
                                            <td>
                                                {{($advisor->user->activated==1)?"Activated":"Pending"}}
                                            </td>

                                            <td>
                                                <select class="form-control advisor_request_status advisor_request_status{{ !is_null($advisor->id) ? $advisor->id : '' }}" name="status" data-id="{{$advisor->id}}">
                                                    <option value="">Select</option>
                                                    @foreach($status as $row)
                                                        @if($advisor->status == $row['value'])
                                                            <option selected="selected" data-val="{{ $row['value']}}">{{$row['name']}}</option>
                                                        @else
                                                            <option data-id="{{$advisor->id}}"  data-val="{{ $row['value'] }}">{{$row['name']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><button class="btn {{($advisor->user->status =='declined')?'btn-danger':'btn-success'}} advisor_status_button advisor_status_button{{ !is_null($advisor->id) ? $advisor->id : '' }}" data-id="{{ !is_null($advisor->id) ? $advisor->id : '' }}"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                        </tr>
                                    @endif
                                @endforeach
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
          $(document).on("click", ".advisor_status_button", function(){
              var $id = $(this).data('id');
              if($id !=='') {
                  var $selected = $('.advisor_request_status' + $id).find('option:selected');
                  data = {
                      '_token': '{{csrf_token()}}',
                      'status': $selected.data('val')
                  }
                  $.ajax({
                      type: 'GET',
                      url: "{{ url('update_advisor_status') }}" + '/' + $id,
                      data: data,
                      beforeSend: function () {
                          $('.advisor_status_button' + $id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

                      },
                      success: function (response) {
                          $('.advisor_status_button' + $id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);
                          if (response.code == 1) {
                              toastr.clear();
                              toastr.success(response.message, 'Success', {timeOut: 5000});
                              if(response.status =='declined') {
                                  $('.advisor_status_button' + $id).addClass('btn-danger').removeClass('btn-success');
                              }else{
                                  $('.advisor_status_button' + $id).addClass('btn-success').removeClass('btn-danger');

                              }
                              if(response.status =='active'){
                                  location.reload();
                              }
                          }
                      }
                  });
              }
        });
</script>
@endsection