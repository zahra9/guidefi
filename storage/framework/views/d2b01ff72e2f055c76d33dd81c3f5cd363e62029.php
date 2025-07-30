<?php $__env->startSection('content'); ?>

















<div class="container-fluid mt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    
                    
                    
                    
                    
                    

                    <?php if(!is_null($post->image_large) && file_exists(public_path().'/blog_images/'.$post->image_large)): ?>
                        <a href="<?php echo e($post->url()); ?>">
                            <img src="<?php echo e(asset('blog_images/'.$post->image_large)); ?>" width="100%" alt="<?php echo e($post->title); ?>" class="">
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e($post->url()); ?>"><img src="<?php echo e(asset('assets/images/blog-dummy.png')); ?>" width="100%" alt=""></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 ml-auto mr-auto">
                <div>
                    <article>
                        <div class="post_text">
                            <div class="post_meta_top">
                                <span class="post_meta_category">
                                    <?php if(isset($post->categories) && !is_null($post->categories)): ?>
                                        <?php $__currentLoopData = $post->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(url('blog/category',$category->slug)); ?>"><?php echo e(isset($category->category_name) ? $category->category_name : ""); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </span>
                                <span class="post_meta_date"><?php echo e(isset($post->created_at) ? $post->created_at->format('M d, Y') : ''); ?></span>
                            </div>
                            <h2 class="post_title mb-20px"><?php echo e(isset($post) ? $post->title : ''); ?></h2>
                            <div class="post_content">
                            <?php echo e(isset($post->post_body) ? $post->post_body : ''); ?>

                                <?php echo isset($post->post_body) ? $post->post_body : ''; ?>

                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <div class="blog-post-comments">
                                <div class="comments-area mb-45px">
                                    <ol class="comment-list">
                                        <?php
                                            /** @var \WebDevEtc\BlogEtc\Models\Comment[] $comments */
                                        ?>
                                        <?php if(isset($comments)): ?>
                                        <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="comment" id="comment-1">
                                                <article id="div-comment-1" class="comment-body">
                                                    <header class="comment-meta">
                                                                                                            <img class="avatar" src="https://placehold.it/75x75/eee" alt="img" width="75" height="75">
                                                        <cite>
                                                            <span ><?php echo e($comment->author()); ?> </span>
                                                        </cite>
                                                        <span><?php echo e($comment->created_at->format('M d, Y')); ?> at <?php echo e($comment->created_at->format('h:m A')); ?>  </span>
                                                                                                            <a class="comment-reply-link" href="#" aria-label="Reply to A WordPress Commenter">Reply</a>
                                                    </header>
                                                    <section class="comment-content comment">
                                                        <p><?php echo nl2br(e($comment->comment)); ?></p>
                                                    </section>
                                                </article>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <div class="alert alert-info">
                                                        No comments yet!



                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                    </ol>
                                </div>


















































































                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('blogetc_admin::layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc_admin/posts/single_post.blade.php ENDPATH**/ ?>