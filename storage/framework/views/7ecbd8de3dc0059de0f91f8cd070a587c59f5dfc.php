<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title font-weight-bold">Advisors List</h4>
                <h6 class="card-title">No. of Advisors not login yet:<?php echo e($noOfNonLogin); ?></h6>
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
                                    <th>State</th>
                                    <th>Join Date</th>

                                </tr>
                                </thead>
                                <tbody>
                                    <?php if($advisors->count()): ?>
                                    <?php $__currentLoopData = $advisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-black">
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e($loop->iteration); ?></a></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->first_name)?$advisor->person->first_name:'-- -- --'); ?></a></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->last_name)?$advisor->person->last_name:'-- -- --'); ?></a></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->email)?$advisor->person->email: '-- -- --'); ?></a></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->phone)?$advisor->person->phone:'-- -- --'); ?></a></td>

                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>">
                                                <?php echo e(!is_null($advisor->person->birth_date) ? \Carbon\Carbon::parse($advisor->person->birth_date)->format('M-d-Y'):'-- -- --'); ?>

                                            </a>
                                        </td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"></a><?php echo e(!is_null($advisor->person->gender)?$advisor->person->gender:'-- -- --'); ?></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"></a><?php echo e(!is_null($advisor->person->age)?$advisor->person->age:'-- -- --'); ?></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"></a><?php echo e(!is_null($advisor->person->race)?$advisor->person->race:'-- -- --'); ?></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->zipcode)?$advisor->person->zipcode:'-- -- --'); ?></a></td>
                                        <td><?php echo e(!is_null($advisor->person) ? \Carbon\Carbon::parse($advisor->person->created_at)->format('M-d-Y'): '-- -- --'); ?></td>
                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>"><?php echo e(!is_null($advisor->person->state)?$advisor->person->state:'-- -- --'); ?></a></td>

                                        <td><a href="<?php echo e(url('/advisor-details', $advisor->id)); ?>">
                                                <?php echo e(!is_null($advisor->person->updated_at)? \Carbon\Carbon::parse($advisor->person->updated_at)->format('M-d-Y'):'-- -- --'); ?>

                                            </a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/advisors.blade.php ENDPATH**/ ?>