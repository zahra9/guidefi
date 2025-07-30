@extends('layouts.dashboard')

@section('template_title')
    Showing Appoinments
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Appoinments
@endsection
@section('content')
    <div class="row">
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="row" style="height: 40px !important; margin-left: 13px;">
                <form action="/appointments/filter" name="filters" id="filters" method="POST" style="margin-top: 24px !important;">
                    @csrf
                    Filter By
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="all" <?php echo ($filter=='all') ? 'checked':'' ?>>All</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="audio"<?php echo ($filter=='audio') ? 'checked':'' ?>>Audio</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="video"<?php echo ($filter=='video') ? 'checked':'' ?>>Video</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="past"<?php echo ($filter=='past') ? 'checked':'' ?>>Past Appointments</label>
                    <label class="form-check-label"><input type="radio" class="form-check-input localfilter" name="filter" value="future"<?php echo ($filter=='future') ? 'checked':'' ?>>Future Appointments</label>

                    <div class="" style="float: right; margin-left: 306px;">
                        <label class="form-check-label"> <input type="text" placeholder="From Date" name="from" class="" id="fromdatepicker" value={{$filter=='datefilter' ? old('from'): ''}}></label>
                        <label class="form-check-label"> <input type="text" placeholder="To Date" name="from" class="" id="todatepicker" value={{$filter=='datefilter' ? old('to'): ''}}></label>
                        <span class="form-check-label localfilter" style="cursor: pointer; color: blueviolet"> Filter </span>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($appoinments)  === 1)
                    {{ sizeof($appoinments) }} Appoinment total
                @elseif (sizeof($appoinments) > 1)
                    {{ sizeof($appoinments) }} Total Appoinments
                @else
                    No Appoinment Found
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                        <th class="mdl-data-table__cell--non-numeric">Client</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Therapist</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Appoinment Date</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Type</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Booking Date</th>
                        {{--                        <th class="mdl-data-table__cell--non-numeric no-sort no-search">Actions</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($appoinments as $key => $appointment)

                        @if(!is_null($appointment->user))
                            <tr>
                                <td class="mdl-data-table__cell--non-numeric"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$key+1}}</a></td>

                                <td class="mdl-data-table__cell--non-numeric"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$appointment->user->name}} </a>
                                </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$appointment->therapist->fullname}} </a>
                                </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$appointment->appointment_date}} </a>
                                </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$appointment->type}} </a>
                                </td>
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                            href="{{ URL::to('appointments/' . $appointment->id) }}">{{$appointment->created_at}} </a>
                                </td>
                                {{--<td class="mdl-data-table__cell--non-numeric">
                                    --}}{{-- VIEW USER ACCOUNT ICON BUTTON --}}{{--
                                    <a href="{{ URL::to('appointments/' . $appointment->id) }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" title="View Appointment Detail">
                                        <i class="material-icons mdl-color-text--blue">account_circle</i>
                                    </a>
                                </td>--}}
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-card__menu" style="top: -4px;">

            @include('partials.mdl-highlighter')

            @include('partials.mdl-search')

            {{-- <a href="{{ url('/appoinments/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New Appoinment">
                 <i class="material-icons">person_add</i>
                 <span class="sr-only">Add New Appoinment</span>
             </a>

             <a href="{{ URL::to('/appoinments/deleted') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Show Deleted Appoinments">
                 <i class="material-icons" aria-hidden="true">delete_sweep</i>
                 <span class="sr-only">Show Deleted Appoinments</span>
             </a>--}}

        </div>
    </div>

    @include('dialogs.dialog-delete')

@endsection
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
                $('.localfilter').click(function(){
                    var filterValue = $(this).val();
                    $('#filters').submit();
                });

            $("#fromdatepicker").on("change", function () {
                //$( "#fromdatepicker" ).datepicker( "option", "dateFormat", 'mm-dd-yy' );
            });
            $("#todatepicker").on("change", function () {
                // $( "#todatepicker" ).datepicker( "option", "dateFormat", 'mm-dd-yy' );
            });
        });
    </script>
    <script>
        $(function () {
            $("#fromdatepicker").datepicker();
        });
        $(function () {
            $("#todatepicker").datepicker();
        });
    </script>
    <script type="text/javascript">

        $("#therapist").change(function () {
            $(".userdiv").hide();
            $(".therapistdiv").show();
        });
        $("#client").change(function () {
            $(".userdiv").show();
            $(".therapistdiv").hide();
        });

    </script>
    <script type="text/javascript">
        @foreach ($appoinments as $a_user)
        mdl_dialog('.dialiog-trigger{{$a_user->id}}', '', '#dialog_delete');
                @endforeach
        var userid;
        $('.dialiog-trigger-delete').click(function (event) {
            event.preventDefault();
            userid = $(this).attr('data-userid');
        });
        $('#confirm').click(function (event) {
            $('form#delete_' + userid).submit();
        });
    </script>
@endsection


