<section class="header-section">
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light custom-navbar p-0">
            <div>
                <a class="navbar-brand mr-0" href="/"><img class="logo" width="115px" src="<?php echo e(asset('assets/images/logo-01.png')); ?>" alt="" /></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav nav-margin">
                    <li class="nav-item">
                        <a class="nav-link ml-2 <?php echo e(request()->is('/') ? 'active' : ''); ?> <?php echo e(request()->is('home') ? 'active' : ''); ?>" href="/">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ml-2 <?php echo e(request()->is('about-us') ? 'active' : ''); ?>" href="<?php echo e(route('about-us')); ?>">ABOUT US</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ml-2 <?php echo e(request()->is('faq') ? 'active' : ''); ?>" href="<?php echo e(route('faq')); ?>">FAQS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ml-2" data-toggle="modal" data-target=".joinGuidefi" href="">JOIN COMMUNITY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ml-2 <?php echo e(request()->is('our-commitment') ? 'active' : ''); ?>" href="<?php echo e(route('our-commitment')); ?>">OUR COMMITMENT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ml-2 <?php echo e(request()->is('blog') ? 'active' : ''); ?> <?php echo e(request()->is('blog/*') ? 'active' : ''); ?>" href="<?php echo e(url('blog')); ?>">BLOG</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</section><?php /**PATH C:\wamp64\www\guidefi\resources\views/home/partials/nav-bar.blade.php ENDPATH**/ ?>