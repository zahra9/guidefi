@extends('layouts.dashboard')

@section('template_title')
  Create New Package
@endsection

@section('header')
  Create New Package
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
    <a itemprop="item" href="/packages">
      <span itemprop="name">
        Packages List
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/packages/create">
      <span itemprop="name">
        Create New Package
      </span>
    </a>
    <meta itemprop="position" content="3" />
  </li>
@endsection

@section('content')

  <div class="mdl-grid full-grid margin-top-0 padding-0">
    <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
        <div class="mdl-card card-new-user" style="width:100%;" itemscope itemtype="http://schema.org/Person">

        <div class="mdl-card__title mdl-card--expand mdl-color--green mdl-color-text--white">
          <h2 class="mdl-card__title-text logo-style">Create New Package
{{--            <b>Important Note:</b> Every time you create a new pakage uou need to that pakage plan on the stripe--}}
          </h2>
        </div>

        {!! Form::open(array('action' => 'PackageController@store', 'method' => 'POST', 'role' => 'form')) !!}

          <div class="mdl-card__supporting-text">
            <div class="mdl-grid full-grid padding-0">
              <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                <div class="mdl-grid ">

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('title') ? 'is-invalid' :'' }}">
                      {!! Form::text('title', NULL, array('id' => 'title', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('title', trans('packages.title') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Letters and White Space only</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('price') ? 'is-invalid' :'' }}">
                      {!! Form::number('price', NULL, array('id' => 'price', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('price', trans('packages.price') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.price') }}</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('package_type') ? 'is-invalid' :'' }}">
                      {!! Form::text('package_type', NULL, array('id' => 'package_type', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('package_type', trans('packages.package_type') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.package_type') }}</span>
                    </div>
                  </div>

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('text_limit') ? 'is-invalid' :'' }}">
                      {!! Form::text('text_limit', NULL, array('id' => 'text_limit', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('text_limit', trans('packages.text_limit') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.text_limit') }}</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('voice_call_limit') ? 'is-invalid' :'' }}">
                      {!! Form::number('voice_call_limit', NULL, array('id' => 'voice_call_limit', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('voice_call_limit', trans('packages.voice_call_limit') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.voice_call_limit') }}</span>
                    </div>
                  </div>

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('video_call_limit') ? 'is-invalid' :'' }}">
                      {!! Form::text('video_call_limit', NULL, array('id' => 'video_call_limit', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('video_call_limit', trans('packages.video_call_limit') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.video_call_limit') }}</span>
                    </div>
                  </div>

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('voice_call_duration') ? 'is-invalid' :'' }}">
                      {!! Form::number('voice_call_duration', NULL, array('id' => 'voice_call_duration', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('voice_call_duration', trans('packages.voice_call_duration') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.voice_call_duration') }}</span>
                    </div>
                  </div>

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('video_call_duration') ? 'is-invalid' :'' }}">
                      {!! Form::number('video_call_duration', NULL, array('id' => 'video_call_duration', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('video_call_duration', trans('packages.video_call_duration') , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid {{ trans('packages.video_call_duration') }}</span>
                    </div>
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
                  {!! Form::button('<i class="material-icons">save</i> Save New Package', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                </span>

              </div>
            </div>
          </div>

            <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Save New User')) !!}
              </span>

              <a href="{{ url('/packages/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users">
                  <i class="material-icons">reply</i>
                  <span class="sr-only">Back to Packages</span>
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