<?php $__env->startSection("content"); ?>

<!-- <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="text-center col-sm-4 mx-auto">
        <?php echo e($posts->appends( [] )->links()); ?>

      </div>
      <?php echo $__env->make("blogetc::sitewide.search_form", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
  </div>
  <div class="col-sm-12 blogetc_container">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(\WebDevEtc\BlogEtc\Gates\GateTypes::MANAGE_BLOG_ADMIN)): ?>
    <div class="text-center">
      <p class="mb-1">
        You are logged in as a blog admin user.
        <br>
        <a href="<?php echo e(route("blogetc.admin.index")); ?>" class="btn border btn-outline-primary btn-sm">
          <i class="fa fa-cogs" aria-hidden="true"></i>
          Go To Blog Admin Panel
        </a>
      </p>
    </div>
    <?php endif; ?>
    <div class="row mt-5 mb-5">
      <?php if(isset($blogetc_category) && $blogetc_category): ?>
      <div class="col-md-12  mb-5">
        <h2 class="text-center font-weight-normal">
          Viewing Category: <?php echo e($blogetc_category->category_name); ?>

        </h2>
      </div>
      <?php if($blogetc_category->category_description): ?>
      <p class="text-center"><?php echo e($blogetc_category->category_description); ?></p>
      <?php endif; ?>
      <?php endif; ?>

      <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="col-lg-3 col-md-4">
        <?php echo $__env->make("blogetc::partials.index_loop", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="alert alert-danger">No posts</div>
      <?php endif; ?>

    </div>
  </div>

</div> -->

<div class="container">
  <div class="row ">
    <div class="col-md-8 mt-5">
      <div class="row">
        <?php if(isset($posts) && !is_null($posts)): ?>
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($post->is_published == 1): ?>
        <div class="col-md-4 blog-post">
          <article>
            <div>
              <?php if(!is_null($post->image_medium) && file_exists(public_path().'/blog_images/'.$post->image_medium)): ?>
              <a href="<?php echo e($post->url()); ?>">
                <img src="<?php echo e(asset('blog_images/'.$post->image_medium)); ?>" width="100%" alt="<?php echo e($post->title); ?>" class="">
              </a>
              <?php else: ?>
              <a href="<?php echo e($post->url()); ?>"><img src="<?php echo e(asset('assets/images/blog-dummy.png')); ?>" width="100%" alt=""></a>
              <?php endif; ?>
            </div>
            <div class="entry-content-wrapper " style="height: 210px; overflow:hidden;">
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
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    </div>
    <?php echo $__env->make('vendor.blogetc.partials.side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make("home.layouts.master", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/index.blade.php ENDPATH**/ ?>