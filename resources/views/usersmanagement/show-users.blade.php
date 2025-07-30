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
        <a itemprop="item" href="/users" disabled>
            <span itemprop="name">
            Clients List
            </span>
        </a>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')

<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
    <div class="row" style="height: 62px !important;">
        <form action="/users" name="filters" id="filters" method="POST" style="margin-top: 24px !important;">
            @csrf
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="all" <?php echo ($filter=='all') ? 'checked':'' ?>>All</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="completed-registration"<?php echo ($filter=='completed-registration') ? 'checked':'' ?>>Completed Registration</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="incomplete-registration"<?php echo ($filter=='incomplete-registration') ? 'checked':'' ?>>In Complete Registration</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="completed-questionair"<?php echo ($filter=='completed-questionair') ? 'checked':'' ?>>Completed Questionnaire</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="incomplete-questionair"<?php echo ($filter=='incomplete-questionair') ? 'checked':'' ?>>In Complete Questionnaire</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="having-therapists"<?php echo ($filter=='having-therapists') ? 'checked':'' ?>>Clients Having Therapists</label>
            <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="not-having-therapists"<?php echo ($filter=='not-having-therapists') ? 'checked':'' ?>>Clients Not Having Therapists</label>
        </form>
    </div>
    <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h2 class="mdl-card__title-text logo-style">
            @if (sizeof($users) === 1)
                {{ sizeof($users) }} Client total
            @elseif (sizeof($users) > 1)
                {{ sizeof($users) }} Total Clients
            @else
                No Clients :(
            @endif
        </h2>

    </div>
    <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
        <div class="table-responsive material-table">
            @if($success)
            <p class="successmsg">{{$success}}</p>
            @endif
            <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                    <th class="mdl-data-table__cell--non-numeric">Nick Name</th>
                    <th class="mdl-data-table__cell--non-numeric">Email</th>                   
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Register Date</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Therapist</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Appointments</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Subscribed</th>
                    <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">($) Total</th>
{{--                    <th class="mdl-data-table__cell--non-numeric no-sort no-search">Blocked</th>--}}
                </tr>
              </thead>
              <tbody>
              @php $i=0 @endphp
              
                    @foreach ($users as $user)
                        <tr {!! $user->activated==0 ? "class=newrequest":"" !!}>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('users/' . $user->id) }}">{{$i+1}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('users/' . $user->id) }}"><img src={{$user->image_url}} width="24px">&nbsp;{{$user->name}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('users/' . $user->id) }}">{{$user->email}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('users/' . $user->id) }}">{{date('m-d-Y H:m:s', strtotime($user->created_at))}} </a></td>
                           
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">
                             {{ $user->stripe_plan }}

</td>



                           <td class="mdl-data-table__cell--non-numeric">
                              {{ sizeof($user->selectedTherapists)>0 ? ($user->selectedTherapists[0]->therapist->fullname) : "" }}
                               
                           </td>
                           <td class="mdl-data-table__cell--non-numeric">
                           {{sizeof($user->appointments)}}
                           </td>
                           <!--
                            <td class="mdl-data-table__cell--non-numeric">
                                @if($user->activated==0)

                                <a href="{{ URL::to('users/confirmuser/' . $user->id) }}"><span class="material-icons">
                            email
                                    </span></a>
                                @endif
                            </td>
                            
                            <td class="mdl-data-table__cell--non-numeric">







                                {{-- Status ICON BUTTON AND FORM CALL --}}
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for={{$user->id}} >
                                                        <input type="checkbox" id="{{$user->id}}" class="mdl-switch__input" {{  $user->block_user === 1 ? "checked" : '' }}  />
                                                                                                /* attribute checked */

                                                        </label>
                            </td> -->

                            <td class="mdl-data-table__cell--non-numeric">
                                @if($user->subscription_status == 'active')
                                    <i class="material-icons mdl-color-text--green">check_circle_outline</i>
                                @else
                                    <i class="material-icons mdl-color-text--pink">remove_shopping_cart</i>
                                @endif
                            </td>
                            <td class="mdl-data-table__cell--non-numeric">
                                ${{number_format($user->total_spending, 2)}}
                            </td>
                        </tr>
                        @php $i++ @endphp
                    @endforeach
              </tbody>
            </table>
        </div>
    </div>
    <div class="mdl-card__menu" style="top: -4px; display: none;">

        @include('partials.mdl-highlighter')

        @include('partials.mdl-search')

        <a href="{{ url('/users/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New User">
            <i class="material-icons">person_add</i>
            <span class="sr-only">Add New User</span>
        </a>

        <a href="{{ URL::to('/users/deleted') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Show Deleted Users">
            <i class="material-icons" aria-hidden="true">delete_sweep</i>
            <span class="sr-only">Show Deleted Users</span>
        </a>

    </div>
</div>


@endsection

@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script type="text/javascript">
        $( document ).ready(function() {
            $('.localfilter').click(function(){
                var filterValue = $(this).val();
                $('#filters').submit();
            });
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