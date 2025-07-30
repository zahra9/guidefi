<?php $__env->startSection('title', 'BlogEtc - Add Category'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <h5 class="mb-5 font-weight-normal">Admin - Add Category</h5>

        <form method="post" action="<?php echo e(route('blogetc.admin.categories.create_category')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('blogetc_admin::categories.form', ['category' => new \WebDevEtc\BlogEtc\Models\Category()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <input type="submit" class="btn btn-primary" value="Add New Category">
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/categories/add_category.blade.php ENDPATH**/ ?>