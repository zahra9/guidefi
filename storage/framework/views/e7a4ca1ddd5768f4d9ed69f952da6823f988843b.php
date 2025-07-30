<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php if(trim($__env->yieldContent('template_title'))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?> <?php echo e(config('app.name', trans('titles.app'))); ?></title>
        <meta name="description" content="">
        <meta name="author" content="Jeremy Kenedy">
        <link rel="shortcut icon" href="/favicon.ico">

        
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        
        <?php echo HTML::style('https://fonts.googleapis.com/css?family=Roboto:300italic,400italic,400,100,300,600,700', array('type' => 'text/css', 'rel' => 'stylesheet')); ?>

        <?php echo HTML::style(asset('https://fonts.googleapis.com/icon?family=Material+Icons'), array('type' => 'text/css', 'rel' => 'stylesheet')); ?>


        
        <?php if(Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null'): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/mdl-themes/' . $theme->link)); ?>" id="user_theme_link">
        <?php else: ?>
            <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/mdl-themes/material.min.css')); ?>" id="user_theme_link">
        <?php endif; ?>

        
        <link href="<?php echo e(asset('/css/app.css')); ?>" rel="stylesheet">

        <?php echo $__env->yieldContent('template_styles'); ?>

        
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
        </script>

    </head>
    <body class="mdl-color--grey-100">
        <div id="app">


            <?php echo $__env->yieldContent('content'); ?>

        </div>

        
        <script src="<?php echo e(mix('/js/app.js')); ?>"></script>
        <script src="<?php echo e(mix('/js/mdl.js')); ?>"></script>

        <?php echo $__env->yieldContent('footer_scripts'); ?>

    </body>
</html><?php /**PATH C:\wamp64\www\guidefi\resources\views/layouts/newpassword.blade.php ENDPATH**/ ?>