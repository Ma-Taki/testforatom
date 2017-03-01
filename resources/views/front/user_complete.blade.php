@extends('front.common.layout')
@section('title', 'エンジニアルート | 新規会員登録完了')
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
      <span itemprop="name">新規会員登録</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-complete">
    <h1 class="main-content__title">会員登録完了</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">

        <p>この度はエンジニアルートに会員登録をしていただき、誠にありがとうございます。</p>
        <div class="regist-thanks">
          <p>会員IDはご登録いただいたメールアドレスになります。</p>
          <p class="user-id">会員ID：{{  $mail or '*****************' }}</p>
          <p>パスワードと共にお忘れずに管理ください。</p>
          <div class="cmmn-btn">
            <a href="/">トップページへ</a>
          </div>
        </div>
        <div class="support">
          <p class="about-support">サポートについて</p>
          <p>疑問点・ご不明な点などございましたら、お気軽にお問い合わせください。</p>
          <div class="cmmn-btn">
            <a href="/contact">お問い合わせフォーム</a>
          </div>
        </div>
      </div>
    </div>
    <div class="invisible-pc invisible-tab">
        @include('front.common.sideInfo')
    </div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
