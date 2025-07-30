<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                    <?php if(\Session::has('success')): ?>
                        <div class="alert alert-success">
                            <ul>
                                <li><?php echo \Session::get('success'); ?></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                        
                        
                    <button type="button" class="btn add-btn btn-info btn-lg" data-toggle="modal"
                           >Add New
                    </button>
                    <h4 class="card-title font-weight-bold">User Type Range</h4>
                    

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>X Factor</th>
                                    <th>User Type</th>
                                    <th>Low Range</th>
                                    <th>High Range</th>
                                    <th>Lead Price</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                <?php if($settings->count()): ?>
                                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-black">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($setting->x_factor); ?></td>
                                            <td><?php echo e($setting->user_type); ?></td>
                                            <td><?php echo e($setting->low_range); ?></td>
                                            <td><?php echo e($setting->high_range); ?></td>
                                            <td><?php echo e($setting->lead_price); ?></td>
                                            <td>
                                                
                                                
                                                <a href="javascript:void(0)" title="Edit"
                                                   style="color: cadetblue; font-size: 17px;">
                                                    <i class="ti-pencil-alt"></i>
                                                </a>
                                                
                                                
                                                <a href="javascript:void(0)" title="Delete"
                                                   style="font-size: 17px"><i class="ti-trash"></i></a>
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


    <div class="content-wrapper mt-5">

        <div class="card">

            <div class="card-body">
                <div>
                <button type="button" class="btn add-btn-commission btn-info btn-lg" data-toggle="modal"
                            data-target="#myModalCommission">Add New
                    </button>
                    <h4 class="card-title font-weight-bold">Commision</h4>
                    

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>#</th>
                                    <th>Commission</th>
                                    <th>Commission Type</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php if($commissions->count()): ?>
                                    <?php $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-black video-index<?php echo e(!is_null($commission->id) ? $commission->id : ''); ?>">
                                            <td><?php echo e($loop->iteration); ?></td>


                                            <td><?php echo e(prettyPrice(!is_null($commission->commission )?$commission->commission :0)); ?></td>
                                            <td>
                                                <?php echo e(($commission->fixed == 1)?'Fixed':'Percentage'); ?>

                                            </td>
                                            <td>
                                                <input class="toggle-event" type="checkbox" data-toggle="toggle"
                                                       data-onstyle="success" data-id="<?php echo e($commission->id); ?>"
                                                       data-offstyle="danger" data-on="Accept"
                                                       data-off="Decline" <?php echo e(($commission->status == 'active' )?'checked' : ''); ?>>

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

    <!-- Trigger the modal with a button -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Setting</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(url('/setting/store')); ?>" method="POST">
                        <fieldset>
                            <?php echo csrf_field(); ?>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('x_factor') ? 'is-invalid' :''); ?>">
                                    <label for="">X Factor</label>
                                    <?php echo Form::number('x_factor', NULL, array('id' => 'x_factor', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'X Factor','step'=>'0.01')); ?>


                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('user_type') ? 'is-invalid' :''); ?>">
                                    <label for="">User Type</label>
                                    <?php echo Form::text('user_type', NULL, array('id' => 'user_type', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'User Type')); ?>


                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('low_range') ? 'is-invalid' :''); ?>">
                                    <label for="">Low Range</label>
                                    <?php echo Form::number('low_range', NULL, array('id' => 'low_range', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Low Range','step'=>'0.01')); ?>


                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('high_range') ? 'is-invalid' :''); ?>">
                                    <label for="">High Range</label>
                                    <?php echo Form::number('high_range', NULL, array('id' => 'high_range', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'High Range','step'=>'0.01')); ?>


                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('lead_price') ? 'is-invalid' :''); ?>">
                                    <label for="">Lead Price</label>
                                    <?php echo Form::number('lead_price', NULL, array('id' => 'lead_price', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Lead Price','step'=>'0.01')); ?>


                                </div>
                            </div>


                        </fieldset>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="myModalCommission" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    
                    <h4 class="modal-title">Add New Commisssion</h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('commissions.store')); ?>" method="POST" id="commissionForm">
                        <fieldset>
                            <?php echo csrf_field(); ?>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('commission') ? 'is-invalid' :''); ?>">
                                    <label for="">Commission</label>
                                    <?php echo Form::number('commission', NULL, array('id' => 'commission', 'class' => 'mdl-textfield__input', 'required' => 'required','placeholder'=>'Commission')); ?>


                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label <?php echo e($errors->has('commission_type') ? 'is-invalid' :''); ?>">
                                    <label for="">Commission Type</label>
                                    <select class="mdl-textfield__input" name="commission_type">
                                        <option value="">Select</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>

                                </div>
                            </div>
                        </fieldset>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>


<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
               url:"<?php echo e(url('update_commission_status')); ?>"+'/'+$id,
               data:data,
               success:function(response) {
                   if(response.code == 1){
                       toastr.clear();
                       toastr.success(response.message, 'Success', {timeOut: 5000});
                       location.reload();

                   }
               }
           });
       })
   })

   $(document).on("submit","#commissionForm",function (e) {
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
                   $('#commissionForm')[0].reset();
                   location.reload();
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
<?php $__env->stopSection(); ?>








<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/setting-list.blade.php ENDPATH**/ ?>