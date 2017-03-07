@extends('front.common.layout')
@section('title', 'エンジニアルート | 退会')
@section('isSimpleFooter', 'true')

@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
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
      <a class="hover-thin" itemprop="item" href="/user">
        <span itemprop="name">マイページ</span>
      </a>
      <meta property="position" content="2">
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">退会</span>
      <meta property="position" content="3">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-delete">
    <h2 class="main-content__title">退会</h2>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">

        <div class="content__info">
          <p>
            「退会する」ボタンをクリックして、エンジニアルートから退会を行います。<br>
            退会を行うことで、案件へのエントリーができなくなります。
          </p>
        </div>
        <hr class="hr-1px-dashed-333">

        <div class="content__body">
          <form method="post" action="{{ url('/user/delete') }}">

            <div class="cmmn-btn">
              <a href="/user">キャンセル</a>
              <button type="submit">退会する</button>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
