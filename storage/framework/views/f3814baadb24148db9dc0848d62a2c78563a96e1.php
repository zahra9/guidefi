<?php $__env->startSection("content"); ?>

<div class="container-fluid mt-5">
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
                                <?php echo isset($post->post_body) ? $post->post_body : ''; ?>

                            </div>





















                            <div class="blog-post-comments">
                                <div class="comments-area mb-45px">
                                    <ol class="comment-list">
                                        <?php
                                            /** @var \WebDevEtc\BlogEtc\Models\Comment[] $comments */
                                        ?>
                                        <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="comment" id="comment-1">
                                            <article id="div-comment-1" class="comment-body">
                                                <header class="comment-meta">

                                                    <cite>
                                                        <span ><?php echo e($comment->author()); ?> </span>
                                                    </cite>
                                                        <span><?php echo e($comment->created_at->format('M d, Y')); ?> at <?php echo e($comment->created_at->format('h:m A')); ?>  </span>

                                                </header>
                                                <section class="comment-content comment">
                                                    <p><?php echo nl2br(e($comment->comment)); ?></p>
                                                </section>
                                            </article>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="alert alert-info">
                                                    No comments yet!
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check(\WebDevEtc\BlogEtc\Gates\GateTypes::ADD_COMMENT)): ?>
                                                        Why don't you be the first?
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                    </ol>
                                </div>
                                <h5 class="comment-reply-title mb-4">Leave a comment</h5>

                                <form method="post" action="<?php echo e(route('blogetc.comments.add_new_comment', $post->slug)); ?>" id="comment_save">
                                    <?php echo csrf_field(); ?>

                                    <div class="form-group">

                                        <label id="comment_label" for="comment">Your Comment</label>
                                        <textarea
                                                class="form-control"
                                                name="comment"
                                                required
                                                id="comment"
                                                placeholder="Write your comment here"
                                                rows="7"><?php echo e(old('comment')); ?></textarea>

                                    </div>

                                    <div class="container-fluid">
                                        <div class="row">
                                            <?php if(config("blogetc.comments.save_user_id_if_logged_in", true) === false || !Auth::check()): ?>
                                                <div class="col-md-4 pl-0">
                                                    <div class="form-group">
                                                        <label id="author_name_label" for="author_name">Your Name </label>
                                                        <input
                                                                type="text"
                                                                class="form-control"
                                                                name="author_name"
                                                                id="author_name"
                                                                placeholder="Your name"
                                                                required
                                                                value="<?php echo e(old("author_name")); ?>">
                                                    </div>
                                                </div>

                                                <?php if(config("blogetc.comments.ask_for_author_email")): ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label id="author_email_label" for="author_email">Your Email
                                                            </label>
                                                            <input
                                                                    type="email"
                                                                    class="form-control"
                                                                    name="author_email"
                                                                    id="author_email"
                                                                    placeholder="Your Email"
                                                                    required
                                                                    value="<?php echo e(old("author_email")); ?>">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if(config("blogetc.comments.ask_for_author_website")): ?>
                                                <div class="col-md-4 pr-0">
                                                    <div class="form-group">
                                                        <label id="author_website_label" for="author_website">Your Website
                                                        </label>
                                                        <input
                                                                type="url"
                                                                class="form-control"
                                                                name="author_website"
                                                                id="author_website"
                                                                placeholder="Your Website URL"
                                                                value="<?php echo e(old("author_website")); ?>">
                                                    </div>
                                                </div>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if($captcha): ?>
                                        
                                        <?php echo $__env->make($captcha->view(), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endif; ?>

                                    <div class="form-group mb-5 pb-5">
                                        <input type="submit" class="btn-shadow btn" value="Add Comment">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </article>

                </div>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("submit","#comment_save",function (e) {
            e.preventDefault();
            var $f = $(this);
            var $sb = $f.find('[type="submit"]');
            var oldBtnText = $sb.html();

            $.ajax({
                beforeSend: function () {
                    $sb.html('<i class="fa fa-spinner fa-spin"></i>Processing').prop('disabled', true);
                },
                url: $f.attr('action'),
                type: $(this).attr('method'),
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $sb.html(oldBtnText).prop('disabled', false);
                    toastr.clear();
                    toastr.success('Thanks! Your comment has been saved!<br/>' +
                        'After an admin user approves the comment, it`ll appear on the site!', 'Success', {timeOut: 7000});
                    $('#comment_save')[0].reset();

                },
                error: function (xhr) {
                    $sb.html(oldBtnText).prop('disabled', false);
                    loadRequestErrors(xhr);
                }
            });
        });
        function loadRequestErrors(xhr) {
            if (xhr.message) {
                $.each(xhr.message, function(key, item) {
                    toastr.clear();
                    toastr.error(item, 'Error', {
                        timeOut: 5000
                    });
                });
            } else if (xhr.responseJSON.message) {
                console.log(xhr.responseJSON);
                toastr.clear();
                toastr.error(xhr.responseJSON.message, 'Error', {
                    timeOut: 5000
                });
            }
        }

    });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("home.layouts.master", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/single_post.blade.php ENDPATH**/ ?>