<!-- partial:partials/_navbar.html -->
<?php
use App\Models\Notification;
$notifications = Notification::latest()->take(5)->get();
?>
<nav class="navbar p-0 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="ti-align-left"></span>
        </button>
        <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="#"><img src="<?php echo e(asset('assets/images/logo-01.png')); ?>" width="150px" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="#"><img src="<?php echo e(asset('assets/images/logo-01.png')); ?>" width="150px" alt="logo" /></a>
        </div>
        <ul class="navbar-nav navbar-nav-right ml-auto">



















































<!--            <li class="nav-item dropdown">-->
<!--                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">-->
<!--                    <i class="ti-bell mx-0"></i>-->
<!--                    <span class="count"></span>-->
<!--                </a>-->
<!--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">-->
<!--                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>-->
<!--                  -->
<!--                    <?php if(!is_null($notifications)): ?>-->
<!--                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->
<!--                    <a class="dropdown-item preview-item" href="<?php echo e(route('notifications.index')); ?>">-->
<!--                        <div class="preview-thumbnail">-->
<!--                            <div class="preview-icon bg-gradient-warning">-->
<!--                                <i class="ti-info mx-0"></i>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="preview-item-content">-->
<!--                            <h6 class="preview-subject font-weight-normal"><?php echo e($notification->description); ?></h6>-->
<!--                            <p class="font-weight-light small-text mb-0 text-muted">-->
<!--                                <?php echo e($notification->created_at->diffForHumans()); ?>-->
<!--                            </p>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
<!--                    <?php endif; ?>-->
<!---->
<!--                </div>-->
<!--            </li>-->
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="https://via.placeholder.com/30x30" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                   <!-- <a href="<?php echo e(url('/notification')); ?>" class="dropdown-item">
                        <i class="ti-bell text-primary"></i>
                        Notifications
                    </a>-->
                    <a href="<?php echo e(url('/setting-list')); ?>" class="dropdown-item">
                        <i class="ti-settings text-primary"></i>
                        Settings
                    </a>
                    <a href="<?php echo e(url('/logout')); ?>" class="dropdown-item">
                        <i class="ti-new-window text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="ti-menu"></span>
        </button>
    </div>
</nav>
<!-- partial --><?php /**PATH C:\wamp64\www\guidefi\resources\views/partials/navHead.blade.php ENDPATH**/ ?>