<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- base:css -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php if(trim($__env->yieldContent('title'))): ?><?php echo $__env->yieldContent('title'); ?>
        | <?php endif; ?> <?php echo e(config('app.name', Lang::get('titles.app'))); ?></title>
    <meta name="description" content="">
    <meta name="author" content="Abdullah">
    <link rel="shortcut icon" href="/favicon.ico">


    
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


    <![endif]-->

    <link href="<?php echo e(asset('/vendors/datatables.net-bs4/dataTables.bootstrap4.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('/vendors/ti-icons/css/themify-icons.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('/vendors/css/vendor.bundle.base.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('/css/style.css')); ?>" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.4.1/css/all.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/mdl-themes/material.min.css')); ?>" id="user_theme_link">


    
    <?php echo $__env->yieldContent('fonts'); ?>

    
  

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/mdl-themes/material.min.css')); ?>"
              id="user_theme_link">



    <?php echo $__env->yieldContent('linked_css'); ?>
   

    <style type="text/css">
        <?php echo $__env->yieldContent('template_fastload_css'); ?>

                .user-avatar-nav {
            background: url(<?php echo e(Gravatar::get(Auth::check() ? Auth::user()->email : 'user@gmail.com')); ?>) 50% 50% no-repeat;
            background-size: auto 100%;
        }

    </style>

    
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
    </script>

    <!-- endinject -->
    <link rel="shortcut icon" href="<?php echo e(asset('/images/favicon.png')); ?>"/>

</head>
<?php echo $__env->yieldContent('head'); ?>

<body>
<div id="app" class="container-scroller">

    <div class="container-fluid page-body-wrapper">
<?php if(Auth::check()): ?>

    <?php echo $__env->make('partials.sidebar_skins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('partials.navHead', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
    <!-- partial -->

        <div class="main-panel">

            <?php echo $__env->yieldContent('content'); ?>

            <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>
        <!-- content-wrapper ends -->

    </div>


</div>

<script src="<?php echo e(asset('/vendors/js/vendor.bundle.base.js')); ?>"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="<?php echo e(asset('/js/off-canvas.js')); ?>"></script>
<script src="<?php echo e(asset('/js/hoverable-collapse.js')); ?>"></script>
<script src="<?php echo e(asset('/js/template.js')); ?>"></script>
<script src="<?php echo e(asset('/js/settings.js')); ?>"></script>
<script src="<?php echo e(asset('/js/todolist.js')); ?>"></script>
<script src="<?php echo e(asset('/vendors/chart.js/Chart.js')); ?>"></script>
<script src="<?php echo e(asset('/js/dashboard2.js')); ?>"></script>
<script src="<?php echo e(asset('/vendors/datatables.net/jquery.dataTables.js')); ?>"></script>
<script src="<?php echo e(asset('/vendors/datatables.net-bs4/dataTables.bootstrap4.js')); ?>"></script>
<script src="<?php echo e(asset('/js/data-table.js')); ?>"></script>
<script src="<?php echo e(asset('/js/popper.js')); ?>"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
             $(window).scroll(function() {
      if(window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        var container = $('.container-scroller');
        if ($(window).scrollTop() >= 30) {
          $(header).addClass('fixed-on-scroll');
          $(container).addClass('fixed-container');
        } else {
          $(header).removeClass('fixed-on-scroll');
          $(container).removeClass('fixed-container');
        }
      }
    });


            </script>
<script src="<?php echo e(mix('/js/app.js')); ?>"></script>
<script src="<?php echo e(mix('/js/mdl.js')); ?>"></script>
<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html><?php /**PATH C:\wamp64\www\guidefi\resources\views/layouts/app.blade.php ENDPATH**/ ?>