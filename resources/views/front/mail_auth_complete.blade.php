@extends('front.common.layout')
@section('title', '認証メール送信完了 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

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
      <span itemprop="name">新規登録</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content mail-auth-complete user-complete">
    <h1 class="main-content__title">認証メール&nbsp;送信完了</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <p>
          この度はエンジニアルートをご利用いただき、誠にありがとうございます。<br />
        </p>
        <div class="regist-thanks">
          <p>
            会員登録ページのURLが記載されたメールを、ご入力されたメールアドレス宛に送信しております。<br />
            引き続き会員情報のご記入のほどよろしくお願い致します。<br />
            エンジニアルート運営事務局一同
          </p>
        </div>
        <div class="cmmn-btn">
          <a href="{{ url('/') }}">トップページへ</a>
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
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
