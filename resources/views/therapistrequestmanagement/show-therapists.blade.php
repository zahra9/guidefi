@extends('layouts.dashboard')

@section('template_title')
    Showing Therapist Requests
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Therapist Requests
@endsection
@section('content')
    @if ($suceess!='null')
        <div class="msgbox">
            {{$suceess}}
        </div>
    @endif
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($therapists)  === 1)
                    {{ sizeof($therapists) }} Therapist Requests total
                @elseif (sizeof($therapists) > 1)
                    {{ sizeof($therapists) }} Total Therapist Requests
                @else
                    No Therapist Request Found
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
                        <th class="mdl-data-table__cell--non-numeric">Email</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Phone</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Education</th>
                        <th class="mdl-data-table__cell--non-numeric">License Detail</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">New Request</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Registraion Date
                        </th>

                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Status</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($therapists as $key => $therapist)
                        <tr {!! $therapist->view_profile==0 ? "class=newrequest":"" !!}>
                            <td class="mdl-data-table__cell--non-numeric"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{{$key+1}}</a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{{$therapist->fullname}} </a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{{$therapist->email}} </a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{{$therapist->phone}} </a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{!!strlen($therapist->education) >20 ? substr($therapist->education,0,20) : $therapist->education!!}
                                </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{!!strlen($therapist->licensedetail) > 30 ? substr($therapist->licensedetail,0,30) : $therapist->licensedetail!!} </a>
                            </td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{!! $therapist->view_profile==0 ? "Yes":"No" !!} </td>

                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                        href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}">{{date('m-d-Y H:m:s', strtotime($therapist->created_at))}} </a>
                            </td>
                            @if($therapist->status==='Approved')
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">
                                    <i class="material-icons mdl-color-text--green">how_to_reg</i>
                                </td>
                            @elseif($therapist->status==='Rejected')
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a
                                            href="{{ URL::to('therapistrequests/showuser/' . $therapist->id) }}"
                                            title='Rejected'>
                                        <i class="material-icons mdl-color-text--red">warning</i>
                                    </a></td>
                            @else
                                <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"
                                    title='Pending'><i class="material-icons mdl-color-text--orange">loop</i></td>
                            @endif


                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-card__menu" style="top: -4px;">

            @include('partials.mdl-highlighter')

            @include('partials.mdl-search')


        </div>
    </div>


    @include('dialogs.dialog-delete')

@endsection
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script type="text/javascript">
        @foreach ($therapists as $a_user)
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


