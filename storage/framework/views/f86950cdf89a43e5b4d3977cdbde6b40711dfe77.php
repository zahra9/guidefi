<?php $__env->startSection('title','Blog Etc Admin - Upload Images'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <h5 class="mb-5 font-weight-normal">Admin - Upload Images</h5>

            <p>You can use this to upload images.</p>
            <form method="post" action="<?php echo e(route("blog-images-store")); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group mb-4 p-2">
                    <label for="image_title">Image title</label>
                    <small id="image_title_help" class="form-text text-muted">Image Title</small>
                    <input required class="form-control" type="text" name="image_title" id="image_title" aria-describedby="image_title_help">
                </div>
                <div class="form-group mb-4 p-2">
                    <label for="upload">Upload image</label>
                    <small id="blog_upload_help" class="form-text text-muted">Upload image</small>
                    <input required class="form-control" type="file" name="upload" id="upload" aria-describedby="upload_help">
                </div>

                <div class="form-group mb-4 p-2">
                    <label>Sizes to convert to</label>
                    <div>
                        <input type="checkbox" name="sizes_to_upload[blogetc_full_size]" value="true" checked id="size_blogetc_full_size">
                        <label for="size_blogetc_full_size">Full size (no resizing)</label>
                    </div>
                    <?php $__currentLoopData = (array)config('blogetc.image_sizes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size => $image_size_details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <input type="checkbox" name="sizes_to_upload[<?php echo e($size); ?>]" value="true" checked id="size_<?php echo e($size); ?>">
                            <label for="size_<?php echo e($size); ?>"><?php echo e($image_size_details['name']); ?> - <?php echo e($image_size_details['w']); ?>

                                x <?php echo e($image_size_details['h']); ?>px</label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="form-group mb-4 p-2">
                    <!--                <label>Save</label>-->
                    <input type="submit" class="btn btn-primary" value="Upload">
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/imageupload/create.blade.php ENDPATH**/ ?>