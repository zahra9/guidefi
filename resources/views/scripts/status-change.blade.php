<script type="text/javascript">
    $(document).on("click", ".video_status_button", function(){
        var $id = $(this).data('id');
        var $selected = $('.video_status'+$id).find('option:selected');
        data = {
            '_token':'{{csrf_token()}}',
            'status': $selected.data('val'),
        };
        $.ajax({
            type:'GET',
            url:"{{ url('update_video_status') }}"+'/'+$id,
            data:data,
            beforeSend: function() {
                $('.video_status_button'+$id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

            },
            success:function(response)
            {
                if(response.code == 1){
                    $('.video_status_button'+$id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);
                    if(response.status == 'approved'){
                        $('.video_status_button'+$id).removeClass('btn-danger');
                        $('.video_status_button'+$id).addClass('btn-success');
                    }else{
                        $('.video_status_button'+$id).removeClass('btn-success');
                        $('.video_status_button'+$id).addClass('btn-danger');
                    }

                    if(response.code == 1){
                        NProgress.done();
                    }
                }
            }
        });
    });
</script>