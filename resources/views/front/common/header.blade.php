<?php use App\Libraries\FrontUtility as FrntUtil; ?>
<header>
    <div class="headerInr">
        <p>フリーランス、フリーエンジニアのためのIT系求人・案件情報提供サイト「エンジニアルート」</p>
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
        <h1><a href="{{ url('/') }}" title="エンジニアルート"></a></h1>

        <div class="search">
            <form action="/item/keyword" method="get">
                <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                <button class="searchBtn" type="submit" />
            </form>
        </div><!-- /.search -->
        <a href="/login/sns/twitter">Twitterログイン</a>
        <a href="/login/sns/facebook">Facebookログイン</a>
        <a href="/login/sns/github">Githubログイン</a>
    </div><!-- /.headerInr -->
</header>
