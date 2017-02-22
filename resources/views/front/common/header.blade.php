<?php use App\Libraries\FrontUtility as FrntUtil; ?>
<header>
  <div class="headerInr">

@if($__env->yieldContent('h1'))
    <h1 class="header__text invisible-sp">@yield('h1')</h1>
@else
    <h1 class="header__text invisible-sp">@yield('title')</h1>
@endif

    <div class="user">
      <ul>
@if(FrntUtil::isLogin())
        <li><a href="/user">マイページ</a></li>
        <li><a href="/logout" class="invisible-sp">ログアウト</a></li>
@else
        <li><a href="/user/regist/auth" class="signin">新規登録</a></li>
        <li><a href="/login">ログイン</a></li>
@endif
      </ul>
    </div>

    <div class="header__logo">
      <a href="{{ url('/') }}" title="エンジニアルート"></a>
    </div>

    <div class="search">
      <form action="/item/keyword" method="get">
        <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
        <button class="searchBtn" type="submit"></button>
      </form>
    </div><!-- /.search -->
  </div><!-- /.headerInr -->
</header>
