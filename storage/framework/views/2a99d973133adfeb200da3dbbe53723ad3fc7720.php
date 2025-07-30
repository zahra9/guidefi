<?php $__env->startSection('linked_css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Payment Logs</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="col-md-3 user-type-section">
                            <div>
                            <label class="">User Type</label>
                            </div>
                            <div class="payment-sec">
                            <select class="form-control" id="dropdown1">
                                <option value=""></option>
                                <?php if(!is_null($allRoles)): ?>
                                <?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            </div>
                        </div>
                        <div class="table-responsive">

                            <table id="payment-logs" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Period Start</th>
                                    <th>Period End</th>
                                    <th>Invoice</th>
                                    <th>Role</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php if($paymentLogs->count()): ?>
                                    <?php $__currentLoopData = $paymentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-black">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(!is_null($paymentLog->customer_email)?$paymentLog->customer_email:''); ?></td>
                                            <td><?php echo e(!is_null($paymentLog->number)?$paymentLog->number:''); ?></td>
                                            <td><?php echo e(!is_null($paymentLog->plan)?$paymentLog->plan:''); ?></td>
                                            <td>
                                                <?php echo e(Config::get('constants.CURRENCY_SIGN').number_format(!is_null($paymentLog->amount) ? $paymentLog->amount : '0', 2, ',', '.')); ?>


                                            </td>
                                            <td><?php echo e(!is_null($paymentLog->period_start)?\Carbon\Carbon::parse($paymentLog->period_start)->format('M-d-Y'):''); ?></td>
                                            <td><?php echo e(!is_null($paymentLog->period_end)?\Carbon\Carbon::parse($paymentLog->period_end)->format('M-d-Y'):''); ?></td>
                                            <td>
                                                <?php if(!is_null($paymentLog->invoice_pdf)): ?>
                                                    <a href="<?php echo e(url($paymentLog->invoice_pdf)); ?>">
                                                        <i class="ti-download" aria-hidden="true"> </i>
                                                    </a>
                                                <?php else: ?>
                                                    -- -- --
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($paymentLog->role); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                </tbody>
                                <tfoot>
                                <tr class="head-color">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/stripepayment_logs/index.blade.php ENDPATH**/ ?>