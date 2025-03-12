<?php 
header('Content-type: text/html; charset=ISO-8859-1');
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//getting base url for actual path
$root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
$root.= str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]);
$base_url = $root;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!-- //need to change -->
    <title>iProduction | Install</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url?>css/bootstrap.min.css"/>
    <link href="<?php echo $base_url?>css/custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $base_url?>css/inline.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $base_url?>css/edit.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $base_url?>css/font-awesome.css" rel="stylesheet" type="text/css" />
      <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $base_url?>css/font-awesome/css/font-awesome.min.css">
    <!-- //need to change -->
    <link rel="shortcut icon" href="<?php echo  $base_url?>img/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="main_header">
        <div id="install-header">
            <!-- //need to change -->
            <img class="ins_1" src="<?php echo  $base_url?>img/main_logo.png"/>
        </div>
        <div class="install">
            <?php
            require("install.php");
            ?>
        </div>
    </div>

    <script src="<?php echo $base_url?>js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url?>js/Bootstrap v4.3.1 .js"></script>
</body>
</html>