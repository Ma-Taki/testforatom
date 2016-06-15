<!DOCTYPE HTML>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="{{ url('/admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- styles -->
    <link href="{{ url('/admin/css/styles.css') }}" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="{{ url('/admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/admin/js/custom.js') }}"></script>
    <link href="{{ url('/admin/css/mycustom.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
@include('admin.common.header')
<div class="page-content">
    <div class="row">
@include('admin.common.sidebar')
@yield('content')
    </div>
</div>
@include('admin.common.footer')
  </body>
</html>
