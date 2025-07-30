<?php
    /** @var \WebDevEtc\BlogEtc\Models\Comment[] $comments */
?>

<?php $__env->startSection('title', 'BlogEtc Manage Comments'); ?>
<?php $__env->startSection('content'); ?>
    <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card m-4">
            <div class="card-body">
                <h5 class="card-title">
                    <?php echo e($comment->author()); ?> commented on:

                    <?php if($comment->post): ?>
                        <a href="<?php echo e($comment->post->url()); ?>"><?php echo e($comment->post->title); ?></a>
                    <?php else: ?>
                        Unknown blog post
                    <?php endif; ?>

                    on <?php echo e($comment->created_at); ?> </h5>

                <p class="m-3 p-2"><?php echo e($comment->comment); ?></p>
                <?php if($comment->post): ?>
                    <a href="<?php echo e($comment->post->url()); ?>" class="card-link btn btn-outline-secondary">
                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        View Post
                    </a>
                    <a href="<?php echo e($comment->post->edit_url()); ?>" class="card-link btn btn-primary">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Edit Post
                    </a>
                <?php endif; ?>

                <?php if(!$comment->approved): ?>
                    
                    <form method="post" action="<?php echo e(route("blogetc.admin.comments.approve", $comment->id)); ?>"
                          class="float-right">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field("PATCH"); ?>
                        <input type="submit" class="btn btn-success" value="Approve"/>
                    </form>
                <?php endif; ?>

                
                <form
                        onsubmit="return confirm('Are you sure you want to delete this blog post comment?\n You cannot undo this action!');"
                        method="post" action="<?php echo e(route("blogetc.admin.comments.delete", $comment->id)); ?>"
                        class="float-right">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field("DELETE"); ?>
                    <input type="submit" class="btn btn-danger mr-2" value="Delete"/>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="alert alert-danger">None found</div>
    <?php endif; ?>

    <div class="text-center">
        <?php echo e($comments->links()); ?>

    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/comments/index.blade.php ENDPATH**/ ?>