<?php $__env->startSection('linked_css'); ?>
<link rel="stylesheet" src="<?php echo e(asset('css/nprogress.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<!-- content-wrapper starts -->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title font-weight-bold">Clients List</h4>
            <h6 class="card-title">No. of Clients not login yet:<?php echo e($noOfNonLogin); ?></h6>

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
                                    <th>Submission Date</th>
                                    <!--                                    <th>Zip Code</th>-->
                                    <th>Asset Level</th>
                                    <th>Income Level</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php if($clients->count()): ?>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black">
                                    
                                    
                                    
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e($loop->iteration); ?></a></td>
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->person->first_name)?$client->person->first_name:'-- -- --'); ?></a></td>
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->person->last_name)?$client->person->last_name:'-- -- --'); ?></a></td>
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->person->email)?$client->person->email: '-- -- --'); ?></a></td>
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->person->phone)?$client->person->phone:'-- -- --'); ?></a></td>

                                    <td>
                                        <a href="<?php echo e(url('/client-detail', $client->id)); ?>">
                                            <?php echo e(!is_null($client->person->birth_date) ? \Carbon\Carbon::parse($client->person->birth_date)->format('M-d-Y'):'-- -- --'); ?>

                                        </a>
                                    </td>
                                    <td><a href="<?php echo e(url('/advisor-details', $client->id)); ?>"></a><?php echo e(!is_null($client->person->gender)?$client->person->gender:'-- -- --'); ?></td>
                                    <td><a href="<?php echo e(url('/advisor-details', $client->id)); ?>"></a><?php echo e(!is_null($client->person->age)?$client->person->age:'-- -- --'); ?></td>
                                    <td><a href="<?php echo e(url('/advisor-details', $client->id)); ?>"></a><?php echo e(!is_null($client->person->race)?$client->person->race:'-- -- --'); ?></td>
                                    <td><?php echo e(!is_null($client->person) ? \Carbon\Carbon::parse($client->person->created_at)->format('M-d-Y'): '-- -- --'); ?></td>

                                <!--                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->person->zipcode)?$client->person->zipcode:'-- -- --'); ?></a></td>-->
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->clientRequest)?$client->clientRequest->assets:'-- -- --'); ?></a></td>
                                    <td><a href="<?php echo e(url('/client-detail', $client->id)); ?>"><?php echo e(!is_null($client->clientRequest)?$client->clientRequest->income_level:'-- -- --'); ?></a></td>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/pages/admin/clients.blade.php ENDPATH**/ ?>