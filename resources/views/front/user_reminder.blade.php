@extends('front.common.layout')
@section('title', 'エンジニアルート | パスワード再設定URLの通知')
@section('canonical', url('/user/reminder'))

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
      <span itemprop="name">パスワード再設定URLの通知</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-reminder">
    <h1 class="main-content__title">パスワード再設定URLの通知</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">

        @include('front.common.validation_error')

        <p class="content__info">
            登録メールアドレスを入力し、「送信」ボタンをクリックしてください。<br>
            登録メールアドレス宛に、パスワード再設定URLを記載したメールを送信します。
        </p>
        <hr class="hr-1px-dashed-333">
        <div class="content__body">
          <form method="post" action="{{ url('/user/reminder') }}">
            <div class="input_field fs0">
              <div class="input_f_name">
                <p>メールアドレス</p>
              </div>
              <div class="input_f_value">
                <input type="text" name="mail" maxlength="256" value="{{ old('mail') }}" placeholder="例）info@solidseed.co.jp">
              </div>
            </div>
            <hr class="hr-1px-dashed-333">
            <div class="cmmn-btn">
              <button type="submit">送信</button>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
