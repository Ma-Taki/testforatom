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
                <div id="aboutERoute" class="content-left">
                    <h1 class="title">エンジニアルートとは</h1>
                    <hr class="partitionLine_02">
                    <div class="aboutERouteInr" >
                        <img src="/front/images/bnr_about.jpg" alt="エンジニアルートとは" />
                        <p>
                            Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、
                            それぞれの方に合った案件検索から、カウンセリング、プロジェクトの終了まで
                            全てのプロセスを徹底的にサポート致します。
                        </p>
                        <p>
                            それぞれの道（ルート）を見定め、そして一歩ずつ進んでいく為に。
                            そして私達も、皆様とともに一緒に歩んでいく為に、
                            一つずつ丁寧にお手伝いしていきます。
                        </p>
                    </div>
                </div>
                <!-- END CONTENT-LEFT -->

                <div class="content-right">
                    <div class="invisible-sp">
                        @include('front.common.sideInfo')
                    </div>
                    <div class="invisible-pc invisible-tab">
                        @include('front.common.sideInfo_sp')
                    </div>
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
