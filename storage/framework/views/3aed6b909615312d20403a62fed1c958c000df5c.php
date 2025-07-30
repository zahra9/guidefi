<div class="form-group">
    <label id="captcha_label" for="captcha">
        Captcha: <?php echo e(config('blogetc.captcha.basic_question', '[error - undefined captcha question]' )); ?>

    </label>
    <input type="text" required class="form-control" name="captcha"
           id="captcha" placeholder="" value="<?php echo e(old('captcha')); ?>">
</div>
<?php /**PATH C:\wamp64\www\guidefi\resources\views/vendor/blogetc/captcha/basic.blade.php ENDPATH**/ ?>