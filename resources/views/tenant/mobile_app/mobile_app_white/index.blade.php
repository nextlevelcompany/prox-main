{{-- añadido assets de laravel --}}
    <!doctype html>
<html>
<head>
    <meta charset=UTF-8>
    <meta name=viewport
          content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no,viewport-fit=cover">
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black-translucent>
    <meta name=theme-color content=#2196f3>
    <meta http-equiv=Content-Security-Policy
          content="default-src * 'self' {{url('/')}} 'unsafe-inline' 'unsafe-eval' data: gap:">
    <title>FacturaloPeru APP Premium</title>
    <link href="{{ asset('liveapp-white/assets/styles.css')}}" rel=stylesheet>
</head>
<body>
<script src=cordova.js></script>
<div id=app></div>
<script src="{{ asset('liveapp-white\assets\83a35d900d4f93e6b923.main.js')}}"></script>
</body>
</html>
