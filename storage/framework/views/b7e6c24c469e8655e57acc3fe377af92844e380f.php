<?php $__env->startSection('content'); ?>
<style>
    .main-panel {
        transition: width 0.25s ease, margin 0.25s ease;
        width: 100%;
        margin-left: 0px;
        min-height: calc(100vh - 106px);
        display: -webkit-flex;
        display: flex;
        -webkit-flex-direction: column;
        flex-direction: column;
    }
    .mdl-color--primary {
    background-color: #04B4F0 !important;
}
</style>

<div class="container-scroller" style="background: url('/assets/img/page_bg.png') !important;">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row  w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo">


                            <img src="<?php echo e(asset('assets/images/logo.svg')); ?>" alt="logo">

                        </div>
                        <h4 class="login"><?php echo e(trans('titles.resetPword')); ?></h4>
                        <?php echo Form::open(array('url' => '/emailverfication', 'method' => 'POST', 'class' => 'auth-form', 'id' => 'reset')); ?>

                        <?php echo e(csrf_field()); ?>

                        <?php if(\Session::has('error')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <li><?php echo \Session::get('error'); ?></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if(\Session::has('success')): ?>
                            <div class="alert alert-success">
                                <ul>
                                    <li><?php echo \Session::get('success'); ?></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="form-group <?php echo e($errors->has('email') ? 'is-invalid' :''); ?>">
                            <?php echo Form::email('email', null, array('id' => 'email', 'class' => 'mdl-textfield__input', 'placeholder'=>'Enter Email Address' , 'required'=>'required' )); ?>

                            <span class="mdl-textfield__error"><?php if($errors->has('email')): ?><?php echo e($errors->first('email')); ?> <?php endif; ?></span>
                        </div>

                        <?php if(config('settings.reCaptchStatus')): ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo e(env('RE_CAP_SITE')); ?>"></div>
                        <?php endif; ?>

                        <?php echo Form::button('<span class="mdl-spinner-text">'.trans('auth.sendResetLink').'</span>
                        <div class="mdl-spinner w-100 mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'w-100 mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--primary mdl-color-text--white mdl-button--raised full-span margin-bottom-1 margin-top-2','type' => 'submit','id' => 'submit')); ?>


                        <?php echo Form::close(); ?>


                        <a class="form-control btn btn-primary mt-4 p-3">Back to login</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
    <?php echo HTML::script('https://www.google.com/recaptcha/api.js', array('type' => 'text/javascript')); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/auth/passwords/resetemail.blade.php ENDPATH**/ ?>