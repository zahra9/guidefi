@extends('layouts.dashboard')

@section('template_title')
  Editing User {{ $user->fullname }}
@endsection

@section('template_linked_css')
@endsection
@section('header')
  Update Existing Therapists
@endsection
@section('content')
  <div class="mdl-grid full-grid margin-top-0 padding-0">
    <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
      <div class="mdl-card card-new-user" style="width:100%;" itemscope itemtype="http://schema.org/Person">

        <div class="mdl-card__title mdl-card--expand mdl-color--green mdl-color-text--white">
          <h2 class="mdl-card__title-text logo-style">Update Existing Therapist</h2>
        </div>

        {!! Form::model($user, array('enctype'=>'multipart/form-data','action' => array('TherapistController@update', $user->id), 'method' => 'PUT')) !!}

        {!! csrf_field() !!}

        <div class="mdl-card__supporting-text">
          <div class="mdl-grid full-grid padding-0">
            <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

              <div class="mdl-grid ">

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('fullname') ? 'is-invalid' :'' }}">
                    {!! Form::text('fullname', NULL, array('id' => 'fullname', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('fullname', trans('auth.fullname') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Letters and White Space only</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('certificatelist') ? 'is-invalid' :'' }}">
                    {!! Form::text('certificatelist', NULL, array('id' => 'certificatelist', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('certificatelist', trans('auth.certificatelist') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.certificatelist') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('experience') ? 'is-invalid' :'' }}">
                    {!! Form::text('experience', NULL, array('id' => 'experience', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('experience', trans('auth.experience') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.experience') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('rating') ? 'is-invalid' :'' }}">
                    {!! Form::text('rating', NULL, array('id' => 'rating', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('rating', trans('auth.rating') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.rating') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('about') ? 'is-invalid' :'' }}">
                    {!! Form::text('about', NULL, array('id' => 'about', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('experience', trans('auth.about') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.about') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('availability') ? 'is-invalid' :'' }}">
                    {!! Form::text('availability', NULL, array('id' => 'availability', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('availability', trans('auth.availability') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.availability') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('image') ? 'is-invalid' :'' }}">
                    {!! Form::file('image', NULL, array('id' => 'image', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('image', "Profile image" , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please upload profile image</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('monday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[monday_start]', null, array('id' => 'monday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('monday_start', trans('auth.monday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.monday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('monday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[monday_end]', NULL, array('id' => 'monday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('monday_end', trans('auth.monday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.monday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('monday_status') ? 'is-invalid' :'' }}">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="monday_status">
                      <input type="checkbox" id="monday_status" name="monday_status"  class="mdl-switch__input"
                      @if($user->timing->monday_status=='on')
                       {{' checked'}}
                              @endif
                      >

                      <span class="mdl-switch__label mdl-textfield__label">Monday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.monday_status') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('tuesday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[tuesday_start]', NULL, array('id' => 'tuesday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('tuesday_start', trans('auth.tuesday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.tuesday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('tuesday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[tuesday_end]', NULL, array('id' => 'tuesday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('tuesday_end', trans('auth.tuesday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.tuesday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('tuesday_status') ? 'is-invalid' :'' }}">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="tuesday_status">
                      <input type="checkbox" id="tuesday_status" name="tuesday_status" class="mdl-switch__input"
                      @if($user->timing->tuesday_status=='on')
                        {{' checked'}}
                              @endif
                      >

                      <span class="mdl-switch__label mdl-textfield__label">Tuesday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.tuesday_status') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('wednesday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[wednesday_start]', NULL, array('id' => 'wednesday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('wednesday_start', trans('auth.wednesday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.wednesday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('wednesday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[wednesday_end]', NULL, array('id' => 'wednesday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('wednesday_end', trans('auth.wednesday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.wednesday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('wednesday_status') ? 'is-invalid' :'' }}">

                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="wednesday_status">
                      <input type="checkbox" id="wednesday_status" name="wednesday_status" class="mdl-switch__input"
                      @if($user->timing->wednesday_status=='on')
                        {{' checked'}}
                        @endif
                      >
                      <span class="mdl-switch__label mdl-textfield__label">Wednesday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.wednesday_status') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('thursday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[thursday_start]', NULL, array('id' => 'thursday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('thursday_start', trans('auth.thursday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.thursday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('thursday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[thursday_end]', NULL, array('id' => 'thursday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('thursday_end', trans('auth.thursday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.thursday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('thursday_status') ? 'is-invalid' :'' }}">

                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="thursday_status">
                      <input type="checkbox" id="thursday_status" name="thursday_status" class="mdl-switch__input"
                      @if($user->timing->thursday_status=='on')
                      {{' checked'}}
                      @endif
                    >
                    <span class="mdl-switch__label mdl-textfield__label">Thursday Status</span>
                  </label>
                  <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.thursday_status') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('friday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[friday_start]', NULL, array('id' => 'friday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('friday_start', trans('auth.friday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.friday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('friday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[friday_end]', NULL, array('id' => 'friday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('friday_end', trans('auth.friday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.friday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('friday_status') ? 'is-invalid' :'' }}">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="friday_status">
                      <input type="checkbox" id="friday_status" name="friday_status" class="mdl-switch__input"
                      @if($user->timing->friday_status=='on')
                        {{' checked'}}
                              @endif
                      >
                      <span class="mdl-switch__label mdl-textfield__label">Friday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.friday_status') }}</span>
                  </div>
                </div>


                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('saturday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[saturday_start]', NULL, array('id' => 'saturday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('saturday_start', trans('auth.saturday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.saturday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('saturday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[saturday_end]', NULL, array('id' => 'saturday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('saturday_end', trans('auth.saturday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.saturday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('saturday_status') ? 'is-invalid' :'' }}">

                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="saturday_status">
                      <input type="checkbox" id="saturday_status" name="saturday_status" class="mdl-switch__input"

                      @if($user->timing->saturday_status=='on')
                        {{' checked'}}
                              @endif
                      >
                      <span class="mdl-switch__label mdl-textfield__label">Saturday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.saturday_status') }}</span>
                  </div>
                </div>

                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('sunday_start') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[sunday_start]', NULL, array('id' => 'sunday_start', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('sunday_start', trans('auth.sunday_start') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.sunday_start') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('sunday_end') ? 'is-invalid' :'' }}">
                    {!! Form::time('timing[sunday_end]', NULL, array('id' => 'sunday_end', 'class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('sunday_end', trans('auth.sunday_end') , array('class' => 'mdl-textfield__label')); !!}
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.sunday_end') }}</span>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('sunday_status') ? 'is-invalid' :'' }}">

                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="sunday_status">
                      <input type="checkbox" id="sunday_status" name="sunday_status" class="mdl-switch__input"
                      @if($user->timing->sunday_status=='on')
                        {{' checked'}}
                              @endif
                      >
                      <span class="mdl-switch__label mdl-textfield__label">Sunday Status</span>
                    </label>
                    <span class="mdl-textfield__error">Please Enter a Valid {{ trans('auth.sunday_status') }}</span>
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
                  {!! Form::button('<i class="material-icons">save</i> Save New Therapist', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                </span>

            </div>
          </div>
        </div>

        <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Save New User')) !!}
              </span>

          <a href="{{ url('/therapists/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users">
            <i class="material-icons">reply</i>
            <span class="sr-only">Back to Therapists</span>
          </a>

        </div>

        @include('dialogs.dialog-save')

        {!! Form::close() !!}

      </div>
    </div>
  </div>




@endsection

@section('footer_scripts')
  <script type="text/javascript">
    mdl_dialog('.dialog-button-save');
    mdl_dialog('.dialog-button-icon-save');
  </script>
  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.check-changed')

@endsection