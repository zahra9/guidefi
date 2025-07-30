@extends('layouts.dashboard')

@section('template_title')
  Showing Corporate Clients
@endsection

@section('template_linked_css')
@endsection
@section('header')
    Showing All Corporate Clients

@endsection

@section('breadcrumbs')
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="{{url('/')}}">
            <span itemprop="name">
                {{ trans('titles.app') }}
            </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="1" />
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/corporateusers" disabled>
            <span itemprop="name">
            Corporate Clients List
            </span>
        </a>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')

<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h2 class="mdl-card__title-text logo-style">

            @if (sizeof($users) === 1)
                {{ sizeof($users) }} Client total
            @elseif (sizeof($users) > 1)
                {{ sizeof($users) }} Total Clients
            @else
                No Corporate Client

            @endif
        </h2>

    </div>
    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
        <div class="table-responsive material-table">
            <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
              <thead>
                <tr>

                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                    <th class="mdl-data-table__cell--non-numeric">Company Name</th>
                    {{-- <th class="mdl-data-table__cell--non-numeric">Company Phone</th>--}}
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Contact Person Name</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Contact Person Phone</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Password</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Invitation Link</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Payment Method</th>
                    <th class="mdl-data-table__cell--non-numeric no-sort no-search">Blocked</th>
                    <th class="mdl-data-table__cell--non-numeric no-sort no-search">View</th>
                </tr>
              </thead>
              <tbody>
              @php $i=0 @endphp
                    @foreach ($users as $user)
                        @if($user->corporateclient)

                        <tr>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('corporateusers/' . $user->id) }}">{{$i+1}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('corporateusers/' . $user->id) }}">&nbsp;{{$user->corporateclient->company_name}} </a>
                               @if($user->newrequests>0)
                                <span class="newrequests">({{$user->newrequests}})</span>
                                   @endif
                            </td>
                            {{--  <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('corporateusers/' . $user->id) }}">{{$user->corporateclient->company_phone}} </a></td>--}}

                              <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$user->corporateclient->contact_person_name}}</td>

                              <td class="mdl-data-table__cell--non-numeric">
                                 {{$user->corporateclient->contact_person_phone}}

                             </td>
                              <td class="mdl-data-table__cell--non-numeric">
                                  {{ $user->corporateclient->password}}

                              </td>
                              <td class="mdl-data-table__cell--non-numeric"><a href="{{ $user->corporateclient->invitation_link }}" target="_blank">View Link </a></td>
                              <td class="mdl-data-table__cell--non-numeric">
                                 @if($user->corporateclient->payment_method==='Prepaid Bank Deposit')
                                      Prepaid Bank Deposit
                                     @endif
                                     @if($user->corporateclient->payment_method==='Credit Card')
                                         Credit Card

                                     @endif

                              </td>
                              <td class="mdl-data-table__cell--non-numeric">







                                  {{-- Status ICON BUTTON AND FORM CALL --}}
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for={{$user->id}} >
                                                        <input type="checkbox" id="{{$user->id}}" class="mdl-switch__input" {{  $user->block_user === 1 ? "checked" : '' }}  />
                                                                                                <!-- attribute checked -->
                                                        
                                                        </label>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('corporateusers/' . $user->id) }}">View</a></td>

                        </tr>
                        @php $i++ @endphp
                        @endif
                    @endforeach
              </tbody>
            </table>

        </div>
    </div>

    <div class="mdl-card__menu addnewuser">

        @include('partials.mdl-highlighter')

        @include('partials.mdl-search')

        <a href="{{ url('/corporateusers/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add Corporate New User">
            <i class="material-icons">person_add</i>
            <span class="sr-only">Add New Corporate Client</span>
        </a>


    </div>
</div>

@endsection

@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script>
        var dialog = document.querySelector('dialog');
        var showModalButton = document.querySelector('.show-modal');
        if (! dialog.showModal) {
            dialogPolyfill.registerDialog(dialog);
        }
        showModalButton.addEventListener('click', function() {
            dialog.showModal();
        });
        dialog.querySelector('.close').addEventListener('click', function() {
            dialog.close();
        });
    </script>
    <script type="text/javascript">

        @foreach ($users as $a_user)
            mdl_dialog('.dialiog-trigger{{$a_user->id}}','','#dialog_delete');
        @endforeach
        var userid;
        $('.dialiog-trigger-delete').click(function(event) {
            event.preventDefault();
            userid = $(this).attr('data-userid');
        });
        $('#confirm').click(function(event) {
            $('form#delete_'+userid).submit();
        });

        $( document ).ready(function() {
            @foreach ($users as $a_user)
            $('#{{$a_user->id}}').change(function() {
                if(this.checked) {
                    var returnVal = confirm("Are you sure to Block this User?");
                    $(this).prop("checked", returnVal);
                    var id = {{$a_user->id}};
                    var status = 1;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/blockit',

                        data:{"id":id, "status":1,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert(data.success);

                        }

                    });
                }
                else{
                    //var returnVal = confirm("Are you sure to Un Block this User?");
                    //$(this).prop("", returnVal);
                    var id = {{$a_user->id}};
                    var status = 0;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/blockit',

                        data:{"id":id, "status":0,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert("User Un Blocked");

                        }

                    });
                }

            });
            @endforeach
        });

    </script>
@endsection
