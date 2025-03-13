<?php
$baseURL = getBaseURL();
?>


<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="image">
            <img alt="Broken light bulb illustration" height="150"
                src="<?php echo e($baseURL); ?>/<?php echo e('frequent_changing/images/404.png'); ?>"
                width="150" />
        </div>
        <div class="error-code">
            404
        </div>
        <div class="message">
            <?php echo app('translator')->get('index.lost'); ?>
        </div>
        <div class="sub-message">
            <?php echo app('translator')->get('index.lost_message'); ?>
        </div>
        <a class="home-link" href="<?php echo e(route('home')); ?>">
            <?php echo app('translator')->get('index.go_to_home'); ?>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Iproduct-main\resources\views/errors/404.blade.php ENDPATH**/ ?>