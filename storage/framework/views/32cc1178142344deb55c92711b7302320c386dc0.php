<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
    <style>
        .fa-star{
            color: #e48107;
        }
        .fa-star-half{
            color: #e48107;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- content-wrapper starts -->
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <div>
                    <h4 class="card-title font-weight-bold">Feedback List</h4>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                <tr class="head-color">
                                    <th>Sr #</th>
                                    <th>Client</th>
                                    <th>Advisor</th>
                                    <th>Expertise Level</th>
                                    <th>Availability</th>
                                    <th>Communication</th>
                                    <th>Feedback</th>
                                    <th>Feedback By</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <?php if($feedbacks->count()): ?>
                                    <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-black">
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e(isset($feedback->client) ? $feedback->client->name : ''); ?></td>
                                        <td><?php echo e(isset($feedback->advisor) ? $feedback->advisor->name : ''); ?></td>
                                        <td>
                                            <?php while($feedback->expertise_level>0): ?>
                                                <?php if($feedback->expertise_level >0.5): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-star-half"></i>
                                                <?php endif; ?>
                                                <?php $feedback->expertise_level--; ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td>
                                            <?php while($feedback->availability>0): ?>
                                                <?php if($feedback->availability >0.5): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-star-half"></i>
                                                <?php endif; ?>
                                                <?php $feedback->availability--; ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td>
                                            <?php while($feedback->communication>0): ?>
                                                <?php if($feedback->communication >0.5): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-star-half"></i>
                                                <?php endif; ?>
                                                <?php $feedback->communication--; ?>
                                            <?php endwhile; ?>
                                        </td>
                                        <td><?php echo e($feedback->feedback); ?></td>
                                        <td><?php echo e($feedback->feedback_by); ?></td>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/feedback-list.blade.php ENDPATH**/ ?>