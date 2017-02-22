@extends('front.common.layout')
@section('title', 'エンジニアルート | 新規登録')
@section('description', 'エンジニアルートの新規会員登録ページです。')
@section('canonical', url('/user/regist/auth'))

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
      <span itemprop="name">新規登録</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content mail-auth">
    <h1 class="main-content__title">新規登録</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
@include('front.common.validation_error')
{{-- SNS認証用メッセージ --}}
@if(Session::has('custom_info_messages'))
        <div class="alert alert-info">
          <ul>
    @foreach(Session::get('custom_info_messages') as $message)
            <li>{!! $message !!}</li>
    @endforeach
          </ul>
        </div>
@endif

        <div class="content__info">
          <p>
            メールアドレスを入力して「送信」ボタンをクリックするか、外部アカウントを使用してメールアドレスの認証を行ってください。<br>
            「メールを送信して認証」では、メール本文に記載されたURLより、会員登録(無料)を行ってください。
          </p>
        </div>
        <hr class="hr-1px-dashed-333">

        <div class="content__body">

          <div class="login__element">
            <h2 class="login__title">メールを送信して認証</h2>
            <form method="post" action="{{ url('/user/regist/auth') }}">
              <div class="login_input fs0">
                <div class="input_field">
                  <input type="text" name="mail" maxlength="256" value="{{ old('email')}}" placeholder="例）info@solidseed.co.jp">
                </div>
                <div class="input_field">
                  <input type="text" name="mail_confirmation" maxlength="256" value="{{ old('mail_confirmation')}}" placeholder="もう一度入力してください">
                </div>
              </div>

              <div class="cmmn-btn">
                <button class="login-btn" type="submit">送信</button>
              </div>
              {{ csrf_field() }}
            </form>
          </div>
          <hr class="hr-1px-dashed-333 invisible-pc invisible-tab">

          <div class="login__element">
            <h2 class="login__title">外部アカウントで認証</h2>
            <a href="/login/sns/facebook?func=regist" class="login__sns-btn facebook">
              <span class="facebook_logo"></span>Facebook
            </a>
            <a href="/login/sns/twitter?func=regist" class="login__sns-btn twitter">
              <span class="twitter_logo"></span>Twitter
            </a>
            <a href="/login/sns/github?func=regist" class="login__sns-btn github">
              <span class="github_logo"></span>GitHub
            </a>
            <p>ユーザーの許可なく投稿することはありません</p>
          </div>
        </div>
      </div>
    </div>
  </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
