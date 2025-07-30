<div class="form-group">
    <label for="blog_title">Blog Post Title</label>
    <input type="text" class="form-control" required id="blog_title" minlength="35" aria-describedby="blog_title_help" name="title"
           value="<?php echo e(old("title",$post->title)); ?>">
    <small id="blog_title_help" class="form-text text-muted">The title of the blog post</small>
</div>

<div class="form-group">
    <label for="blog_subtitle">Subtitle</label>
    <input type="text" class="form-control" id="blog_subtitle" aria-describedby="blog_subtitle_help" name="subtitle"
           value="<?php echo e(old("subtitle",$post->subtitle)); ?>">
    <small id="blog_subtitle_help" class="form-text text-muted">The subtitle of the blog post (optional)</small>
</div>

<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label for="blog_slug">Blog Post Slug</label>
            <input type="text" class="form-control" id="blog_slug" aria-describedby="blog_slug_help" name="slug"
                   value="<?php echo e(old("slug",$post->slug)); ?>">
            <small id="blog_slug_help" class="form-text text-muted">The slug (leave blank to auto generate) -
                i.e. <?php echo e(route("blogetc.single", "" )); ?>/<u><em>this_part</em></u></small>
        </div>

    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <label for="blog_is_published">Published?</label>

            <select name="is_published" class="form-control" id="blog_is_published"
                    aria-describedby="blog_is_published_help">

                <option <?php if(old("is_published",$post->is_published) == '1'): ?> selected="selected" <?php endif; ?> value="1">
                    Published
                </option>
                <option <?php if(old("is_published",$post->is_published) == '0'): ?> selected="selected" <?php endif; ?> value="0">
                    Not Published
                </option>

            </select>
            <small id="blog_is_published_help" class="form-text text-muted">
                Should this be published? If not, then it won't be publicly viewable.
            </small>
        </div>

    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <label for="blog_posted_at">Posted at</label>
            <input type="text" class="form-control" id="blog_posted_at" aria-describedby="blog_posted_at_help"
                   name="posted_at"
                   value="<?php echo e(old("posted_at",$post->posted_at ?? \Carbon\Carbon::now())); ?>">
            <small id="blog_posted_at_help" class="form-text text-muted">
                When this should be published. If this value is greater than now (<?php echo e(\Carbon\Carbon::now()); ?>)
                then it will not (yet) appear on your blog. Should be in the <code>YYYY-MM-DD HH:MM:SS</code> format.
            </small>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="blog_post_body">Post Body
        <?php if(config("blogetc.echo_html")): ?>
            (HTML)
        <?php else: ?>
             (Html will be escaped)
        <?php endif; ?>

    </label>
    <textarea style="min-height:600px;" class="form-control" id="blog_post_body" aria-describedby="blog_post_body_help"
              name="post_body"><?php echo e(old("post_body",$post->post_body)); ?></textarea>

    <div class="alert alert-danger">
        Please note that any HTML (including any JS code) that is entered here will be
        echoed (without escaping)
    </div>
</div>

<?php if(config("blogetc.use_custom_view_files",true)): ?>
    <div class="form-group">
        <label for="blog_use_view_file">Custom View File</label>
        <input type="text" class="form-control" id="blog_use_view_file" aria-describedby="blog_use_view_file_help"
               name="use_view_file"
               value="<?php echo e(old("use_view_file",$post->use_view_file)); ?>">
        <small id="blog_use_view_file_help" class="form-text text-muted">
            Optional - if anything is entered here, then it will attempt to load
            <code>view("custom_blog_posts." . $use_view_file)</code>. You must create the file in
            <code>/resources/views/custom_blog_posts/FILENAME.blade.php</code>.
        </small>
    </div>
<?php endif; ?>

<div class="form-group">
    <label for="blog_seo_title">SEO: <?php echo e("<title>"); ?> tag (optional)</label>
    <input class="form-control" id="blog_seo_title" aria-describedby="blog_seo_title_help"
           name="seo_title" type="text" value="<?php echo e(old("seo_title",$post->seo_title)); ?>">
    <small id="blog_seo_title_help" class="form-text text-muted">Enter a value for the <?php echo e("<title>"); ?> tag. If nothing is
        provided here we will just use the normal post title from above (optional)</small>
</div>

<div class="form-group">
    <label for="blog_meta_desc">Meta Desc (optional)</label>
    <textarea class="form-control" id="blog_meta_desc" aria-describedby="blog_meta_desc_help"
              name="meta_desc"><?php echo e(old("meta_desc",$post->meta_desc)); ?></textarea>
    <small id="blog_meta_desc_help" class="form-text text-muted">Meta description (optional)</small>
</div>

<div class="form-group">
    <label for="blog_short_description">Short Desc (optional)</label>
    <textarea class="form-control" id="blog_short_description" aria-describedby="blog_short_description_help"
              name="short_description"><?php echo e(old("short_description",$post->short_description)); ?></textarea>
    <small id="blog_short_description_help" class="form-text text-muted">Short description (optional - only useful if
        you use in your template views)</small>
</div>

<?php if(config("blogetc.image_upload_enabled",true)): ?>

    <div class="bg-white pt-4 px-4 pb-0 my-2 mb-4 rounded border">
        <style>
            .image_upload_other_sizes {
                display: none;
            }
        </style>
        <h4>Featured Images</h4>

        <?php
            // TODO - put this logic in controller
            $hasImage = false; ?>
        <?php $__currentLoopData = config("blogetc.image_sizes"); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imageSizeKey =>$imageSizeAttributes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-group mb-4 p-2 <?php echo e($loop->iteration > 1 ? 'image_upload_other_sizes' : ''); ?>">
                <?php if($post->hasImage($imageSizeAttributes['basic_key'])): ?>
                    <?php $hasImage = true; ?>
                    <div style="max-width:55px;" class="float-right m-2">
                        <a style="cursor: zoom-in;" target="_blank"
                           href="<?php echo e($post->image_url($imageSizeAttributes['basic_key'])); ?>">
                            <?php echo $post->imageTag($imageSizeAttributes['basic_key'], false, 'd-block mx-auto img-fluid '); ?>

                        </a>
                    </div>
                <?php endif; ?>

                <label for="blog_<?php echo e($imageSizeKey); ?>">Image - <?php echo e($imageSizeAttributes['name']); ?> (optional)</label>
                <small id="blog_<?php echo e($imageSizeKey); ?>_help" class="form-text text-muted">
                    Upload <?php echo e($imageSizeAttributes['name']); ?> image
                    
                     - it will
                    get automatically resized if larger
                </small>
                <input class="form-control" type="file" name="<?php echo e($imageSizeKey); ?>" id="blog_<?php echo e($imageSizeKey); ?>"
                       aria-describedby="blog_<?php echo e($imageSizeKey); ?>_help">

                <?php if($hasImage): ?>
                    <a href="<?php echo e(route('blogetc.admin.images.delete-post-image', $post)); ?>" class="btn btn-xs btn-danger" target="_blank">Delete images</a>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <p>
            By default it will resize for all images based on the first image. If you want to select specific images for
            each size, please click:
            <span class="btn btn-light btn-sm"
                    onclick='$(this).parent().hide(); $(".image_upload_other_sizes").slideDown()'>
                Show other sizes
            </span>
        </p>

    </div>
<?php else: ?>
    <div class="alert alert-warning">Image uploads were disabled in blogetc.php config</div>
<?php endif; ?>

<div class="bg-white pt-4 px-4 pb-0 my-2 mb-4 rounded border">
    <h4>Categories:</h4>
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = \WebDevEtc\BlogEtc\Models\Category::orderBy("category_name","asc")->limit(1000)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="form-check col-sm-6">
                <input type="checkbox" value="1"
                       <?php if(old("category.".$category->id, $post->categories->contains($category->id))): ?> checked="checked"
                       <?php endif; ?> name="category[<?php echo e($category->id); ?>]" id="category_check<?php echo e($category->id); ?>">
                <label class="form-check-label" for="category_check<?php echo e($category->id); ?>">
                    <?php echo e($category->category_name); ?>

                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-md-12">
                No categories
            </div>
        <?php endif; ?>

        <div class="col-md-12 my-3 text-center">

            <em>
                <a target="_blank" href="<?php echo e(route("blogetc.admin.categories.create_category")); ?>">
                    <i class="fa fa-external-link" aria-hidden="true"></i>
                    Add new categories here
                </a>
            </em>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/posts/form.blade.php ENDPATH**/ ?>