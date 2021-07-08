<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>{{ config('app.frontName') }}</title>
    <meta name="description" content="{{ config('app.frontName') }}">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{ asset('home/css/layui.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/layer.css') }}">
    <script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="{{ asset('home/js/html5.js') }}"></script>
    <script src="{{ asset('home/js/css3-mediaqueries.js') }}"></script>
    <![endif]-->
    <script src="{{ asset('js/custompackage.js') }}"></script>
</head>
<body>
<div class="wrap-body">
    @yield('home_nav')
    @yield('home_content')
    @yield('home_footer')
</div>
<!-- Scripts -->
<!-- Light Box -->
<script src="{{ asset('home/js/lightbox-plus-jquery.min.js') }}"></script>
<!-- carousel -->
<script src="{{ asset('home/owl-carousel/owl.carousel.js') }}"></script>
<script>layui.config({base:"home/js/"}).use("home")</script>
</body>
</html>