<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=450, user-scalable=no">
    <title>NOT FOUND</title>
    <link href="{{ asset('errors/application-one.css') }}" media="screen, projection" rel="stylesheet" type="text/css">
    <link href="{{ asset('errors/application-mobile.css') }}" media="only screen and (max-device-width: 480px)" rel="stylesheet" type="text/css">
    <script src="{{ asset('errors/application.js') }}" type="text/javascript"></script>
</head>
<body class="error_page">
<div id="wrap" class="clearfix">
    <div id="header"></div>
    <div id="smash_page">
        <h4>{{$code}} PAGE {{$message}}</h4>
    </div>
    <div id="smash" style="top:15%">
        <div class="skull" style="cursor: pointer;">
            <div class="eyes">
                <img alt="Eyes01" class="pupils" src="/errors/eyes.png" style="left: 17px; top: 15px;">
                <img alt="Hilites02" class="hilites" src="/errors/hilites.png" style="left: 17px; top: 12px;">
            </div>
            <img alt="Face04" class="face" src="/errors/face.png">
        </div>
    </div>
</div>
</body>
</html>