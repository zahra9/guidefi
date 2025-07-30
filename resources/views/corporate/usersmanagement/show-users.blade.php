@extends('layouts.dashboard')

@section('template_title')
  Showing Clients
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Clients
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
        <a itemprop="item" href="/clients" disabled>
            <span itemprop="name">
            Clients List
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
                No Clients
            @endif
        </h2>

    </div>
    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
        <div class="table-responsive material-table">
            <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                    <th class="mdl-data-table__cell--non-numeric">Nick Name</th>
                    <th class="mdl-data-table__cell--non-numeric">Email</th>                   
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Register Date</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Therapist</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Re Invite</th>
                    <th class="mdl-data-table__cell--non-numeric no-sort no-search">Blocked</th>
                </tr>
              </thead>
              <tbody>
              @php $i=0 @endphp
              
                    @foreach ($users as $user)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}">{{$i+1}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}"><img src={{$user->image_url}} width="24px">&nbsp;{{$user->name}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('clients/' . $user->id) }}">{{$user->email}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('clients/' . $user->id) }}">{{date('m-d-Y H:m:s', strtotime($user->created_at))}} </a></td>
                           
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">
                             {{ sizeof($user->subscribedPlans)>0 ? $user->subscribedPlans[0]->title : "" }}
                            </td>

                            <td class="mdl-data-table__cell--non-numeric"></td>
                            <td class="mdl-data-table__cell--non-numeric">
                                @if($user->first_login==1)
                                    <a href="{{ URL::to('clients/sendemail/' . $user->id) }}" title="Send Email About User Info"><span class="material-icons">
email
</span> </a>

                                    @endif
                            </td>
                            <td class="mdl-data-table__cell--non-numeric">
                                {{-- Status ICON BUTTON AND FORM CALL --}}
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for={{$user->id}} >
                                                        <input type="checkbox" id="{{$user->id}}" class="mdl-switch__input" {{  $user->block_user === 1 ? "checked" : '' }}  />
                                                                                                <!-- attribute checked -->
                                                        
                                                        </label>
                            </td>
                        </tr>
                        @php $i++ @endphp
                    @endforeach
              </tbody>
            </table>
        </div>
    </div>
    <div class="mdl-card__menu" style="top: -4px;">

        @include('partials.mdl-highlighter')

        @include('partials.mdl-search')

        <a href="{{ url('/clients/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New User">
            <i class="material-icons">person_add</i>
            <span class="sr-only">Add New User</span>
        </a>
        <a href="{{ url('/importcsv') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add CSV Data">
            <i class="material-icons">group_add</i>
            <span class="sr-only">Add CSV Data</span>
        </a>



    </div>
</div>


@endsection

@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script type="text/javascript">
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

                        url:'/clients/blockit',

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

                        url:'/clients/blockit',

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
