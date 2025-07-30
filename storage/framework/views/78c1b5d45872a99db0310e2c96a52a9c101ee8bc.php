<?php $__env->startSection("content"); ?>
<h5 class="ml-4 font-weight-bold">Admin - Manage Blog Posts</h5>
<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6">
        <div class="card m-4">
            <div class="card-body" style="height: 381px;">
                <h5 class="card-title admin-title-limit"><a href="<?php echo e($post->url()); ?>"><?php echo e($post->title); ?></a></h5>
                <!-- <h5 class="card-subtitle mb-2 text-muted"><?php echo e($post->subtitle); ?></h5> -->
                <p class="card-text"><?php echo e($post->html); ?></p>

                <?php echo $post->imageTag('thumbnail', false, 'float-right'); ?>


                <dl>
                    <dt>Author</dt>
                    <dd><?php echo e($post->author_string()); ?></dd>
                    <dt>Posted at</dt>
                    <dd><?php echo e($post->posted_at); ?></dd>
                    <dt>Is published?</dt>
                    <dd>
                        <?php echo ($post->is_published ? "Yes" : '<span class="border border-danger rounded p-1">No</span>'); ?>

                    </dd>

                    <dt>Categories</dt>
                    <dd>
                        <?php if(count($post->categories)): ?>
                        <?php $__currentLoopData = $post->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="btn btn-outline-secondary btn-sm m-1" href="<?php echo e($category->edit_url()); ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                            <?php echo e($category->category_name); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?> No Categories
                        <?php endif; ?>

                    </dd>
                </dl>


                <?php if($post->use_view_file): ?>
                <h5>Uses Custom Viewfile:</h5>
                <div class="m-2 p-1">
                    <strong>View file:</strong><br>
                    <code><?php echo e($post->use_view_file); ?></code>
                    <?php
                    $viewfile = resource_path('views/custom_blog_posts/' . $post->use_view_file . '.blade.php');
                    ?>
                    <br>
                    <strong>Full filename:</strong>
                    <br>
                    <small>
                        <code><?php echo e($viewfile); ?></code>
                    </small>

                    <?php if(!file_exists($viewfile)): ?>
                    <div class="alert alert-danger">Warning! The custom view file does not exist. Create the
                        file for this post to display correctly.
                    </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>


                <a href="<?php echo e(route('view-post',$post->slug)); ?>" class="card-link btn btn-outline-secondary"><i class="fa fa-file-text-o" aria-hidden="true"></i>
                    View Post</a>
                <a href="<?php echo e($post->edit_url()); ?>" class="card-link btn btn-primary">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Edit Post</a>
                <form onsubmit="return confirm('Are you sure you want to delete this blog post?\n You cannot undo this action!');" method='post' action='<?php echo e(route("blogetc.admin.destroy_post", $post->id)); ?>' class='float-right'>
                    <?php echo csrf_field(); ?>
                    <input name="_method" type="hidden" value="DELETE" />
                    <button type='submit' class='btn btn-danger'>
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="alert alert-warning">No posts to show you. Why don't you add one?</div>
    <?php endif; ?>
</div>

<div class="text-center">
    <?php echo e($posts->appends( [] )->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("blogetc_admin::layouts.admin_layout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/index.blade.php ENDPATH**/ ?>