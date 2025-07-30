@extends('layouts.dashboard')

@section('template_title')
  Showing Packages
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Packages
@endsection
@section('content')

    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($packages)  === 1)
                    {{ sizeof($packages) }} Packages total
                @elseif (sizeof($packages) > 1)
                    {{ sizeof($packages) }} Total Packages
                @else
                    No Package Found
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">ID</th>
                        <th class="mdl-data-table__cell--non-numeric">Title</th>
                        <th class="mdl-data-table__cell--non-numeric">Price</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Package Type</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Text limit</th>
                        <th class="mdl-data-table__cell--non-numeric">Voice Call Limit</th>
                        <th class="mdl-data-table__cell--non-numeric">Video Call Limit</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Created</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Updated</th>
                        <th class="mdl-data-table__cell--non-numeric no-sort no-search">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->id}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->title}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->price}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->package_type}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->text_limit}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->voice_call_limit}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->video_call_limit}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->created_at}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only"><a href="{{ URL::to('packages/' . $package->id) }}">{{$package->updated_at}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric">
                                {{-- VIEW USER ACCOUNT ICON BUTTON --}}
                                <a href="{{ URL::to('packages/' . $package->id) }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" title="view package details">
                                    <i class="material-icons mdl-color-text--blue">account_circle</i>
                                </a>

                                {{-- EDIT USER ICON BUTTON --}}
                                <a href="{{ URL::to('packages/' . $package->id . '/edit') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                    <i class="material-icons mdl-color-text--orange">edit</i>
                                </a>

                                {{-- DELETE ICON BUTTON AND FORM CALL --}}
                                {!! Form::open(array('url' => 'packages/' . $package->id, 'class' => 'inline-block', 'id' => 'delete_'.$package->id)) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$package->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$package->id}}">
                                    <i class="material-icons mdl-color-text--red">delete</i>
                                </a>
                                {!! Form::close() !!}

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-card__menu" style="top: -4px;">

            @include('partials.mdl-highlighter')

            @include('partials.mdl-search')

            <a href="{{ url('/packages/create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New Package">
                <i class="material-icons">person_add</i>
                <span class="sr-only">Add New Package</span>
            </a>

            {{--<a href="{{ URL::to('/create/stripe/plans') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Delete and reCreate Stripe Plans">
                <span class="material-icons">done_all</span>
                <span class="sr-only">Delete and create Stripe Plans</span>
            </a>--}}

            {{--<a href="{{ URL::to('/packages/deleted') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Show Deleted Package">
                <i class="material-icons" aria-hidden="true">delete_sweep</i>
                <span class="sr-only">Show Deleted Packages</span>
            </a>--}}

        </div>
    </div>

    @include('dialogs.dialog-delete')

@endsection
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script type="text/javascript">
        @foreach ($packages as $a_user)
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


