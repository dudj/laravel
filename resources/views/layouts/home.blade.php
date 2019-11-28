<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }} - Home</title>
    <meta name="description" content="Free Responsive Html5 Css3 Templates">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{ asset('home/css/zerogrid.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/lightbox.css') }}">
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ asset('home/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Owl Carousel Assets -->
    <link rel="stylesheet" href="{{ asset('home/owl-carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('home/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('home/js/script.js') }}"></script>

    <!--[if lt IE 8]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
            <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
    </div>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="{{ asset('home/js/html5.js') }}"></script>
    <script src="{{ asset('home/js/css3-mediaqueries.js') }}"></script>
    <![endif]-->
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
</body>
</html>