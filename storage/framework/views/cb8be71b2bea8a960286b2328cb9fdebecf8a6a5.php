<?php
$recentPosts =  \WebDevEtc\BlogEtc\Models\Post::where('is_published',1)->orderBy('id','Desc')->take(5)->get(['id','title','slug']);
$recentCategories =  \WebDevEtc\BlogEtc\Models\Category::orderBy('id','Desc')->take(5)->get();
?>
<div class="col-md-4 pl-5 pr-5 mt-5">
    <div class="widget">
        <form method="get" action="<?php echo e(route('blogetc.search')); ?>" class="text-center">
        <div class="form-group blog-search-box">
                <input type="input" class="form-control" id="" aria-describedby="" placeholder="Search..." name="s" value="<?php echo e(Request::get('s')); ?>">
            <button type="submit" value="Search" class="btn btnBlogSearch"><i class="fa fa-search" aria-hidden="true"></i></button>
            
        </div>
        </form>
    </div>
    <div class="recent-posts">
        <h5 class="widget-title">
            Recent Posts
        </h5>
        <ul>
            <?php if(isset($recentPosts) && !is_null($recentPosts)): ?>
            <?php $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e(url('blog',$recentPost->slug)); ?>"><?php echo e($recentPost->title); ?></a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
        </ul>
    </div>
    <div class="category-list mt-5">
        <h5 class="widget-title">Categories</h5>
        <ul>
            <?php if(isset($recentCategories) && !is_null($recentCategories)): ?>
            <?php $__currentLoopData = $recentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e(url('blog/category',$recentCategory->slug)); ?>"><?php echo e($recentCategory->category_name); ?></a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
        </ul>
    </div>
</div>
<?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/partials/side_bar.blade.php ENDPATH**/ ?>