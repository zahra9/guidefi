<?php $__env->startSection('content'); ?>
<div class="card mb-5">
    <div class="card-body">
        <h5 class="font-weight-normal mb-5">Admin - Add post</h5>

        <form method="POST" action="<?php echo e(route('post-store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make("blogetc_admin::posts.form", ['post' => new \WebDevEtc\BlogEtc\Models\Post()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <input type="submit" class="btn btn-primary" value="Add new post">
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/posts/add_post.blade.php ENDPATH**/ ?>