@extends('layouts.dashboard')

@section('template_title')
    Deleted Therapists
@endsection

@php
    $dataTablesShowCount = 5;   // Number of users at which to show the datatables
    $deletedUsersCount = count($therapists);
@endphp

@section('header')
    Showing Deleted Therapists
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
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/therapists" disabled>
            <span itemprop="name">
                Therapists List
            </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="2" />
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/therapists/deleted" disabled>
            <span itemprop="name">
                Deleted Therapists
            </span>
        </a>
        <meta itemprop="position" content="3" />
    </li>

@endsection

@section('content')

    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--red-400 mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if ($deletedUsersCount === 1)
                    {{ $deletedUsersCount }} Deleted Therapist
                @elseif ($deletedUsersCount > 1)
                    {{ $deletedUsersCount }} Deleted therapists
                @else
                    No Records Found
                @endif
            </h2>
        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                @if ($deletedUsersCount >= 1)
                    <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th class="mdl-data-table__cell--non-numeric">ID</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Full Name</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Experience</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">About</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Deleted</th>
                            <th class="mdl-data-table__cell--non-numeric no-sort no-search">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                            @foreach ($therapists as $user)
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">{{$user->id}}</td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$user->fullname}} </td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$user->experience}} </td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$user->about}} </td>

                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$user->deleted_at}}</td>

                                    <td class="mdl-data-table__cell--non-numeric">



                                        {!! Form::model($user, ['action' => ['ThSoftDeletesController@update', $user->id],  'method' => 'PUT', 'class' => 'inline-block', 'id' => 'restore_'.$user->id]) !!}
                                            <a href="#" class="dialog-button dialiog-trigger-restore dialiog-trigger-restore-{{$user->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$user->id}}" id="restore_user_{{$user->id}}">
                                                <i class="material-icons mdl-color-text--green">replay</i>
                                                <span class="sr-only">Restore Deleted Therapists</span>
                                                <span class="mdl-tooltip mdl-tooltip--top" for="restore_user_{{$user->id}}">
                                                    Restore Deleted Therapists
                                                </span>
                                            </a>
                                        {!! Form::close() !!}

                                        {!! Form::model($user, ['action' => ['SoftDeletesController@destroy', $user->id],  'method' => 'DELETE', 'class' => 'inline-block', 'id' => 'delete_'.$user->id]) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$user->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$user->id}}" id="delete_user_{{$user->id}}">
                                                <i class="material-icons mdl-color-text--red">delete_forever</i>
                                                <span class="sr-only">Permanently Delete User</span>
                                                <span class="mdl-tooltip mdl-tooltip--top" for="delete_user_{{$user->id}}">
                                                    Permanently Delete User
                                                </span>
                                            </a>
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                            @endforeach
                      </tbody>
                    </table>
                @else
                    <p class="text-center margin-top-4">
                        No Deleted Users
                    </p>
                @endif
            </div>
        </div>
        <div class="mdl-card__menu" style="top: -4px;">

            @if ($deletedUsersCount != 0)
                @include('partials.mdl-highlighter')
            @endif

            @if ($deletedUsersCount > $dataTablesShowCount)
                @include('partials.mdl-search')
            @endif

            <a href="{{ url('/users/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users" @if ($deletedUsersCount == 0) style="top: 16px" @endif>
                <i class="material-icons">reply</i>
                <span class="sr-only">Back to Therapists</span>
            </a>

        </div>
    </div>

    @if ($deletedUsersCount != 0)
        @include('dialogs.dialog-delete')
        @include('dialogs.dialog-restore')
    @endif

@endsection

@section('footer_scripts')

    @if ($deletedUsersCount > $dataTablesShowCount)
        @include('scripts.mdl-datatables')
    @endif

    @if ($deletedUsersCount != 0)
        @include('scripts.highlighter-script')

        <script type="text/javascript">
            @foreach ($therapists as $a_user)
                mdl_dialog('.dialiog-trigger{{$a_user->id}}','','#dialog_delete');
                mdl_dialog('.dialiog-trigger-restore-{{$a_user->id}}','.dialog-restore-close','#dialog_restore');
            @endforeach
            var userid;
            $('.dialiog-trigger-delete, .dialiog-trigger-restore').click(function(event) {
                event.preventDefault();
                userid = $(this).attr('data-userid');
            });
            $('#confirm').click(function(event) {
                $('form#delete_'+userid).submit();
            });
            $('#confirm_restore').click(function(event) {
                $('form#restore_'+userid).submit();
            });
        </script>

    @endif

@endsection