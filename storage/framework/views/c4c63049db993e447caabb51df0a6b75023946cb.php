<?php $__env->startSection('title', 'Edit Category ' . $category->category_name); ?>
<?php $__env->startSection('content'); ?>
<div class="card m-4">
    <div class="card-body">
        <h5>Admin - Edit Category</h5>

        <form method="post" action="<?php echo e(route('blogetc.admin.categories.edit_category', $category->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('patch'); ?>
            <?php echo $__env->make('blogetc_admin::categories.form', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <input type="submit" class="btn btn-primary" value="Save">
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/categories/edit_category.blade.php ENDPATH**/ ?>