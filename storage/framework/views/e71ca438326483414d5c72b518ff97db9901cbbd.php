<?php $__env->startSection('content'); ?>
    <div class="text-center">
        <h3>
            Thanks! Your comment has been saved!
        </h3>

        <?php if(!config('blogetc.comments.auto_approve_comments', false)): ?>
            <p>
                After an admin user approves the comment, it'll appear on the site!
            </p>
        <?php endif; ?>

        <a href="<?php echo e($blog_post->url()); ?>" class="btn btn-primary">
            Back to blog post
        </a>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app',['title'=>'Saved comment'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/saved_comment.blade.php ENDPATH**/ ?>