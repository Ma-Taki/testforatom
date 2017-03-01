@extends('front.common.layout')
@section('title', 'エンジニアルート | お問い合わせ完了')
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
      <span itemprop="name">お問い合わせ</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content contact-complete">
        <h2 class="main-content__title">お問い合わせ完了</h2>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">
                <div class="content__info">
                    <p>お問い合わせ頂きありがとうございます。</p>
                </div>
                <div class="content__body">
                    <p>弊社にてお問い合わせ内容を確認後、エンジニアルートスタッフより、折り返しご連絡差し上げます。</p>
                    <p>今後ともエンジニアルートを宜しくお願い致します。</p>
                    <div class="cmmn-btn">
                        <a href="{{ url('/') }}">トップページへ</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
