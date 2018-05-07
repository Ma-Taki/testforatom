<?php
    use App\Libraries\FrontUtility as FrntUtil;
    use App\Models\Tr_wp_terms;
?>
<section class="sideInfo">
  <div class="invisible-sp">
    <ul>
@if(FrntUtil::isLogin() && Request::path() != '/')
      <li>
        <div class="hello_user bg">
          <p class="wsnw">こんにちは、<br /><a href="{{ url('/user') }}">{{ FrntUtil::getLoginUserName() }}さん</a></p>
        </div>
      </li>
@elseif(FrntUtil::isLogin() && Request::path() == '/')
@else
      <li>
        <a href="{{ url('/user/regist/auth') }}">
          <img src="/front/images/sbnrSignup.png" alt="無料会員登録。案件紹介をご希望の方はこちら">
        </a>
      </li>
@endif
<!--
            <li>
                <a href="{{ url('/') }}">
                    <img src="/front/images/sBnr01.png" alt="Engineer-Route News">
                </a>
            </li>
-->
      <li>
        <a href="{{ url('/question#aboutFreeEngineer') }}">
          <img src="/front/images/sBnr02.png" alt="フリーエンジニアになるためには？">
        </a>
      </li>
      <li>
        <a href="{{ url('/flow') }}">
          <img src="/front/images/sBnr03.png" alt="ご利用案内">
        </a>
      </li>
      <li>
        <a href="{{ url('/column') }}">
          <img src="/front/images/sBnr05.png" alt="コラム">
        </a>
      </li>
      <li>
        <a href="{{ url('/question') }}">
          <img src="/front/images/sBnr04.png" alt="よくある質問">
        </a>
      </li>
<!--       <li>
        <a href="http://jitel.jp/" target="_blank">
          <img src="/front/images/sbnr_jitel.jpg" alt="株式会社日本IT教育研修機構">
        </a>
      </li> -->
    </ul>

  </div>

  <div class="invisible-pc invisible-tab">
    <ul>
<!--
            <li><a href="/user/regist"><img src="/front/images/sBnr01.png" alt="Engineer-Route News"></a></li>
-->
      <li><a href="/question#aboutFreeEngineer"><img src="/front/images/sBnr02.png" alt="フリーエンジニアになるためには？"></a></li>
      <li><a href="/flow"><img src="/front/images/sBnr03.png" alt="ご利用案内"></a></li>
      <li><a href="/column"><img src="/front/images/sBnr05.png" alt="コラム"></a></li>
      <li><a href="/question"><img src="/front/images/sBnr04.png" alt="よくある質問"></a></li>
    </ul>
  </div>

  <div>

<?php
  //コラム最新５件
  $posts=FrntUtil::getRecentPosts(5);
?>

    <div class='wordpress-sidebar'>
      <ul>
        <p>最新コラム</p>
        @foreach($posts as $post)
        <a href = "{{$post->guid}}">
          <li>
              {{$post->post_title}}
              <span class='date'>{{$post->post_date->format('Y.m.d')}}</span>
          </li>
        </a>
        @endforeach
      </ul>
    </div>

    <div class='wordpress-sidebar'>
      <ul>
        <p>コラムカテゴリー</p>
        @foreach(Tr_wp_terms::getAllCategories() as $category)
        <a href = "/column/category/{{ $category->slug }}/">
          <li>{{ $category->name }}</li>
        </a>
        @endforeach
      </ul>
    </div>
  </div>

</section><!-- END SIDE-INFO -->
