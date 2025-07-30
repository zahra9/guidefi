<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title font-weight-bold">Sign Up Requests</h4>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Financial Professional</th>
                                    <th>Interested In</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <?php if($requests->count()): ?>
                                    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-black">
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($request->name); ?></td>
                                        <td><?php echo e($request->email); ?></td>
                                        <td><?php echo e($request->phone); ?></td>
                                        <td><?php echo e($request->financial_professional); ?></td>
                                        <td><?php echo e($request->interested_in); ?></td>
                                        <td><?php echo e(!is_null($request->created_at)?\Carbon\Carbon::parse($request->created_at)->format('M-d-Y'):''); ?></td>


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


 <?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/sign-up-request-list.blade.php ENDPATH**/ ?>