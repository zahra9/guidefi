<?php $__env->startSection('content'); ?>
    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card m-4">
            <div class="card-body">
                <h5 class="card-title font-weight-normal"><a href="<?php echo e($category->url()); ?>"><?php echo e($category->category_name); ?></a></h5>
                <a href="<?php echo e($category->url()); ?>" class="card-link btn btn-outline-secondary">
                    View Posts in this category
                </a>
                
                <form
                        onsubmit="return confirm('Are you sure you want to delete this blog post category?\n You cannot undo this action!');"
                        method="post"
                        action="<?php echo e(route("blogetc.admin.categories.destroy_category", $category->id)); ?>"
                        class="float-right">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
                <a href="<?php echo e($category->edit_url()); ?>" class="card-link btn btn-primary float-right mr-4">Edit Category</a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="alert alert-danger">None found, why don't you add one?</div>
    <?php endif; ?>

    <div class="text-center">
        <?php echo e($categories->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/categories/index.blade.php ENDPATH**/ ?>