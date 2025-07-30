@extends('layouts.app')

@section('linked_css')
    <link rel="stylesheet" src="{{asset('css/nprogress.css') }}">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
@endsection
@section('content')

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">

        </div>
    </div>
    <!-- content-wrapper ends -->

    <!-- Modal -->
@endsection

@section('scripts')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>

        $(document).on("click", ".DeleteQuote", function() {
            if (confirm('Are you sure you want to delete?')) {
                var id = $(this).data('id');
                var url = '{!! url('quotes')!!}'+"/"+id;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    dataType: 'json',
                    data: {
                        id: id,
                        _method: 'DELETE'
                    },
                    beforeSend: function() {
                        $('.DeleteQuote' + id).html('<i class="fas fa-cog fa-spin" aria-hidden="true"></i>');
                    },
                    success: function(response) {
                        if (response.status) {
                            $('.video-index' + id).remove();
                            toastr.clear();
                            toastr.success(response.message, 'Error', {timeOut: 5000});
                        }
                    },
                    complete: function() {
                        $('.DeleteQuote' + id).html('<i class="fas fa-trash-alt" aria-hidden="true"></i>');


                    }
                });
            }
        });

    </script>

    <script type="text/javascript">
        $(document).on("click", ".quote_status_btn", function() {
            var $selected = $('.quote_status').find('option:selected');
            data = {
                '_token':'{{csrf_token()}}',
                'status': $selected.data('val')
            }
            $.ajax({
                type:'GET',
                url:"{{ url('update_quote_status') }}"+'/'+$selected.data('id'),
                data:data,
                success:function(response) {
                    if(response.code == 1){
                        toastr.clear();
                        toastr.success(response.message, 'Error', {timeOut: 5000});

                    }
                }
            });
        });



    </script>
@endsection