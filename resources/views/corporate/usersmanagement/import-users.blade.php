@extends('layouts.dashboard')

@section('template_title')
  Import CSV Users
@endsection

@section('header')
  Import CSV Users
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
    <a itemprop="item" href="/clients">
      <span itemprop="name">
        Clients List
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/clients/create">
      <span itemprop="name">
        Import CSV Users
      </span>
    </a>
    <meta itemprop="position" content="3" />
  </li>
@endsection

@section('content')

  <div class="mdl-grid full-grid margin-top-0 padding-0">
    <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
        <div class="mdl-card card-new-user" itemscope itemtype="http://schema.org/Person">

        <div class="mdl-card__title mdl-card--expand mdl-color--green mdl-color-text--white">
          <h2 class="mdl-card__title-text logo-style">Import CSV Users</h2>
        </div>

        {!! Form::open(array('action' => 'CorporateUsersManagementController@savecsvfile', 'method' => 'POST', 'role' => 'form','files'=>'true')) !!}

          <div class="mdl-card__supporting-text">
            <div class="mdl-grid full-grid padding-0">
              <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                <div class="mdl-grid ">

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('csvfile') ? 'is-invalid' :'' }}">
                      {!! Form::file('csvfile', NULL, array('id' => 'csvfile', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('csvfile', 'CSV File' , array('class' => 'mdl-textfield__label')) !!}
                      {!! Form::hidden('created_by',  $created_by , array('id' => 'created_by', 'class' => 'mdl-textfield__input')) !!}

                      <span class="mdl-textfield__error">Upload Valid CSV File</span>
                    </div>
                  </div>

                  <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                    @if(!is_null($data) && count($data)>0)
                    <table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp">

                      @foreach ($data as $row)

                          <tbody>
                          <tr>
                          @foreach ($row as $key => $value)
                            <td>{{ $value }}</td>
                          @endforeach
                          </tr>
                          </tbody>

                      @endforeach

                    </table>
                      @endif
                  </div>










                </div>
              </div>

            </div>
          </div>

          <div class="mdl-card__actions padding-top-0">
            <div class="mdl-grid padding-top-0">
              <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">

                {{-- SAVE BUTTON--}}
                <span class="save-actions">
                  {!! Form::button('<i class="material-icons">save</i> Upload CSV File', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                </span>

              </div>
            </div>
          </div>

            <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Upload CSV File')) !!}
              </span>

              <a href="{{ url('/clients/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users">
                  <i class="material-icons">reply</i>
                  <span class="sr-only">Back to Users</span>
              </a>

            </div>

            @include('dialogs.dialog-save')

          {!! Form::close() !!}

        </div>
    </div>
  </div>

@endsection

@section('footer_scripts')

  @include('scripts.mdl-required-input-fix')
  @include('scripts.gmaps-address-lookup-api3')

  <script type="text/javascript">
    mdl_dialog('.dialog-button-save');
    mdl_dialog('.dialog-button-icon-save');
  </script>

@endsection
