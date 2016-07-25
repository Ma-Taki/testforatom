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
            <div id="privacyPolicy" class="content_">
                <h1 class="pageTitle">プライバシーポリシー</h1>
                <hr>
                <div class="privacyPolicy">
                    <h3 class="contentTitle">個人情報保護方針</h2>
                    <hr class="partitionLine">
                    <h4 class="subTitle">1.個人情報保護の目的</h3>
                    <p>
                        当社では、サービスを提供する上で数多くの「情報」を取り扱っております。その中でも「個人情報」は大切に保護すべき情報であると認識しております。
                        個人情報の保護に関する各種法令や規範を遵守するだけでなく、保護の徹底、仕組み作り及び継続的改善を行うことが社会的責務であると考えております。
                        以下の通り「個人情報保護方針」を定め、全ての役員、従業員に周知し、徹底を図ります。
                    </p>




                </div>

            </div><!-- END CONTENT -->
        </div><!-- END WRAP -->

        @include('front.common.footer')
        <!-- END FOOTER -->
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>
    </body>
</html>
