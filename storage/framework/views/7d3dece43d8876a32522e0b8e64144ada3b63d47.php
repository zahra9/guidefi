<?php
    /** @var \WebDevEtc\BlogEtc\Models\Post $post */
?>



<div class="col-md-4 blog-post">
    <article >
        <div>
            <?php if(!is_null($post->image_medium) && file_exists(public_path().'/blog_images/'.$post->image_medium)): ?>
                <a href="<?php echo e($post->url()); ?>">
                    <img src="<?php echo e(asset('blog_images/'.$post->image_medium)); ?>" width="100%" alt="<?php echo e($post->title); ?>" class="">
                </a>
            <?php else: ?>
                <a href="<?php echo e($post->url()); ?>"><img src="<?php echo e(asset('assets/images/blog-dummy.png')); ?>" width="100%" alt=""></a>
            <?php endif; ?>
        </div>
        <div class="entry-content-wrapper "style="height: 210px; overflow:hidden;">
            <div class="entry-meta-top">
                <span class="entry-meta-data"><?php echo e(isset($post->created_at) ? $post->created_at->format('M d, Y') : ''); ?></span>
                <span class="entry-meta-category">
                    <?php if(isset($post->categories->first()->slug) && !is_null($post->categories->first()->slug)): ?>
                        <a href="<?php echo e(url('blog/category',$post->categories->first()->slug)); ?>"><?php echo e(isset($post->categories->first()->category_name) ? $post->categories->first()->category_name : ""); ?></a>
                    <?php endif; ?>
                </span>
            </div>
            <h4 class="entry-title">
                <a href="<?php echo e($post->url()); ?>"><?php echo e(isset($post->title) ? str_limit($post->title , 40) : ''); ?></a>
            </h4>
            <div class="entry-excerpt">
                <?php echo isset($post->post_body) ? $post->post_body : ''; ?>

            </div>
        </div>
    </article>
</div>

<?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/partials/index_loop.blade.php ENDPATH**/ ?>