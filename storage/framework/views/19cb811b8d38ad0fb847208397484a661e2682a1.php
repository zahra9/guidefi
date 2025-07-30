<?php $__env->startSection('content'); ?>
    <p>Are you sure you want to delete the featured image for the selected post?</p>

    <form method="post" action="<?php echo e(route('blogetc.admin.images.delete-post-image-confirmed', $postId)); ?>">
        <?php echo method_field('DELETE'); ?>
        <?php echo csrf_field(); ?>
        <input type="submit" value="Delete the images" class="btn btn-danger" />
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/imageupload/delete-post-image.blade.php ENDPATH**/ ?>