<?php $__env->startSection("content"); ?>

    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-8 mt-5">
                <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $search_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('blogetc::partials.index_loop', ['post' => $result->indexable], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-md-12 blog-post">
                            <div class="alert alert-danger">Sorry, but there were no results!</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php echo $__env->make('vendor.blogetc.partials.side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make("home.layouts.master", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/search.blade.php ENDPATH**/ ?>