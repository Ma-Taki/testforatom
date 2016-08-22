<?php use App\Libraries\FrontUtility as FrntUtil; ?>
<section class="sideInfo">
    <div class="invisible-sp">
        <ul>
@if(FrntUtil::isLogin() && Request::path() != '/')
            <li>
                <div class="hello_user">
                    <p class="wsnw">こんにちは、<wbr><a href="{{ url('/user') }}">{{ FrntUtil::getLoginUserName() }}さん</a></p>
                </div>
            </li>
@elseif(FrntUtil::isLogin() && Request::path() == '/')
@else
            <li>
                <a href="{{ url('/user/regist') }}">
                    <img src="/front/images/sbnrSignup.png" alt="会員登録はこちら">
                </a>
            </li>
@endif

            <li>
                <a href="{{ url('/') }}">
                    <img src="/front/images/sBnr01.png" alt="Engineer-Route News">
                </a>
            </li>
            <li>
                <a href="{{ url('/front/question#aboutFreeEngineer') }}">
                    <img src="/front/images/sBnr02.png" alt="フリーエンジニアになるためには？">
                </a>
            </li>
            <li>
                <a href="{{ url('/front/flow') }}">
                    <img src="/front/images/sBnr03.png" alt="ご利用案内">
                </a>
            </li>
            <li>
                <a href="{{ url('/front/question') }}">
                    <img src="/front/images/sBnr04.png" alt="よくある質問">
                </a>
            </li>
        </ul>
    </div>
    <div class="invisible-pc invisible-tab">
        <ul>
            <li><a href="/user/regist"><img src="/front/images/sBnr01.png"></a></li>
            <li><a href="/front/question#aboutFreeEngineer"><img src="/front/images/sBnr02.png"></a></li>
            <li><a href="/front/flow"><img src="/front/images/sBnr03.png"></a></li>
            <li><a href="/front/question"><img src="/front/images/sBnr04.png"></a></li>
        </ul>
    </div>
</section><!-- END SIDE-INFO -->
