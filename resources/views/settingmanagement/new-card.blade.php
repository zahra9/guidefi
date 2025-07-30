@extends('layouts.dashboard')

@section('template_title')
    Setting Page
@endsection

@section('header')
    Credit Card Info
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
        <a itemprop="item" href="/settings">
      <span itemprop="name">
        Setting Page
      </span>
        </a>
        <i class="material-icons">chevron_right</i>
        <meta itemprop="position" content="2" />
    </li>
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/settings">
      <span itemprop="name">
        New Credit Card
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
                    <h2 class="mdl-card__title-text logo-style">Add New Credit Card</h2>
                </div>

                {!! Form::open(array('action' => 'SettingController@savecard', 'method' => 'POST', 'role' => 'form')) !!}

                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid full-grid padding-0">
                        <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                            <div class="mdl-grid card-new-user">
                                    <div class="mdl-card mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-shadow--2dp">

                                        <div class="mdl-card__supporting-text">
                                            <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                                                <div class="mdl-grid ">

                                                    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name_on_card') ? 'is-invalid' :'' }}">
                                                            {!! Form::text('name_on_card', NULL, array('id' => 'name_on_card', 'class' => 'mdl-textfield__input')) !!}
                                                            {!! Form::label('name_on_card', "Name on Card" , array('class' => 'mdl-textfield__label')); !!}
                                                            <span class="mdl-textfield__error">Please Add Name on Card</span>
                                                        </div>
                                                    </div>
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('card_no') ? 'is-invalid' :'' }}">
                                                    {!! Form::number('card_no', NULL, array('id' => 'card_no', 'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::label('card_no', "Credit Card No" , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Add Valid Credit Card</span>
                                                </div>
                                            </div>
                                            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('cvv') ? 'is-invalid' :'' }}">
                                                    {!! Form::number('cvv', NULL, array('id' => 'cvv', 'class' => 'mdl-textfield__input')) !!}
                                                    {!! Form::label('cvv', "CVV No" , array('class' => 'mdl-textfield__label')); !!}
                                                    <span class="mdl-textfield__error">Please Add Valid CVV No</span>
                                                </div>
                                            </div>
                                                    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('expire_month') ? 'is-invalid' :'' }}">
                                                            <select class="mdl-selectfield__select mdl-textfield__input" name="expire_month" id="expire_month">
                                                                <option value="1" >Jan</option>
                                                                <option value="2" >Feb</option>
                                                                <option value="3" >Mar</option>
                                                                <option value="4" >Apr</option>
                                                                <option value="5" >May</option>
                                                                <option value="6" >Jun</option>
                                                                <option value="7" >Jul</option>
                                                                <option value="8" >Aug</option>
                                                                <option value="9" >Sep</option>
                                                                <option value="10" >Oct</option>
                                                                <option value="11" >Nov</option>
                                                                <option value="12" >Dec</option>


                                                            </select>
                                                            <label for="role">
                                                                <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                                                            </label>
                                                            {!! Form::label('expire_month', "Expire Month", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                                                            <span class="mdl-textfield__error">Select Expire Month</span>

                                                        </div>
                                                    </div>
                                                    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('expire_year') ? 'is-invalid' :'' }}">
                                                            <select class="mdl-selectfield__select mdl-textfield__input" name="expire_year" id="expire_year">


                                                                    @foreach($expireyears as $expireyear)
                                                                        <option value="{{ $expireyear }}">{{ $expireyear }}</option>
                                                                    @endforeach

                                                            </select>
                                                            <label for="role">
                                                                <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                                                            </label>
                                                            {!! Form::label('expire_year', "Expire Year", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                                                            <span class="mdl-textfield__error">Select Expire Year</span>
                                                        </div>
                                                    </div>
                                                    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('is_default') ? 'is-invalid' :'' }}">
                                                            <select class="mdl-selectfield__select mdl-textfield__input" name="is_default" id="is_default">
                                                           <option value="0">No</option>
                                                           <option value="1">Yes</option>

                                                            </select>
                                                            <label for="role">
                                                                <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                                                            </label>
                                                            {!! Form::label('is_default', "Is Default", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                                                            <span class="mdl-textfield__error">Select Is Default Option</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               {{-- <div class="mdl-card mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-shadow--2dp">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Learning Web Design</h2>
                                    </div>
                                </div> --}}



















                            </div>
                        </div>

                    </div>
                </div>

                <div class="mdl-card__actions padding-top-0">
                    <div class="mdl-grid padding-top-0">
                        <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">

                            {{-- SAVE BUTTON--}}
                            <span class="save-actions">
                  {!! Form::button('<i class="material-icons">save</i> Save Card', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                </span>

                        </div>
                    </div>
                </div>

                <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Save New Card')) !!}
              </span>

                    <a href="{{ url('/settings/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Settings">
                        <i class="material-icons">reply</i>
                        <span class="sr-only">Back to Setting</span>
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