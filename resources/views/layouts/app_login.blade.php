@php
$baseURL = getBaseURL();
$setting = getSettingsInfo();

$whiteLabelInfo = getWhiteLabelInfo();

$base_color = '#6ab04c';
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($whiteLabelInfo->site_title) ? $whiteLabelInfo->site_title : config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <script src="{!! $baseURL . 'assets/bower_components/jquery/dist/jquery.min.js' !!}"></script>
    <link rel="stylesheet" href="{!! $baseURL . 'assets/bower_components/font-awesome/css/font-awesome.min.css' !!}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{!! $baseURL . 'assets/bower_components/Ionicons/css/ionicons.min.css' !!}">

    <!-- Latest compiled and minified JavaScript -->
    <link rel="stylesheet" href="{!! $baseURL . 'assets/bower_components/bootstrap-new/bootstrap.min.css' !!}">
    
    <script src="{!! $baseURL . 'assets/bower_components/bootstrap-new/bootstrap.min.js' !!}"></script>

    <script src="{!! $baseURL . 'frequent_changing/js/login.js' !!}"></script>
    <link rel="stylesheet" href="{!! $baseURL . 'frequent_changing/css/login_new.css' !!}">

    <link rel="shortcut icon" href="{!! $baseURL .
        (isset($whiteLabelInfo->favicon)
            ? 'uploads/white_label/' . $whiteLabelInfo->favicon
            : 'assets/branding/favicon.ico') !!}" type="image/x-icon">
    

    <!-- Select2 -->
    <script src="{!! $baseURL . 'assets/bower_components/select2/dist/js/select2.full.min.js' !!}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{!! $baseURL . 'assets/bower_components/select2/dist/css/select22.min.css' !!}">

    <script src="{!! $baseURL . 'assets/plugins/local/html5shiv.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/plugins/local/respond.min.js' !!}"></script>
    <!-- Google Font -->
    <link rel="stylesheet" href="{!! $baseURL . 'assets/plugins/local/google_font.css' !!}">
    <link rel="stylesheet" href="{!! $baseURL . 'frequent_changing/newDesign/style_v1.css' !!}">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">

                @yield('content')
            </div>
        </div>
    </section>
    <script src="{!! $baseURL . 'frequent_changing/js/iconify-icon.min.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/step_2.js' !!}"></script> 
    <script src="{!! $baseURL . 'frequent_changing/js/login.js' !!}"></script>   
</body>

</html>
