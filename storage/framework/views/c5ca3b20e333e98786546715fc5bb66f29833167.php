<?php $__env->startSection("content"); ?>
    <div class="alert alert-success">
        <b>Deleted that post</b>
        <br/>
        <a href="<?php echo e(route('blogetc.admin.index')); ?>" class="btn btn-primary">
            Back to posts overview
        </a>
    </div>

    <?php
    $images_to_delete = [];
    foreach ((array)config('blogetc.image_sizes') as $image_size => $image_size_info) {
        if (!$deletedPost->$image_size) {
            continue;
        }
        $images_to_delete[] = $image_size;
    }?>

    <?php if(count($images_to_delete)): ?>
        <p>However, the following images were <strong>not</strong> deleted:</p>

        <table class="table">
            <thead>
            <tr>
                <th>Image/link</th>
                <th>Filename / filesize</th>
                <th>Full location</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $images_to_delete; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image_size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center">
                        <a href="<?php echo e(asset(config("blogetc.blog_upload_dir","blog_images")."/".$deletedPost->$image_size)); ?>"
                           target="_blank" class="btn btn-primary m-1">
                            view
                        </a>

                        <img alt="Uploaded image" src="<?php echo e(asset(config("blogetc.blog_upload_dir","blog_images")."/".$deletedPost->$image_size)); ?>"
                             width="100" />

                    </td>
                    <td><code><?php echo e($deletedPost->$image_size); ?></code>
                        
                        <?php if(filesize(public_path(config("blogetc.blog_upload_dir","blog_images")."/".$deletedPost->$image_size))): ?>
                            (<?php echo e((round(filesize(public_path(config("blogetc.blog_upload_dir","blog_images")."/".$deletedPost->$image_size)) / 1000 ,1)). " kb"); ?>)
                        <?php endif; ?>
                    </td>
                    <td><code>
                            <small><?php echo e(public_path(config("blogetc.blog_upload_dir","blog_images")."/".$deletedPost->$image_size)); ?></small>
                        </code></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <p>
            Please manually remove those files from the filesystem if desired.
        </p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("blogetc_admin::layouts.admin_layout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/posts/deleted_post.blade.php ENDPATH**/ ?>