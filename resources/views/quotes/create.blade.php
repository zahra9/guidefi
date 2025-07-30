
@extends('layouts.app')

@section('content')
    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Create Quotes</h4>
                <div class="row mb-5 mr-3 ml-3 pb-4 " style="background-color: white;color: black;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="col-12">
                            @if(isset($quote))
                                {{ Form::model($quote, ['route' => ['quotes.update', $quote->id], 'method' => 'patch','id' => 'quoteForm']) }}
                            @else
                                {{ Form::open(['route' => 'quotes.store', 'class' => 'form', 'role' => 'form', 'method' => 'post','id' => 'quoteForm']) }}
                            @endif
                           @csrf
                            <div class="row pt-4">
                                <div class="col-md-6">
                                    <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                        <div class=" {{ $errors->has('title') ? 'is-invalid' :'' }}">
                                            <label for="">Title</label>
                                            {!! Form::text('title', old('title', null), ['class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Title','maxlength'=>'50']) !!}
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="col-md-6">
                                    <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                        <div class=" {{ $errors->has('image_url') ? 'is-invalid' :'' }}">
                                            <label for="">Url</label>
                                        {!! Form::text('image_url', old('image_url', null), ['class' => 'mdl-textfield__input', 'placeholder'=>'Image Url','maxlength'=>'50']) !!}

                                        </div>
                                    </div>
                                </div>-->


                            </div>
                            <div class="row pt-4">
                                <div class="col-md-6">
                                    <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                        <div class=" {{ $errors->has('description') ? 'is-invalid' :'' }}">
                                            <label for="">Description</label>
                                            {!! Form::Textarea('description', null,['class' => 'col-md-12 box-shadow','id'=>'editor', 'required' => 'required','placeholder'=>'Title','maxlength'=>'50']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="pt-3 pl-2">
                                {{ link_to_route('quotes.index', 'Cancel', [], ['class' => 'btn btn-primary']) }}
                                {{ Form::submit('Save', ['class' => 'btn btn-success']) }}

                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    @endsection
@section('scripts')
{{--<script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>--}}

<script>
    // CKEDITOR.replace( 'editor' );

    $(document).on("submit","#quoteForm",function (e) {
        e.preventDefault();
        var $f = $(this);
        var $sb = $f.find('[type="submit"]');
        var oldBtnText = $sb.html();

        $.ajax({
            beforeSend: function () {
                $sb.html('<i class="fa fa-spinner fa-spin"></i>Processing').prop('disabled', true);
            },
            url: $f.attr('action'),
            type: $(this).attr('method'),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                $sb.html(oldBtnText).prop('disabled', false);
                if (data.status) {
                    toastr.clear();

                    toastr.success(data.message, 'Success', {timeOut: 5000});
                     var url = '/quotes';

                    window.location.href = url;
                    setTimeout(function(){  }, 5000);
                    $('#quoteForm')[0].reset();
                    // CKEDITOR.instances.textAreaId.setData('');


                }
                else {
                    toastr.clear();
                    toastr.error(data.message, 'Error', {timeOut: 5000});
                    loadRequestErrors(data);
                }
            },
            error: function (xhr) {
                $sb.html(oldBtnText).prop('disabled', false);
                loadRequestErrors(xhr);
            }
        });
    });

    function loadRequestErrors(xhr) {
        if (xhr.message) {
            $.each(xhr.message, function(key, item) {
                toastr.clear();
                toastr.error(item, 'Error', {
                    timeOut: 5000
                });
            });
        } else if (xhr.responseJSON.message) {
            toastr.clear();
            toastr.error(xhr.responseJSON.message, 'Error', {
                timeOut: 5000
            });
        }
    }
</script>
@endsection
