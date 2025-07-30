@extends('layouts.app')

@section('linked_css')
<link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
@endsection
@section('content')

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Setting Edit</h4>
            <div class="row" style="background-color: white;color: black;">
                <div class="col-12">
                    <form action="{{ url('/setting/update', $setting->id) }}" method="POST">
                        {{-- <fieldset> --}}
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('x_factor') ? 'is-invalid' :'' }}">
                                        <label for="">X Factor</label>
                                        {!! Form::number('x_factor', old('x_factor', $setting->x_factor), array('id' => 'x_factor', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'X Factor','step'=>'0.01')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('user_type') ? 'is-invalid' :'' }}">
                                    <label for="">User Type</label>
                                        {!! Form::text('user_type', old('user_type', $setting->user_type), array('id' => 'user_type', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'User Type')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('low_range') ? 'is-invalid' :'' }}">
                                    <label for="">Low Range</label>   
                                    {!! Form::number('low_range', old('low_range', $setting->low_range), array('id' => 'low_range', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Low Range','step'=>'0.01')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('high_range') ? 'is-invalid' :'' }}">
                                    <label for="">High range</label>   
                                    {!! Form::number('high_range', old('high_range', $setting->high_range), array('id' => 'high_range', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'High Range','step'=>'0.01')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('lead_price') ? 'is-invalid' :'' }}">
                                    <label for="">Lead Price</label>    
                                    {!! Form::number('lead_price', old('lead_price', $setting->lead_price), array('id' => 'lead_price', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Lead Price','step'=>'0.01')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- </fieldset> --}}
                        <div class="pt-3">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->





@endsection
@section('footer_scripts')

@include('scripts.mdl-required-input-fix')


@endsection