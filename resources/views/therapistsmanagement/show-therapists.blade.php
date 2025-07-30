@extends('layouts.dashboard')

@section('template_title')
  Showing Therapists
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Therapists
@endsection
@section('content')

    <span>Show: </span>
    <form action="/therapists" name="filters" id="filters" method="POST">
        @csrf
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="all" <?php echo ($filter=='all') ? 'checked':'' ?>>All</label>
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="completed-questionair"<?php echo ($filter=='completed-questionair') ? 'checked':'' ?>>Completed Questionnaire</label>
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="incomplete-questionair"<?php echo ($filter=='incomplete-questionair') ? 'checked':'' ?>>In Complete Questionnaire</label>
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="having-clients"<?php echo ($filter=='having-clients') ? 'checked':'' ?>>Therapists Having Clients</label>
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="not-having-clients"<?php echo ($filter=='not-having-clients') ? 'checked':'' ?>>Therapists Not Having Clients</label>
        <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="clients-today"<?php echo ($filter=='clients-today') ? 'checked':'' ?>>Clients Today</label>
    </form>
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">

        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($therapists)  === 1)
                    {{ sizeof($therapists) }} Therapist total
                @elseif (sizeof($therapists) > 1)
                    {{ sizeof($therapists) }} Total Therapists
                @else
                    No Therapist Found
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                        <th class="mdl-data-table__cell--non-numeric">Full Name</th>
                        <th class="mdl-data-table__cell--non-numeric">State</th>
                        <th class="mdl-data-table__cell--non-numeric">Certificate list</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Experience</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Rating</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">No. Of Appt</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Upcoming Appt</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Number of clients</th>
                        <th class="mdl-data-table__cell--non-numeric no-sort no-search">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0 @endphp
                    @foreach ($therapists as $therapist)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('therapists/' . $therapist->id) }}">{{$i+1}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('therapists/' . $therapist->id) }}">{{$therapist->fullname}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric">{{$therapist->state['answer_text']}}</td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('therapists/' . $therapist->id) }}">{{$therapist->certificatelist}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('therapists/' . $therapist->id) }}">{{$therapist->experience}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('therapists/' . $therapist->id) }}">{{$therapist->rating}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric">{{$therapist->appointments}}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{$therapist->upcoming_appointments}}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{$therapist->active_clients}}</td>

                            <td class="mdl-data-table__cell--non-numeric">

                                {{-- VIEW USER ACCOUNT ICON BUTTON --}}
                                @if(isset($therapist->user))
                                <a href="{{ URL::to('therapists/' . $therapist->id) }}"><i class="fa fa-search"></i></a>
                                <label style="display:none;" class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for={{$therapist->id}}>

                                                        <input type="checkbox" id={{$therapist->id}} class="mdl-switch__input" onclick="myFunction()"

                                                                {{  $therapist->user->block_user === 1 ? "checked" : '' }}

                                                                />
                                                           <!-- attribute checked -->
                                                        
                                                        </label>
                                @endif

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

            <a href="{{ url('/therapists/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New Therapist">
                <i class="material-icons">person_add</i>
                <span class="sr-only">Add New Therapist</span>
            </a>

            <a href="{{ URL::to('/therapists/deleted') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Show Deleted Therapist">
                <i class="material-icons" aria-hidden="true">delete_sweep</i>
                <span class="sr-only">Show Deleted Therapists</span>
            </a>

        </div>
    </div>

    @include('dialogs.dialog-delete')

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


            @foreach ($therapists as $therapist)
            @if(isset($therapist->user))
            $('#{{$therapist->id}}').change(function() {
                if(this.checked) {
                    var returnVal = confirm("Are you sure , you want to block this therapist?");

                    $(this).prop("checked", returnVal);
                    var id = {{$therapist->user_id}};
                    var status = 1;
                    $('#{{$therapist->id}}').val(this.checked);
                    if(returnVal==true){
                        $.ajax({

                            type:'POST',

                            url:'/blockit',

                            data:{"id":id, "status":1,"_token": "{{ csrf_token() }}"},

                            success:function(data){

                                alert("Therapist Blocked");

                            }

                        });
                    }

                }
                else{
                    //var returnVal = confirm("Are you sure to Un Block this User?");
                    //$(this).prop("", returnVal);
                    var id = {{$therapist->user_id}};
                    var status = 0;
                    $('#{{$therapist->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/blockit',

                        data:{"id":id, "status":0,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert("Therapist unblocked!");

                        }

                    });
                }

            });
            @endif
            @endforeach

        });
        function myFunction() {

        }
        @foreach ($therapists as $a_user)
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
    </script>
@endsection


