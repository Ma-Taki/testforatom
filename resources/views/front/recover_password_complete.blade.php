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

  <div id="recover_password_complete" class="content">
    <h1 class="pageTitle">パスワード再設定完了</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="recover_password_complete">
      <p class="pageInfo">
        パスワードを再設定致しました。<br />
        ログイン画面よりログインしてください。
      </p>
      <div class="commonCenterBtn">
        <a href="{{ url('/login') }}"><button>ログイン画面</button></a>
      </div>
    </div>
  </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
