<?php if(session('success')): ?>
    <section class="alert-wrapper">
        <div class="alert alert-success alert-dismissible show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-body">
                <p><i class="m-right fa fa-check"></i><strong><?php echo app('translator')->get('index.success'); ?>!</strong> <?php echo e(session('success')); ?></p>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if(session('error')): ?>
    <section class="alert-wrapper">
        <div class="alert alert-danger alert-dismissible show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-body">
                <i class="m-right fa fa-times"></i> <strong><?php echo app('translator')->get('index.error'); ?>!</strong> <?php echo e(session('error')); ?>

            </div>
        </div>
    </section>
<?php endif; ?>


<?php if(Session::has('message')): ?>
    <div class="alert alert-<?php echo e(Session::get('type') ?? 'info'); ?> alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
            <p class="mb-0">
                <i class="m-right fa fa-<?php echo e(Session::get('sign') ?? 'check'); ?>"></i>
                <?php echo e(Session::get('message')); ?>

            </p>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\Iproduct-main\resources\views/utilities/messages.blade.php ENDPATH**/ ?>