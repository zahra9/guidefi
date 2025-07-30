<?php
/** @var \WebDevEtc\BlogEtc\Models\Post $post */
?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <h5 class="mb-5">Admin - Editing post
            <a target="_blank" href="<?php echo e(route('view-post',$post->slug)); ?>" class="float-right btn btn-primary">
                View post
            </a>
        </h5>

        <form method="post" action="<?php echo e(route('update-post', $post->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('patch'); ?>
            <?php echo $__env->make('blogetc_admin::posts.form', ['post' => $post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <input type="submit" class="btn btn-primary" value="Save Changes">
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/posts/edit_post.blade.php ENDPATH**/ ?>