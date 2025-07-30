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
                        <h4 class="login">Log In</h4>
                        <?php if(\Session::has('success')): ?>
                            <div class="alert alert-success">
                                <ul>
                                    <li><?php echo \Session::get('success'); ?></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if(\Session::has('error')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <li><?php echo \Session::get('error'); ?></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                            <?php echo Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'pt-3', 'id' => 'login', 'role' => 'form']); ?>

                            <?php echo e(csrf_field()); ?>

                            <div class="form-group <?php echo e($errors->has('email') ? 'is-invalid' :''); ?>">
                            <?php if($errors->has('email')): ?>
                                    <p class="error alert alert-danger" ><?php echo e(trans('auth.emailLoginError')); ?></p>
                                <?php endif; ?>
                                <input type="email" class="form-control mail form-control-lg" id="exampleInputEmail1" placeholder="Username" name="email">
                               
                            </div>
                            <div class="form-group  <?php echo e($errors->has('password') ? 'is-invalid' :''); ?>">
                                <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password">
                                <?php if($errors->has('password')): ?>
                                    <span class="error"><?php echo e(trans('auth.pwLoginError')); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    
                                </div>
                                <a href="/resetpassword" class="auth-link" style="color:#04b4f0;">Forgot password?</a>
                            </div>
                            <div class="mt-3">
                                <?php echo Form::button('<span class="mdl-spinner-text">'.trans('auth.login').'</span><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white"></div>', array('class' => 'btn btn-block btn-lg login-button font-weight-medium auth-form-btn','type' => 'submit','id' => 'submit','style' => "background:#04b4f0;")); ?>


                            </div>
                            <div class="mt-3">

                            </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\guidefi\resources\views/auth/login.blade.php ENDPATH**/ ?>