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
    <div class="col-md-12 col-lg-10 login-card-wrapper">
        <div class="login-parent-wrapper">
            <?php echo $__env->make('utilities.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="wrap d-md-flex">
                
                <div class="img business-grap" style="background-image: url('<?php echo $baseURL . 'frequent_changing/images/login-page.jpg'; ?>');">
                    <div class="overlay">
                        <div>
                            <h4><?php echo app('translator')->get('index.start_journey_with_us'); ?></h4>
                            <p><?php echo app('translator')->get('index.login_text'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="login-wrap">
                    <div class="d-flex justify-content-center logo-area">
                        <a href="<?php echo e(route('login')); ?>">
                            <img src="<?php echo $baseURL .
                                (isset($whiteLabelInfo->logo) ? 'uploads/white_label/' . $whiteLabelInfo->logo : 'images/logo.png'); ?>" alt="site-logo">
                        </a>
                    </div>
                    <div class="d-flex">
                        <div class="w-100">
                            <h3 class="mb-3 auth-title"><?php echo app('translator')->get('index.please_login'); ?></h3>
                        </div>
                    </div>
                    <form action="<?php echo e(route('login')); ?>" id="login_form" autocomplete="off" method="post"
                        accept-charset="utf-8">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label class="label" for="email"><?php echo app('translator')->get('index.email'); ?> <span
                                    class="text-danger">*</span></label>
                            <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo app('translator')->get('index.email'); ?>"
                                type="text" id="email" autocomplete="off" name="email">
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
                        <div class="form-group mb-3">
                            <label class="label" for="password"><?php echo app('translator')->get('index.password'); ?> <span
                                    class="text-danger">*</span></label>
                            <input type="password" autocomplete="off" name="password" id="password"
                                class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Password">
                            <?php $__errorArgs = ['password'];
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

                        <div class="d-flex py-10">
                            <button type="submit" name="submit" value="submit"
                                class="btn login-button btn-2 rounded submit me-1"><?php echo app('translator')->get('index.submit'); ?></button>
                        </div>
                        <div class="d-flex justify-content-end forgot-pass-wrap">
                            <a href="<?php echo e(url('forgot-password-step-one')); ?>" class="forgot-pass"><?php echo app('translator')->get('index.forgot_password'); ?></a>
                        </div>
                        <?php if(appMode() == 'demo'): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col"><?php echo app('translator')->get('index.email'); ?></th>
                                            <th scope="col"><?php echo app('translator')->get('index.password'); ?></th>
                                            <th scope="col"><?php echo app('translator')->get('index.actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        <tr>
                                            <td>admin@doorsoft.co</td>
                                            <td>123456</td>
                                            <td>
                                                <button type="button" class="btn btn-primary login_btn_click"><iconify-icon
                                                        icon="solar:login-broken"></iconify-icon></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app_login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Iproduct-main\resources\views/auth/login.blade.php ENDPATH**/ ?>