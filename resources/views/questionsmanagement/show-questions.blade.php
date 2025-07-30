@extends('layouts.dashboard')

@section('template_title')
  Showing Questions
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Questions
@endsection
@section('content')

    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($payload['section'])  === 1)
                    {{ sizeof($payload['section']) }} Question total
                @elseif (sizeof($payload['section']) > 1)
                    {{ sizeof($payload['section']) }} Total Questions
                @else
                    No Question Found
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>question</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    {{-- Loop for Get all Sections --}}
                    @foreach ($payload['section'] as $section)
                    <tr role="row">
                        <td class=" details-control"></td>
                        <td class="sorting_1">{{  $section['title'] }}</td>
                        <td>
                            <ol type="i">
                                {{-- Loop for Get all questions of current Section --}}
                            @foreach ($section['question'] as $question)
                                    <li>Question: {{$question['title']}}
                                        <p>Type: {{$question['type']}}</p>
                                        <span>Options:</span>
                                        <ol type="i">
                                            {{-- Loop for Get all options of current question --}}
                                        @foreach ($question['options'] as $option)
                                                <li class="qoptions">{{$option['title']}}</li>
                                        @endforeach
                                        </ol>
                                    </li>
                            @endforeach
                            </ol>
                        </td>
                        <td></td>
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


@endsection
@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <style>
        .qoptions{

        }
        td.details-control {
            background: url({{ asset('images/details_open.png') }}) no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url({{ asset('images/details_close.png') }}) no-repeat center center;
        }
    </style>
    <script type="text/javascript">
        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                '<td>Section name:</td>'+
                '<td>'+d.name+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td valign="top">Questions:</td>'+
                '<td>'+d[2]+'</td>'+
                '</tr>'+
                '</table>';
        }

        $(document).ready(function() {
            var table = $('#example').DataTable( {
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "name" },
                    { "visible": false },
                    { "data": null,
                    "defaultContent": '' }

                ],
                "order": [[1, 'asc']]
            } );

            // Add event listener for opening and closing details
            $('#example tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
        } );
    </script>
@endsection


