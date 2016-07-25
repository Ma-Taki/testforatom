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
                <h1 class="pageTitle">利用規約</h1>
                <hr class="partitionLine_02">
                <div class="privacyPolicy">

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;1.</h3><h3 class="table-cell">「Engineer Route」のサービス</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                「Engineer Route(エンジニアルート)」のサービスとは、ソリッドシード株式会社(以下、弊社)が運営するIT技術者支援関連のサービスサイトおよびそれに関するサービスの総称をいいます。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;2.</h3><h3 class="table-cell">会員(利用者)</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                会員とは、Engineer Routeに個人情報およびその他の情報を登録し、弊社がこれを承認した方をいいます。
                                当該会員登録および承認後、会員はEngineer Routeにおける会員向けサービスを利用可能になります。 会員はEngineer Routeに登録した時点で、
                                本規約の内容すべてに承諾したものとみなされます。不承諾の意思表示は、Engineer Routeへの登録をしないことをもって認められることとします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;3.</h3><h3 class="table-cell">審査</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                弊社審査の手続きに従い、Engineer Routeの会員として不適格と判断した際にはご利用をお断りすることができるものとします。
                                この審査に関してのご質問についてはご返答をさし控えさせていただきます。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;4.</h3><h3 class="table-cell">責任</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                Engineer Routeの会員は、自らの意思および責任をもって、Engineer Routeに登録し、また会員は自らの意思によってEngineer Routeを利用し、
                                利用にかかわるすべての責任を負うこととします。 登録した情報は、本人がその内容について責任を負うこととします。
                                会員は、弊社がお知らせする方法に従いEngineer Routeを利用することとします。
                                会員は、本規約に違反し弊社に対し損害を与えた場合、会員は、弊社に対し直接・間接を問わず一切の損害の賠償義務を負担することとします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;5.</h3><h3 class="table-cell">会員(利用者)の禁止事項</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                以下の事項を会員の禁止事項と定めます。</br>
                                (1) 虚偽の情報を申し込み時、もしくは登録時に提供する行為。</br>
                                (2) 弊社またはクライアント企業の営業・業務を妨害する行為。</br>
                                (3) Engineer Routeの運営を妨げる行為。</br>
                                (4) 他の利用者、弊社、クライアント企業およびEngineer Route関係者の名誉や信用を毀損、または誹謗中傷する行為。</br>
                                (5) 弊社または関係者が有する著作権・商標権・プライバシー権・肖像権を侵害する行為。</br>
                                (6) Engineer Routeを通じて得た情報を本サービスの目的範囲を逸脱して使用すること。また、第三者に情報を開示もしくは漏洩する行為。</br>
                                (7) 犯罪に結びつく行為。</br>
                                (8) 公序良俗に違反する行為。</br>
                                (9) その他法令・法律に反する行為。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;6.</h3><h3 class="table-cell">除名</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                弊社は、会員が本規約に違反したと判断した場合、事前に通知することなく、当該会員の会員サービスの全部または一部の利用を一時中止、または除名することができます。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;7.</h3><h3 class="table-cell">サービスの変更</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                弊社は、会員への事前の通知なくして、サービスの変更、一時的な中断をすることができることとします。このことにより会員に損害が発生した場合でも、弊社は責任の負担をしないものとします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;8.</h3><h3 class="table-cell">個人情報の取扱い</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                個人情報の取扱いに関して、Engineer Routeに申し込む際に弊社の「 個人情報の取扱いについて 」に同意したものとみなします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">&nbsp;9.</h3><h3 class="table-cell">損害賠償</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                会員が本規約の事項に違反した結果、弊社、第三者もしくは関係者に損害を与えた場合は、会員がその損害の賠償責任を負うものとします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">10.</h3><h3 class="table-cell">免責</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                Engineer Routeの利用から生じた一切の損害について、故意または重大な過失のない限り、弊社はその責任を負わないものとします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">11.</h3><h3 class="table-cell">規約の変更</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                弊社は、会員の承諾を得ることなく、本規約を随時変更することができることとします。変更の内容についてはEngineer Route上での掲載をもって、すべての会員が承諾したこととみなします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">12.</h3><h3 class="table-cell">管轄裁判所</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                本規約に関して生じる一切の紛争については、東京地方裁判所または東京簡易裁判所を第一審の管轄裁判所とします。
                            </p>
                        </div>
                    </div>

                    <div class="block table">
                        <div class="table-row">
                            <h3 class="table-cell">13.</h3><h3 class="table-cell">著作権</h3>
                        </div>
                        <div class="table-row">
                            <p class="table-cell"></p>
                            <p class="table-cell text">
                                Engineer Routeに掲載されているすべてのコンテンツの著作権は弊社に帰属します。
                            </p>
                        </div>
                    </div>

                </div>
            </div><!-- END CONTENT -->
        </div><!-- END WRAP -->

        @include('front.common.footer')
        <!-- END FOOTER -->
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>
    </body>
</html>
