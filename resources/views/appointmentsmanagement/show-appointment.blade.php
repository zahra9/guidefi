@extends('layouts.dashboard')

@section('template_title')
  Showing User {{ $appointment->reason }}
@endsection

@section('header')
  Showing {{ $appointment->reason }}
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
    <a itemprop="item" href="/appointments">
      <span itemprop="name">
        Appointments List
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
    <meta itemprop="position" content="2" />
  </li>
  <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/users/{{ $appointment->id }}">
      <span itemprop="name">
        {{ $appointment->reason }}
      </span>
    </a>
    <meta itemprop="position" content="3" />
  </li>
@endsection

@section('content')





<div class="mdl-grid full-grid margin-top-0 padding-0">
  <div class="mdl-cell mdl-cell mdl-cell--12-col mdl-cell--12-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop mdl-card mdl-shadow--3dp margin-top-0 padding-top-0">
      <div class="mdl-card card-wide" style="width:100%;" itemscope itemtype="http://schema.org/Person">
      <div class="mdl-user-avatar">

      </div>

        <div class="mdl-card__supporting-text">
        <div class="mdl-grid full-grid padding-0">
          <div class="mdl-cell mdl-cell--12-col-phone mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
              <ul class="demo-list-icon mdl-list">

                  
                  <li class="mdl-list__item mdl-typography--font-light">
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">event</i>
                      Time: {{ $appointment->time }}
                    </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                      <div class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-icon">event</i>
                          Date: {{ $appointment->date }}
                      </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                      <div class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-icon">event</i>
                          Type: {{ $appointment->type }}
                      </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                      <div class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-icon">person</i>
                          Therapist: {{ $appointment->therapist->fullname }}
                      </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                      <div class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-icon">person</i>
                          Client: {{ $appointment->user->name }}
                      </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                      <div class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-icon">event</i>
                          Created At: {{ $appointment->created_at }}
                      </div>
                  </li>
                  <li class="mdl-list__item mdl-typography--font-light">
                    <div class="mdl-list__item-primary-content">
                      <i class="material-icons mdl-list__item-icon">event</i>
                      Last Updated: {{ $appointment->updated_at }}
                    </div>
                  </li>






        </div>
        </div>

        <div class="mdl-card__menu">



        </div>
      </div>
  </div>
</div>
























@endsection

@section('footer_scripts')


@endsection