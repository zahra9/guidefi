@extends('layouts.dashboard')

@section('template_title')
  Showing package {{ $package->name }}
@endsection

@section('header')
  Showing {{ $package->name }}
@endsection



@section('breadcrumbs')
  <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="{{url('/')}}">
      <span itemprop="name">
        Ayana
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
    <a itemprop="item" href="/packages/{{ $package->id }}">
      <span itemprop="name">
        {{ $package->title }}
      </span>
    </a>
    <meta itemprop="position" content="3" />
  </li>
@endsection

@section('content')








<div class="mdl-grid full-grid margin-top-0 padding-0">
  <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
      <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
      <div class="mdl-user-avatar">

      </div>
{{--
        <div class="mdl-card__title" @if ($user->profile->user_profile_bg != NULL) style="background: url('{{$user->profile->user_profile_bg}}') center/cover;" @endif>
            <h3 class="mdl-card__title-text mdl-title-username">
              {{ $user->name }}
            </h3>
        </div>
    --}}
        <div class="mdl-card__supporting-text">
        <div class="mdl-grid full-grid padding-0">
          <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
            <div><h4 style="font-weight: bold;color: black;">Package Detail</h4></div>
              <ul class="demo-list-icon mdl-list">



                  <li class="mdl-list__item mdl-typography--font-light">
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">star</i>
                      <span itemprop="name">
                              {{ $package->title }}&nbsp&nbsp
                      </span>
                    </div>
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon" >attach_money</i>
                      <span itemprop="email">
                     ${{ $package->price }}
                  </span>
                    </div>
                  </li>


                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Package Type:{{ $package->package_type }}
                  </span>
                  </div>
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Text Limit:{{ $package->text_limit }}
                  </span>
                  </div>
                </li>

                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Voice Call Limit:{{ $package->voice_call_limit }}
                  </span>
                  </div>
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Video Call Limit:{{ $package->video_call_limit }}
                  </span>
                  </div>
                </li>

                <li class="mdl-list__item mdl-typography--font-light">
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Voice Call Duration:{{ $package->voice_call_duration }}
                  </span>
                  </div>
                  <div class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon" style="color:green">done</i>
                    <span itemprop="email">
                    Video Call Duration:{{ $package->video_call_duration }}
                  </span>
                  </div>
                </li>



              </ul>

        </div>
        </div>

        <div class="mdl-card__menu">

        <a href="{{ URL::to('packages/' . $package->id . '/edit') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
          <i class="material-icons">edit</i>
        </a>

        {!! Form::open(array('url' => 'packages/' . $package->id, 'class' => 'inline-block')) !!}
          {!! Form::hidden('_method', 'DELETE') !!}
          <a href="#" class="dialog-button-delete mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
            <i class="material-icons">delete</i>
          </a>
          @include('dialogs.dialog-delete')
        {!! Form::close() !!}

        
        </div>
      </div>
  </div>
</div>





























@endsection

@section('footer_scripts')



  <script type="text/javascript">

    mdl_dialog('.dialog-button-delete','.dialog-delete-close','#dialog_delete');

  </script>

@endsection