@extends('front.common.layout')
@section('title', 'ログイン | エンジニアルート')
@section('description', '')
@section('canonical', url('/login'))
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
      <span itemprop="name">ログイン</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content login">
    <h1 class="main-content__title">ログイン</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">

{{-- error：validation --}}
@if(count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
@endif
{{-- error：custom --}}
@if(count($errors) == 0)
    @if(Session::has('custom_error_messages'))
        <div class="alert alert-danger">
          <ul>
@foreach(Session::get('custom_error_messages') as $message)
            <li>{!! $message !!}</li>
@endforeach
          </ul>
        </div>
    @endif
@endif

        <div class="content__body">
          <div class="login__element">
            <h2 class="login__title">メールアドレスでログイン</h2>
            <form action="/login" method="post">
              <div class="login_input fs0">
                <div class="input_field">
                  <input type="text" name="email" value="{{ old('email')}}" placeholder="例）info@solidseed.co.jp">
                </div>
                <div class="input_field">
                  <input type="password" name="password" placeholder="パスワード">
                </div>
              </div>
              <div class="infoText">
                <p>パスワードを忘れた方は<a href="/user/reminder">コチラ</a></p>
                <p>新規会員登録は<a href="/user/regist/auth">コチラ</a></p>
              </div>
              <div class="cmmn-btn">
                <button class="login-btn" type="submit">ログイン</button>
              </div>
              <input type="hidden" name="next" value="{{ $next }}">
              {{ csrf_field() }}
            </form>
          </div>
          <hr class="hr-1px-dashed-333 invisible-pc invisible-tab">

          <div class="login__element">
            <h2 class="login__title">外部アカウントでログイン</h2>
            <a href="/login/sns/facebook?func=login" class="login__sns-btn facebook">
              <span class="facebook_logo"></span>Facebook
            </a>
            <a href="/login/sns/twitter?func=login" class="login__sns-btn twitter">
              <span class="twitter_logo"></span>Twitter
            </a>
            <a href="/login/sns/github?func=login" class="login__sns-btn github">
              <span class="github_logo"></span>GitHub
            </a>
            <p>ユーザーの許可なく投稿することはありません</p>
          </div>
        </div>
        <hr class="hr-1px-dashed-333">

        <div class="login__sub-text">
          <p>ログインすると以下の機能を使用できるようになります。</p>
          <ul>
            <li>・案件へのエントリー</li>
            <li>・会員情報の変更</li>
          </ul>
        </div>

      </div>
    </div>
  </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
