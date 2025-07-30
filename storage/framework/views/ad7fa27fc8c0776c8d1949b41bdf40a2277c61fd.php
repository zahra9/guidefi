<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title font-weight-bold">Advisor Applications  (<?php echo e($advisor_requests->count()); ?>) </h4>
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
                                    <th>Age</th>
                                    <th>Race</th>
                                    <th>Zip Code</th>
                                    <th>Submission Date</th>
                                    <th>Email Validation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $advisor_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $advisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!is_null($advisor->user) && !is_null($advisor->user->person)): ?>
                                        <tr class="text-black">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->first_name : '-- -- --'); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->last_name:'-- -- --'); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) ? $advisor->user->email: '-- -- --'); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->phone: '-- -- --'); ?></td>
                                            <td>
                                                <?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? \Carbon\Carbon::parse($advisor->user->person->birth_date)->format('M-d-Y'): '-- -- --'); ?>

                                            </td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->gender: '-- -- --'); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->age: '-- -- --'); ?></td>
                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->race: '-- -- --'); ?></td>

                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? $advisor->user->person->zipcode: '-- -- --'); ?></td>

                                            <td><?php echo e(!is_null($advisor->user) && !is_null($advisor->user->person) ? \Carbon\Carbon::parse($advisor->user->person->created_at)->format('M-d-Y'): '-- -- --'); ?></td>
                                            <td>
                                                <?php echo e(($advisor->user->activated==1)?"Activated":"Pending"); ?>

                                            </td>

                                            <td>
                                                <select class="form-control advisor_request_status advisor_request_status<?php echo e(!is_null($advisor->id) ? $advisor->id : ''); ?>" name="status" data-id="<?php echo e($advisor->id); ?>">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($advisor->status == $row['value']): ?>
                                                            <option selected="selected" data-val="<?php echo e($row['value']); ?>"><?php echo e($row['name']); ?></option>
                                                        <?php else: ?>
                                                            <option data-id="<?php echo e($advisor->id); ?>"  data-val="<?php echo e($row['value']); ?>"><?php echo e($row['name']); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td><button class="btn <?php echo e(($advisor->user->status =='declined')?'btn-danger':'btn-success'); ?> advisor_status_button advisor_status_button<?php echo e(!is_null($advisor->id) ? $advisor->id : ''); ?>" data-id="<?php echo e(!is_null($advisor->id) ? $advisor->id : ''); ?>"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
          $(document).on("click", ".advisor_status_button", function(){
              var $id = $(this).data('id');
              if($id !=='') {
                  var $selected = $('.advisor_request_status' + $id).find('option:selected');
                  data = {
                      '_token': '<?php echo e(csrf_token()); ?>',
                      'status': $selected.data('val')
                  }
                  $.ajax({
                      type: 'GET',
                      url: "<?php echo e(url('update_advisor_status')); ?>" + '/' + $id,
                      data: data,
                      beforeSend: function () {
                          $('.advisor_status_button' + $id).html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

                      },
                      success: function (response) {
                          $('.advisor_status_button' + $id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').prop('disabled', false);
                          if (response.code == 1) {
                              toastr.clear();
                              toastr.success(response.message, 'Success', {timeOut: 5000});
                              if(response.status =='declined') {
                                  $('.advisor_status_button' + $id).addClass('btn-danger').removeClass('btn-success');
                              }else{
                                  $('.advisor_status_button' + $id).addClass('btn-success').removeClass('btn-danger');

                              }
                              if(response.status =='active'){
                                  location.reload();
                              }
                          }
                      }
                  });
              }
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/advisor-requests.blade.php ENDPATH**/ ?>