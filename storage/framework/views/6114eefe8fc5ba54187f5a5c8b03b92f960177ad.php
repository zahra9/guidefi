<?php $__env->startSection('linked_css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title font-weight-bold">Client Requests (<?php echo e($client_requests->count()); ?>)</h4>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                            <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date Of Birth</th>
                                    <th>Gender</th>
                                    <th>Race</th>
                                    <th>Submission Date</th>

                                    <!--                                    <th>Zip Code</th>-->
                                    <th>Email Validation</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if($client_requests->count()): ?>
                                <?php $__currentLoopData = $client_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person) ? $request->user->person->first_name : '-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person) ? $request->user->person->last_name : '-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($request->user)?$request->user->person->email:'-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($request->user)?$request->user->person->phone:'-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person) ?
                                            \Carbon\Carbon::parse($request->user->person->birth_date)->format('M-d-Y'): '-- -- --'); ?></td>

                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person) ? $request->user->person->gender: '-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person) ? $request->user->person->race: '-- -- --'); ?></td>

                                    <td><?php echo e(!is_null($request->user) && !is_null($request->user->person)? \Carbon\Carbon::parse($request->user->person->created_at)->format('M-d-Y'): '-- -- --'); ?></td>

                                
<!--                                    <td><?php echo e(!is_null($request->user)?$request->user->person->zipcode: '-- -- --'); ?></td>-->
                                    
                                    <td>
                                        <?php echo e(($request->user->activated==1)?"Activated":"Pending"); ?>

                                    </td>
                                    <td>
                                        <select class="form-control client_request_status<?php echo e(!is_null($request->id) ? $request->id : ''); ?>" name="status" data-id="<?php echo e($request->id); ?>">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($request->status == $row['value']): ?>
                                            <option selected="selected" data-val="<?php echo e($row['value']); ?>"><?php echo e($row['name']); ?></option>
                                            <?php else: ?>
                                            <option data-id="<?php echo e($request->id); ?>" data-val="<?php echo e($row['value']); ?>"><?php echo e($row['name']); ?></option>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                    <td><button class="btn <?php echo e(($request->user->status =='declined')?'btn-danger':'btn-success'); ?> client_status_button client_status_button<?php echo e(!is_null($request->id) ? $request->id : ''); ?>" data-id="<?php echo e(!is_null($request->id) ? $request->id : ''); ?>"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                    
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
<script type="text/javascript" src="<?php echo e(asset('js/nprogress.js')); ?>"></script>
<script type="text/javascript">
    $(document).on("click", ".client_status_button", function() {
        var $id = $(this).data('id');
        var $selected = $('.client_request_status' + $id).find('option:selected');
        data = {
            '_token': '<?php echo e(csrf_token()); ?>',
            'status': $selected.data('val')
        }
        $.ajax({
            type: 'GET',
            url: "<?php echo e(url('update_client_status')); ?>" + '/' + $id,
            data: data,
            beforeSend: function() {
                $('.client_status_button' + $id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
            },
            success: function(response) {
                $('.client_status_button' + $id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);

                if (response.code == 1) {
                    toastr.clear();
                    toastr.success(response.message, 'Success', {
                        timeOut: 5000
                    });

                    if(response.status =='declined') {
                        $('.client_status_button' + $id).addClass('btn-danger').removeClass('btn-success');
                    }else{
                        $('.client_status_button' + $id).addClass('btn-success').removeClass('btn-danger');

                    }
                    if(response.status =='active'){
                        location.reload();
                    }

                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/client-requests.blade.php ENDPATH**/ ?>