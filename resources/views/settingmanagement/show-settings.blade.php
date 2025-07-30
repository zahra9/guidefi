@extends('layouts.dashboard')

@section('template_title')
    Showing Setting
@endsection

@section('template_linked_css')
@endsection

@section('header')
    Showing All Settings
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
    <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/settings" disabled>
            <span itemprop="name">
            All Settings List
            </span>
        </a>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    @if(!is_null($currentuser->corporateclient)  && $currentuser->corporateclient->payment_method=='Credit Card')

        <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h2 class="mdl-card__title-text logo-style">
                @if (sizeof($cards) === 1)
                    {{ sizeof($cards) }} Credit Cards total
                @elseif (sizeof($cards) > 1)
                    {{ sizeof($cards) }} Total Credit Cards
                @else
                    No Credit Card
                @endif
            </h2>

        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                        <th class="mdl-data-table__cell--non-numeric">Name On Card</th>
                        <th class="mdl-data-table__cell--non-numeric">Card Number</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Expiry Month</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Expiry Year</th>
                        <th class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">Is Default</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0 @endphp

                    @foreach ($cards as $card)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('/editcard/' . $card->id) }}">{{$i+1}}</a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('/editcard/' . $card->id) }}">{{$card->name_on_card}} </a></td>
                            <td class="mdl-data-table__cell--non-numeric"><a href="{{ URL::to('/editcard/' . $card->id) }}">{{$card->card_number}} </a></td>

                            <td class="mdl-data-table__cell--non-numeric mdl-layout--large-screen-only">
                                {{$card->expiry_month}}
                            </td>

                            <td class="mdl-data-table__cell--non-numeric">{{$card->expiry_year}}</td>
                            <td class="mdl-data-table__cell--non-numeric">
                                {{-- Status ICON BUTTON AND FORM CALL --}}
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for={{$card->id}} >
                                    <input type="checkbox" id="{{$card->id}}" class="mdl-switch__input" {{  $card->is_default === 1 ? "checked" : '' }}  />
                                    <!-- attribute checked -->

                                </label>
                            </td>
                        </tr>
                        @php $i++ @endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mdl-card__menu" style="top: -4px;">

            @include('partials.mdl-highlighter')

            @include('partials.mdl-search')

            <a href="{{ url('/createcard') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New Credit Card">
                <i class="material-icons">credit_card</i>
                <span class="sr-only">Add New Credit Card</span>
            </a>



        </div>

    </div>
   @endif
   @if(!is_null($currentuser->corporateclient)  && $currentuser->corporateclient->payment_method=='Prepaid Bank Deposit')
       <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop margin-top-0">
           <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
               <h2 class="mdl-card__title-text logo-style">
                   {{count($allpayments)}} Payments
               </h2>

           </div>
           <div class="mdl-card__supporting-text mdl-color-text--grey-600 padding-0 context">
               <div class="table-responsive material-table">
                   <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                       <thead>
                       <tr>
                           <th class="mdl-data-table__cell--non-numeric">Sr. No</th>
                           <th class="mdl-data-table__cell--non-numeric">Payment Date</th>
                           <th class="mdl-data-table__cell--non-numeric">Amount</th>


                       </tr>
                       </thead>
                       <tbody>
                       @php $i=0 @endphp

                       -     @foreach ($allpayments as $payment)


                           <tr>
                               <td class="mdl-data-table__cell--non-numeric">{{$i+1}}</td>
                               <td class="mdl-data-table__cell--non-numeric">{{$payment->payment_date}}</td>
                               <td class="mdl-data-table__cell--non-numeric">${{number_format($payment->total_amount, 2)}}</td>

                           </tr>
                           @php $i++ @endphp
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

       @endif
    {{--
    <div class="mdl-grid full-grid margin-top-0 padding-0">
        <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
            <div class="mdl-card card-new-user" style="width:100%;" itemscope itemtype="http://schema.org/Person">

                <div class="mdl-card__title mdl-card--expand mdl-color--primary mdl-color-text--white">
                    <h2 class="mdl-card__title-text logo-style">Billing Address</h2>
                </div>

                {!! Form::open(array('action' => 'SettingController@billingaddress', 'method' => 'POST', 'role' => 'form')) !!}

                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid full-grid padding-0">
                        <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">

                            <div class="mdl-grid ">

                                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('Street_address') ? 'is-invalid' :'' }}">
                                        {!! Form::text('Street_address', NULL, array('id' => 'Street Address', 'class' => 'mdl-textfield__input')) !!}
                                        {!! Form::label('Street_address', 'Street Address' , array('class' => 'mdl-textfield__label')); !!}
                                        <span class="mdl-textfield__error">Please Provide Billing Address</span>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('city') ? 'is-invalid' :'' }}">
                                        {!! Form::number('city', NULL, array('id' => 'price', 'class' => 'mdl-textfield__input')) !!}
                                        {!! Form::label('city', 'City' , array('class' => 'mdl-textfield__label')); !!}
                                        <span class="mdl-textfield__error">Please Enter a Valid City</span>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('state') ? 'is-invalid' :'' }}">
                                        {!! Form::text('state', NULL, array('id' => 'state', 'class' => 'mdl-textfield__input')) !!}
                                        {!! Form::label('state', 'State' , array('class' => 'mdl-textfield__label')); !!}
                                        <span class="mdl-textfield__error">Please Enter a Valid State</span>
                                    </div>
                                </div>

                                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('country') ? 'is-invalid' :'' }}">
                                        {!! Form::text('country', NULL, array('id' => 'country', 'class' => 'mdl-textfield__input')) !!}
                                        {!! Form::label('country', 'Country' , array('class' => 'mdl-textfield__label')); !!}
                                        <span class="mdl-textfield__error">Please Enter a Valid Country</span>
                                    </div>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--6-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('phone') ? 'is-invalid' :'' }}">
                                        {!! Form::number('phone', NULL, array('id' => 'phone', 'class' => 'mdl-textfield__input')) !!}
                                        {!! Form::label('phone', 'Phone No' , array('class' => 'mdl-textfield__label')); !!}
                                        <span class="mdl-textfield__error">Please Enter a Valid Phone NO</span>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>

                <div class="mdl-card__actions padding-top-0">
                    <div class="mdl-grid padding-top-0">
                        <div class="mdl-cell mdl-cell--12-col padding-top-0 margin-top-0 margin-left-1-1">


                            <span class="save-actions">
                  {!! Form::button('<i class="material-icons">save</i> Update Billing Address', array('class' => 'dialog-button-save mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--green mdl-color-text--white mdl-button--raised margin-bottom-1 margin-top-1 margin-top-0-desktop margin-right-1 padding-left-1 padding-right-1 ')) !!}
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
--}}
                @include('dialogs.dialog-save')

                {!! Form::close() !!}

            </div>
        </div>
    </div>


@endsection

@section('footer_scripts')
    @include('scripts.highlighter-script')
    @include('scripts.mdl-datatables')
    <script type="text/javascript">
        $( document ).ready(function() {
            @foreach ($cards as $a_user)
            $('#{{$a_user->id}}').change(function() {
                if(this.checked) {
                    var returnVal = confirm("Are you sure to Make Default This Card?");
                    $(this).prop("checked", returnVal);
                    var id = {{$a_user->id}};
                    var status = 1;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/settings/makedefault',

                        data:{"id":id, "status":1,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert(data.success);
                            location.reload();

                        }

                    });
                }
                else{
                    //var returnVal = confirm("Are you sure to Un Block this User?");
                    //$(this).prop("", returnVal);
                    var id = {{$a_user->id}};
                    var status = 0;
                    $('#{{$a_user->id}}').val(this.checked);
                    $.ajax({

                        type:'POST',

                        url:'/settings/makedefault',

                        data:{"id":id, "status":0,"_token": "{{ csrf_token() }}"},

                        success:function(data){

                            alert("Card is removed from default.");

                        }

                    });
                }

            });
            @endforeach
        });

    </script>
@endsection
