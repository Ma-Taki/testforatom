@extends('front.common.layout')
@section('title', 'エンジニアルート | パスワード再設定完了')
@section('content')
<div class="wrap">

  <div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a class="hover-thin" itemprop="item" href="/">
        <span itemprop="name">エンジニアルート</span>
      </a>
      <meta itemprop="position" content="1" />
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">パスワード再設定</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content recover-password-complete">
    <h2 class="main-content__title">パスワード再設定完了</h2>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <p>
          パスワードを再設定致しました。<br />
          ログイン画面よりログインしてください。
        </p>
      </div>
      <div class="cmmn-btn">
        <a href="{{ url('/login') }}">ログイン画面へ</a>
      </div>
    </div>
  </div><!-- END main-content -->
</div><!-- END .wrap -->
@endsection
