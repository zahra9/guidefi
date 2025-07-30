@extends('layouts.dashboard')

@section('template_title')
  Create New User
@endsection

@section('header')
  Create New Corporate User
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

    <a itemprop="item" href="/corporateusers">
      <span itemprop="name">
        Corporate Users List
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">

    <a itemprop="item" href="/corporateusers/create">
      <span itemprop="name">
        Create New Corporate User

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
          <h2 class="mdl-card__title-text logo-style">Create New Corporate User</h2>
        </div>

        {!! Form::open(array('action' => 'CorporateClientController@store', 'method' => 'POST', 'role' => 'form')) !!}

          <div class="mdl-card__supporting-text">
            <div class="mdl-grid full-grid padding-0">
              <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                <div class="mdl-grid ">

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('company_name') ? 'is-invalid' :'' }}">
                      {!! Form::text('company_name', NULL, array('id' => 'company_name', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('company_name', 'Company Name' , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Company Name</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('company_phone') ? 'is-invalid' :'' }}">
                      {!! Form::text('company_phone', NULL, array('id' => 'company_phone', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('company_phone', 'Company Phone', array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Company Phone No</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label 'is-invalid'">
                      {!! Form::text('company_address', NULL, array('id' => 'company_address', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('company_address', 'Company Address', array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Valid Company Address</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('contact_person_name') ? 'is-invalid' :'' }}">
                      {!! Form::text('contact_person_name', NULL, array('id' => 'contact_person_name', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('contact_person_name', "Contact Person Name" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Contact Person Name</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('contact_person_phone') ? 'is-invalid' :'' }}">
                      {!! Form::text('contact_person_phone', NULL, array('id' => 'contact_person_phone', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('contact_person_phone', 'Contact Person Phone', array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Company Phone No</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label 'is-invalid'">
                      {!! Form::text('contact_person_address', NULL, array('id' => 'contact_person_address', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('contact_person_address', 'Contact Person Address', array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Contact Person Address</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                      {!! Form::email('email', NULL, array('id' => 'email', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('email', "Email" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Enter a Valid User Name Should Be Valid Email</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('annonymity') ? 'is-invalid' :'' }}">
                      <select class="mdl-selectfield__select mdl-textfield__input" name="annonymity" id="annonymity">
                       <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                      <label for="role">
                        <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                      </label>
                      {!! Form::label('annonymity', "Annonymity", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                      <span class="mdl-textfield__error">Select Annonymity</span>

                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('is_corp_email') ? 'is-invalid' :'' }}">
                      <select class="mdl-selectfield__select mdl-textfield__input" name="is_corp_email" id="is_corp_email">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                      <label for="is_corp_email">
                        <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                      </label>
                      {!! Form::label('is_corp_email', "Do you want to use company's email address for signup?", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                      <span class="mdl-textfield__error">Select Company's email address for signup</span>

                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">
                      <select class="mdl-selectfield__select mdl-textfield__input" name="payment_method" id="payment_method">
                        <option value=""></option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Prepaid Bank Deposit">Prepaid Bank Deposit</option>
                      </select>
                      <label for="role">
                        <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                      </label>
                      {!! Form::label('payment_method', "Payment method", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                      <span class="mdl-textfield__error">Select Payment Method</span>

                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>

                  {!! Form::hidden('role', $roles[0]->id, array('id' => 'role', 'readonly' => 'readonly', 'class' => 'mdl-textfield__input')) !!}

                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--5-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name_on_card') ? 'is-invalid' :'' }}">
                      {!! Form::text('name_on_card', NULL, array('id' => 'name_on_card', 'max'=>"9999999999999999",'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('name_on_card', "Name on Card" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Name on Card</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--5-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('card_no') ? 'is-invalid' :'' }}">
                      {!! Form::number('card_no', NULL, array('id' => 'card_no', 'max'=>"9999999999999999",'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('card_no', "Credit Card No" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Valid Credit Card</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--7-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--2-col-tablet mdl-cell--1-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('cvv') ? 'is-invalid' :'' }}">
                      {!! Form::number('cvv', NULL, array('id' => 'cvv', 'max'=>"9999",'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('cvv', "CVV No" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Valid CVV No</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--11-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--2-col-tablet mdl-cell--2-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('expire_month') ? 'is-invalid' :'' }}">
                      <select class="mdl-selectfield__select mdl-textfield__input" name="expire_month" id="expire_month">
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>


                      </select>
                      <label for="role">
                        <i class="mdl-icon-toggle__label material-icons">arrow_drop_down</i>
                      </label>
                      {!! Form::label('expire_month', "Expire Month", array('class' => 'mdl-textfield__label mdl-selectfield__label')); !!}
                      <span class="mdl-textfield__error">Select Expire Month</span>

                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--10-col-desktop creditcardinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--2-col-tablet mdl-cell--2-col-desktop creditcardinfo">
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
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop prepaidpaymentinfo">
                    <div class="datepicker mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('prepaid_amount') ? 'is-invalid' :'' }}">

                      <input type="number" required placeholder="0.00" name="prepaid_amount" min="0" value="0" step="0.01" class="mdl-textfield__input" title="Amount" pattern="^\d+(?:\.\d{1,2})?$" onblur="
this.parentNode.parentNode.style.backgroundColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'inherit':'red'
">
                      {!! Form::label('prepaid_amount', "Amount($)" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Valid Amount</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop prepaidpaymentinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('payment_date') ? 'is-invalid' :'' }}">
                      {!! Form::text('payment_date', NULL, array('id' => 'payment_date', 'class' => 'mdl-textfield__input')) !!}
                      {!! Form::label('payment_date', "Payment Date" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Payment Date</span>
                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop prepaidpaymentinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select mdl-select__fullwidth {{ $errors->has('payment_method') ? 'is-invalid' :'' }}">


                    </div>
                  </div>
                  <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop prepaidpaymentinfo">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('notes') ? 'is-invalid' :'' }}">
                      <textarea class="mdl-textfield__input" type="text" rows= "5" id="notes" name="notes" ></textarea>

                      {!! Form::label('notes', "Notes" , array('class' => 'mdl-textfield__label')); !!}
                      <span class="mdl-textfield__error">Please Add Notes</span>
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
                  {!! Form::button('<i class="material-icons">save</i> Save New User', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
                </span>

              </div>
            </div>
          </div>

            <div class="mdl-card__menu mdl-color-text--white">

              <span class="save-actions">
                {!! Form::button('<i class="material-icons">save</i>', array('class' => 'dialog-button-icon-save mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect', 'title' => 'Save New User')) !!}
              </span>


              <a href="{{ url('/corporateusers/') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Back to Users">

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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
      $( function() {

          $( "#payment_date" ).on( "change", function() {
              $( "#payment_date" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
          });

      } );
  </script>
  <script>
      $(function() {
          $( "#payment_date" ).datepicker( );

      });

  </script>
  <script type="text/javascript">
    mdl_dialog('.dialog-button-save');
    mdl_dialog('.dialog-button-icon-save');

    $("input[name='company_phone']").keyup(function() {
      $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1)$2-$3"));
    });

    $("input[name='contact_person_phone']").keyup(function() {
      $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1)$2-$3"));
    });
    $('select#payment_method').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if(valueSelected==='Credit Card')
        {

            $('.creditcardinfo').hide();
            $('.prepaidpaymentinfo').hide();
        }
        else if(valueSelected==='Prepaid Bank Deposit'){
            $('.creditcardinfo').hide();
            $('.prepaidpaymentinfo').hide();
        }
        else {
            $('.creditcardinfo').hide();
            $('.prepaidpaymentinfo').hide();

        }

    });
  </script>

@endsection
