<?php
$baseURL = getBaseURL();
$setting = getSettingsInfo();
$whiteLabelInfo = getWhiteLabelInfo();
$base_color = '#6ab04c';
if(isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo e(isset($whiteLabelInfo->site_title) ? $whiteLabelInfo->site_title : config('app.name')); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?php echo $baseURL . 'frequent_changing/css/error.css'; ?>">

    <link rel="shortcut icon" href="<?php echo $baseURL .
        (isset($whiteLabelInfo->favicon)
            ? 'uploads/white_label/' . $whiteLabelInfo->favicon
            : 'assets/branding/favicon.ico'); ?>" type="image/x-icon">

</head>

<body>
    <?php echo $__env->yieldContent('content'); ?>   
</body>

</html>
<?php /**PATH C:\xampp\htdocs\Iproduct-main\resources\views/layouts/errors.blade.php ENDPATH**/ ?>