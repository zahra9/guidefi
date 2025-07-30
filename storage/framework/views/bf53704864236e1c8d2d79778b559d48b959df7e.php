<?php $__env->startSection('linked_css'); ?>
    <link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                <?php echo e(link_to_route('quotes.create', 'Add New', [], ['class' => 'btn video btn-info btn-lg'])); ?>


                    <h4 class="card-title font-weight-bold">Quotes</h4>
                    

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php if($quotes->count()): ?>
                                    <?php $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-black video-index<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($quote->title); ?></td>
                                            <td style="white-space: inherit;">
                                            <?php echo e($quote->description); ?>

                                            </td>
                                            <td><?php echo e(!is_null($quote->user) ?$quote->user->name:''); ?></td>

                                            <td><?php echo e(!is_null($quote) ? \Carbon\Carbon::parse($quote->created_at)->format('M-d-Y'): '-- -- --'); ?></td>

                                            <td>

                                            <select class="form-control quote_status<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>" name="status">
                                                    <option value="">Select</option>
                                                <?php $__currentLoopData = $privacyStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($quote->admin_status == $status): ?>
                                                <option data-id="<?php echo e($quote->id); ?>" selected="selected" data-val="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                                                <?php else: ?>
                                                <option data-id="<?php echo e($quote->id); ?>"  data-val="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </select>
                                        </td>
                                            <td>
                                                <button id="admin-status" class="btn <?php echo e(($quote->admin_status == 'approved')?'btn-success':'btn-danger'); ?> quote_status_button quote_status_button<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>"
                                                        data-id="<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>">
                                                    <i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                                <a href="<?php echo e(route('quotes.edit', $quote->id)); ?>" style="cursor: pointer;color:#FE0957;font-size: 17px;" ><i class="fas fa-edit"></i> </a>
                                                <a title="Delete" style="cursor: pointer;color:#FE0957;font-size: 17px;" class="DeleteQuote  DeleteQuote<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>" data-id="<?php echo e(!is_null($quote->id) ? $quote->id : ''); ?>"><i class="ti-trash"></i></a>

                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

        $(document).on("click", ".DeleteQuote", function() {
            if (confirm('Are you sure you want to delete?')) {
                var id = $(this).data('id');
                var url = '<?php echo url('quotes'); ?>'+"/"+id;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
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
                        }
                    },
                    error:function (response){
                        console.log('error');
                        console.log(response);
                    },

                });
            }
        });

    </script>

    <script>
        $(function() {
            $('.toggle-event').change(function() {
                var $status = $(this).prop('checked');
                var $id = $(this).data('id');
                data = {
                    '_token':'<?php echo e(csrf_token()); ?>',
                    'status': $status
                }
                $.ajax({
                    type:'GET',
                    url:"<?php echo e(url('update_quote_status')); ?>"+'/'+$id,
                    data:data,
                    success:function(response) {
                        if(response.code == 1){
                            toastr.clear();
                            toastr.success(response.message, 'Error', {timeOut: 5000});
                        }
                    }
                });
            })
        })
        
    </script>
<script>
    $(document).on("click", ".quote_status_button", function(){
        var $id = $(this).data('id');
        var $selected = $('.quote_status'+$id).find('option:selected');
        var data = {
            '_token':'<?php echo e(csrf_token()); ?>',
            'status': $selected.data('val')
        };
        $.ajax({
            type:'POST',
            url:"<?php echo e(url('update_quote_status')); ?>"+'/'+$id,
            data:data,
            beforeSend: function() {
                $('.quote_status_button'+$id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            },
            success:function(response)
            {
                if(response.code == 1){
                    $('.quote_status_button'+$id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);
                    if(response.status == 'approved'){
                        $('.quote_status_button'+$id).removeClass('btn-danger');
                        $('.quote_status_button'+$id).addClass('btn-success');
                    }else{
                        $('.quote_status_button'+$id).removeClass('btn-success');
                        $('.quote_status_button'+$id).addClass('btn-danger');
                    }
                }else{

                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/quotes/index.blade.php ENDPATH**/ ?>