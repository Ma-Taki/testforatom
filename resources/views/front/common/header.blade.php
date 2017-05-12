<?php
 use App\Libraries\FrontUtility as FrntUtil;
 use App\Libraries\ConsiderUtility as CnsUtil;
 ?>
<header>
  <div class="headerInr">

@if($__env->yieldContent('h1'))
    <h1 class="header__text invisible-sp">@yield('h1')</h1>
@else
    <h1 class="header__text invisible-sp">@yield('title')</h1>
@endif

    <div class="user">
      <ul>
        <li><a href="/considers"><span>検討中</span><span><span id="considers_length">{{ CnsUtil::culcConsiderLength() }}</span><span id="considers_unit"> 件</span></span></a></li>
@if(FrntUtil::isLogin())
        <li><a href="/user/entry"><span>応募</span><span>履歴</span></a></li>
        <li><a href="/user"><span>マイ</span><span>ページ</span></a></li>
        <li><a href="/logout" class="invisible-sp"><span>ログ</span><span>アウト</span></a></li>
@else
        <li id = "user-signin"><a href="/user/regist/auth" class="signin">新規登録</a></li>
        <li id = "user-login"><a href="/login"><span>ログ</span><span>イン</span></a></li>
@endif
      </ul>
    </div>

    <div class="header__logo">
      <a href="{{ url('/') }}" title="エンジニアルート"></a>
    </div>

    <div class="search">
      <form action="/item/keyword" method="get">
        <input type="text" name="keyword" placeholder="キーワードで検索" size="40" maxlength="255" class="searchBox">
        <button class="searchBtn" type="submit"></button>
      </form>
    </div><!-- /.search -->
  </div><!-- /.headerInr -->
</header>
