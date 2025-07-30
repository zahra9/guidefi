@extends('layouts.dashboard')

@section('template_title')
    Deleted Packages
@endsection

@php
    $dataTablesShowCount = 5;   // Number of users at which to show the datatables
    $deletedUsersCount = count($packages);
@endphp

@section('header')
    Showing Deleted Packages
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
        <a itemprop="item" href="/packages" disabled>
            <span itemprop="name">
                Packages List
            </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="2" />
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/packages/deleted" disabled>
            <span itemprop="name">
                Deleted Packages
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
                    {{ $deletedUsersCount }} Deleted Package
                @elseif ($deletedUsersCount > 1)
                    {{ $deletedUsersCount }} Deleted Packages
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
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Title</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Price</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package Type</th>
                            <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Deleted At</th>
                            <th class="mdl-data-table__cell--non-numeric no-sort no-search">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                            @foreach ($packages as $package)
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric">{{$package->id}}</td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$package->title}} </td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$package->price}} </td>
                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$package->package_type}} </td>

                                    <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">{{$package->deleted_at}}</td>

                                    <td class="mdl-data-table__cell--non-numeric">



                                        {!! Form::model($package, ['action' => ['PkSoftDeletesController@update', $package->id],  'method' => 'PUT', 'class' => 'inline-block', 'id' => 'restore_'.$package->id]) !!}
                                            <a href="#" class="dialog-button dialiog-trigger-restore dialiog-trigger-restore-{{$package->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$package->id}}" id="restore_user_{{$package->id}}">
                                                <i class="material-icons mdl-color-text--green">replay</i>
                                                <span class="sr-only">Restore Deleted Packages</span>
                                                <span class="mdl-tooltip mdl-tooltip--top" for="restore_user_{{$package->id}}">
                                                    Restore Deleted Packages
                                                </span>
                                            </a>
                                        {!! Form::close() !!}

                                        {!! Form::model($package, ['action' => ['PkSoftDeletesController@destroy', $package->id],  'method' => 'DELETE', 'class' => 'inline-block', 'id' => 'delete_'.$package->id]) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$package->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$package->id}}" id="delete_user_{{$package->id}}">
                                                <i class="material-icons mdl-color-text--red">delete_forever</i>
                                                <span class="sr-only">Permanently Delete Package</span>
                                                <span class="mdl-tooltip mdl-tooltip--top" for="delete_user_{{$package->id}}">
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
                        No Deleted Package
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

            <a href="{{ url('/packages/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users" @if ($deletedUsersCount == 0) style="top: 16px" @endif>
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
            @foreach ($packages as $a_user)
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