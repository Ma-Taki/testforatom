<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="@yield('description')">
        <meta name="keywords" content="IT案件,案件情報,求人,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP">
        <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
        <meta http-equiv="content-language" content="ja">
        <title>@yield('title')</title>
        <link rel="canonical" href="{{ url('/') }}">
        <link rel="icon" href="{{ url('/front/favicon.ico') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick-theme.css') }}">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/slick.min.js') }}"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/jquery.tile.js') }}"></script>
    </head>
    <body>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-2026154-2', 'engineer-route.com');
            ga('send', 'pageview');
        </script>

        @include('front.common.header')
        <!-- END HEADER -->
        @include('front.common.navi')
        <!-- END NAVI -->
        @yield('content')
        @include('front.common.footer')
        <!-- END FOOTER -->
    </body>
    <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>
</html>
