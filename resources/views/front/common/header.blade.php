<header>
    <div class="headerInr">
        <p>フリーランス、フリーエンジニアのためのIT系求人・案件情報提供サイト<span class="invisible-tab">「エンジニアルート」</span></p>
        <div class="user">
            <ul>
                <li><a href="/user/regist/input" class="signin">新規登録</a></li>
                <li><a href="/login">ログイン</a></li>
            </ul>
        </div>
        <h1 class="alignleft"><a href="{{ url('/') }}" title="エンジニアルート"></a></h1>

        <div class="search">
            <form action="/front/keyword" method="get">
                <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                <button class="searchBtn" type="submit" />
            </form>
        </div><!-- /.search -->
    </div><!-- /.headerInr -->
</header>
