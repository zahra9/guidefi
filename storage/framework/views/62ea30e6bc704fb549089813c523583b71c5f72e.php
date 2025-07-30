<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Discovery Call Logs</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client Name</th>
                                    <th>Advisor Name</th>
                                    <th>Type</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if($callLogs->count()): ?>
                                    <?php $__currentLoopData = $callLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $callLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-black">
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($callLog->client->name); ?></td>
                                        <td><?php echo e($callLog->advisor['name']); ?></td>
                                        <td><?php echo e($callLog->type); ?></td>
                                        <td><?php echo e($callLog->start_time); ?></td>
                                        <td><?php echo e($callLog->end_time); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($callLog->created_at)->format('M-d-Y')); ?></td>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/call-log.blade.php ENDPATH**/ ?>