<!DOCTYPE HTML>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords'),IT案件,案件情報,求人,案件,仕事,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP">
    <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
    @if($__env->yieldContent('noindex'))<meta name="robots" content="noindex,nofollow" />@endif
    <!-- <meta http-equiv="content-language" content="ja"> -->
    <title>@yield('title')</title>
    @if($__env->yieldContent('canonical'))<link rel="canonical" href="@yield('canonical')">@endif
    @if($__env->yieldContent('prev'))<link rel="prev" href="@yield('prev')" />@endif
    @if($__env->yieldContent('next'))<link rel="next" href="@yield('next')" />@endif
    <link rel="icon" href="{{ url('/front/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/front/css/style.css') . '?' . filemtime( __DIR__.'/../../../public/front/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick-theme.css') }}">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/slick.min.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/jquery.tile.js') }}"></script>
    @include('front.common.inside_head_tag')
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

    @if($__env->yieldContent('isSimpleFooter'))

      @include('front.common.simple_footer')

    @else

      @include('front.common.footer')

    @endif
    <!-- END FOOTER -->
    <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js?20170602') }}"></script>
  </body>
</html>
