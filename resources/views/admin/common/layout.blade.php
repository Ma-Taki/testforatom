<!DOCTYPE HTML>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{ url('/admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('/admin/css/styles.css') }}" rel="stylesheet">
    <link href="{{ url('/admin/css/jquery-ui.css') }}" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery.js"></script>-->
    <script src="{{ url('/admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/admin/js/custom.js') }}"></script>
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
  <script type="text/javascript" charset="utf-8" src="{{ url('/admin/js/all.js?20180403') }}"></script>
  </body>
</html>
