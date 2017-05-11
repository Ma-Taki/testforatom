<?php
 use App\Libraries\FrontUtility as FrntUtil;
 use App\Libraries\CookieUtility as CkieUtil;
 use App\Models\Tr_users;
 use App\Models\Tr_considers;
 use App\Http\Controllers\ConsiderController;
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

<!-- 検討中案件数カウント  -->
<?php
$considers_length = 0;
if(FrntUtil::isLogin()){
  $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
  if($cookie){
    $user = Tr_considers::where("user_id",$cookie)->where("delete_flag",0)->get();
    $considers_length = count($user);
  }
}else{
  $considers_length = CkieUtil::get("considers") ? count(CkieUtil::get("considers")) : 0;
}
?>
        <li><a href="/considers"><span>検討中</span><span><span id="considers_length"> {{ $considers_length }}</span><span id="considers_unit">件</span></span></a></li>
@if(FrntUtil::isLogin())
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
        <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
        <button class="searchBtn" type="submit"></button>
      </form>
    </div><!-- /.search -->
  </div><!-- /.headerInr -->
</header>
