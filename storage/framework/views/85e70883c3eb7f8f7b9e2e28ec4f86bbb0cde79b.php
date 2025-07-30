
<script>
    //-----------------
    $(document).ready(function(){
        $('#contact_us').submit(function(e){
            e.preventDefault();
            /*Ajax Request Header setup*/
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#send_form').html('Sending..');

            /* Submit form data using ajax*/
            $.ajax({
                url: "<?php echo e(url('connect/user')); ?>",
                method: 'post',
                data: $('#contact_us').serialize(),
                success: function(data) {
                    if (data.status) {
                        toastr.clear();
                        toastr.success(data.message, 'Success', {
                            timeOut: 5000
                        });
                        location.reload(true);
                    } else {
                        toastr.clear();
                        toastr.error(data.message, 'Error', {
                            timeOut: 5000
                        })
                    }
                },
                error: function(xhr) {
                    loadRequestErrors(xhr);
                }});
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
</script><?php /**PATH C:\wamp64\www\guidefi\resources\views/scripts/connect-users.blade.php ENDPATH**/ ?>