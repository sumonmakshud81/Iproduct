<?php
$baseURL = getBaseURL();
$setting = getSettingsInfo();

$whiteLabelInfo = getWhiteLabelInfo();

$base_color = '#6ab04c';
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
?>


<?php $__env->startSection('content'); ?>   

    <div class="col-md-6 col-lg-5 login-card-wrapper-2">
        <div class="msg-wrap-2">
        <?php echo $__env->make('utilities.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="wrap-2">
            <div class="login-wrap-2">
                <div class="d-flex justify-content-center logo-area">
                    <a href="<?php echo e(route('login')); ?>">
                       <img src="<?php echo $baseURL .
                            (isset($whiteLabelInfo->logo) ? 'uploads/white_label/' . $whiteLabelInfo->logo : 'images/logo.png'); ?>"
                                alt="site-logo">
                    </a>
                </div>
                <div class="d-flex">
                    <div class="w-100">
                        <h3 class="mb-3 auth-title"><?php echo app('translator')->get('index.reset_password_step_1'); ?></h3>
                    </div>
                </div>
                <form action="<?php echo e(route('post-forgot-password-step-one')); ?>" method="post"
                    accept-charset="utf-8">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-3">
                        <label class="label" for="email"><?php echo app('translator')->get('index.email'); ?><span
                                    class="text-danger">*</span></label>
                        <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo app('translator')->get('index.email'); ?>" type="text" id="email"
                            autocomplete="off" name="email" value="<?php echo e(old('email')); ?>">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-1">
                        <button type="submit" name="submit" value="submit"
                            class="btn login-button btn-2 rounded submit me-1"><span><?php echo app('translator')->get('index.submit'); ?></button>
                    </div>
                    <div class="text-center py-3">
                        <a class="forgot-pass" href="<?php echo e(url('login')); ?>"><?php echo app('translator')->get('index.back_to_login'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app_login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Iproduct-main\resources\views/pages/authentication/forgotPasswordStepOne.blade.php ENDPATH**/ ?>