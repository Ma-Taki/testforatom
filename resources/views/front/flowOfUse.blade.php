<?php
use App\Libraries\ModelUtility as mdlUtil;
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。">
        <meta name="keywords" content="IT案件,案件情報,求人,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP">
        <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
        <title>フリーランス、フリーエンジニアのためのIT系求人・案件情報提供サイト「エンジニアルート」</title>
        <link rel="canonical" href="http://www.engineer-route.com/">
        <link rel="icon" href="{{ url('/front/favicon.ico') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick-theme.css') }}">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/slick.min.js') }}"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/jquery.tile.js') }}"></script>
    </head>
    <body>

        @include('front.common.header')
        <!-- END HEADER -->
        @include('front.common.navi')
        <!-- END NAVI -->

        <div class="wrap">
            <div class="content_">
                <div class="content-left">
                    <h1 class="title">ご利用の流れ</h1>
                    <hr>
                    <h2 class="detail">エンジニアルートでは、案件のプロジェクト終了後までエンジニアの皆様を全力でサポート致します。</h1>
                    <ul class="flowImages">
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 1</h4></li>
                                <li><img alt="会員登録" src="/front/images/guide01.png"></li>
                                <li><h4>会員登録</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 2</h4></li>
                                <li><img alt="案件検索" src="/front/images/guide02.png"></li>
                                <li><h4>案件検索</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 3</h4></li>
                                <li><img alt="エントリー" src="/front/images/guide03.png"></li>
                                <li><h4>エントリー</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 4</h4></li>
                                <li><img alt="カウンセリング" src="/front/images/guide04.png"></li>
                                <li><h4>カウンセリング</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 5</h4></li>
                                <li><img alt="面談" src="/front/images/guide05.png"></li>
                                <li><h4>面談</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 6</h4></li>
                                <li><img alt="最終調整" src="/front/images/guide06.png"></li>
                                <li><h4>最終調整</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 7</h4></li>
                                <li><img alt="就業開始" src="/front/images/guide07.png"></li>
                                <li><h4>就業開始</h4></li>
                            </ul>
                        </li>
                        <li class="flowImage">
                            <ul>
                                <li><h4 class="color-01">Step 8</h4></li>
                                <li><img alt="案件終了" src="/front/images/guide08.png"></li>
                                <li><h4>案件終了</h4></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="clear flowWords">
                        <li class="flowWordTitle"><h2>Step 1</h2><h2>会員登録</h2></li>
                        <li class="flowWordDetail"><h3>まずはじめに、会員登録ページから申し込みをして下さい。</h3></li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 2</h2><h2>案件を探す</h2></li>
                        <li class="flowWordDetail">
                            <h3>エンジニアルートでは、キーワード検索だけでなく、案件の特徴やスキル／単価等の細かい条件検索で、より希望に近い案件を探し出すことができます。</h3>
                            <h3>また個別に案件を紹介させていただくことも可能ですので、お気軽にお問い合わせ下さい！</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 3</h2><h2>案件にエントリー</h2></li>
                        <li class="flowWordDetail">
                            <h3>気になる案件が見つかりましたら、早速エントリーしましょう！</h3>
                            <h3>エンジニアルートでは、Webから簡単にエントリーできます。また、アップロードによるスキルシートの提出が可能です。</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 4</h2><h2>担当とのカウンセリング</h2></li>
                        <li class="flowWordDetail">
                            <h3>今までのご経験やご要望をお伺いし、キャリアプランのお手伝いをさせて頂きます。</h3>
                            <h3>Web上では掲載していない未公開案件などもご紹介させていただきます。フリーランスとして初めて就業される方もお気軽にご相談ください。</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 5</h2><h2>クライアントとの面談</h2></li>
                        <li class="flowWordDetail">
                            <h3>ご希望の案件が見つかり次第、クライアント様にご提案させていただき、面談を調整致します。</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 6</h2><h2>条件の調整</h2></li>
                        <li class="flowWordDetail">
                            <h3>事前に頂いている条件・業務内容・就業時期などで、問題がないか最終確認をいたします。</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 7</h2><h2>就業開始</h2></li>
                        <li class="flowWordDetail">
                            <h3>就業後も、担当コンサルタントが随時フォローいたします。</h3>
                            <h3>何かありましたらお気軽にご相談ください。</h3>
                        </li>
                    </ul>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 8</h2><h2>案件の終了</h2></li>
                        <li class="flowWordDetail">
                            <h3>案件（プロジェクト）の終了の1ヶ月前（現プロジェクト期間）から次の案件のご相談を伺います。「期間を空けたくない」「少しお休みしたい」などのご希望に沿って再度お探しいたします。</h3>
                        </li>
                    </ul>

                </div>
                <!-- END CONTENT-LEFT -->

                <div class="content-right">
                    @include('front.common.sideInfo')
                </div>
                <div class="clear"></div>
                <!-- END CONTENT-RIGHT -->
            </div>
        </div><!-- END WRAP -->

        @include('front.common.footer')
        <!-- END FOOTER -->
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>
    </body>
</html>
