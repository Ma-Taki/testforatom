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
                <hr class="partitionLine_02">
                <div class="privacyPolicy">
                    <h2 class="contentTitle">個人情報保護方針</h2>
                    <hr class="partitionLine_01">
                    <div class="privacyPolicyInr">

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">1.</h3><h3 class="table-cell">個人情報保護の目的</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社では、サービスを提供する上で数多くの「情報」を取り扱っております。その中でも「個人情報」は大切に保護すべき情報であると認識しております。
                                    個人情報の保護に関する各種法令や規範を遵守するだけでなく、保護の徹底、仕組み作り及び継続的改善を行うことが社会的責務であると考えております。
                                    以下の通り「個人情報保護方針」を定め、全ての役員、従業員に周知し、徹底を図ります。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">2.</h3><h3 class="table-cell">個人情報の取得について</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社が個人情報の取得を行う場合は、</br>
                                    (1) 利用目的の達成のために必要な範囲のみ収集します。</br>
                                    (2)適法且つ公正な手段を用い収集します。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">3.</h3><h3 class="table-cell">個人情報の利用について</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社が取得した個人情報は、適切に管理し、特定された利用目的の達成に必要な範囲を超えた個人情報の取扱い（利用、提供等）を行いません。
                                    年１回実施する内部監査において目的外利用の有無をチェック致します。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">4.</h3><h3 class="table-cell">個人情報の適正管理について</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社は、個人情報の正確性及び安全性を確保するために、セキュリティ対策をはじめとする安全対策を実施し、
                                    個人情報への不正アクセス、個人情報の漏えい、滅失、き損などを確実に予防します。
                                    また、市場のセキュリティ事故の実例、お客さまからの御要望などにより改善が必要とされたときには、速やかにこれを是正し、予防に努めます。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">5.</h3><h3 class="table-cell">法令遵守について</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社は、個人情報に関連する法令及び国が定める指針その他の規範を遵守します。また、これらの法令及び国が定める指針その他の規範に当社の管理の仕組みを常に適合させます。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">6.</h3><h3 class="table-cell">継続的改善について</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    内部監査及び個人情報保護マネジメントシステムレビューの機会を通じて、管理の仕組みを継続的に改善し、常に最良の状態を維持します。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">7.</h3><h3 class="table-cell">苦情相談窓口</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    当社は、個人情報に関する苦情及び相談には適切な体制を整備し、対応します。 個人情報保護に関するお問い合わせは、個人情報保護担当者宛に電話またはメールにてお願いいたします。
                                    （当社休日を除く）</br>
                                    TEL : 03-5774-5557</br>
                                    E-MAIL : privacy@solidseed.co.jp
                                </p>
                            </div>
                        </div>

                        <div class="block tAlignRight">
                            <p>
                                制定日 2011年2月1日</br>
                                </br>
                                ソリッドシード株式会社</br>
                                代表取締役　妹尾　準
                            </p>
                        </div>
                    </div><!-- ./privacyPolicyInr -->

                    <h2 class="contentTitle">個人情報の取扱いについて</h2>
                    <hr class="partitionLine_01">
                    <div class="privacyPolicyInr">

                        <div class="block">
                            <p>
                                弊社は個人情報を管理するにあたって細心の注意を払い、以下に掲げた通りに取扱います。
                            </p>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">1.</h3><h3 class="table-cell">事業者の名称</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    ソリッドシード株式会社
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">2.</h3><h3 class="table-cell">当社の個人情報管理者</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    個人情報保護管理者：妹尾　準
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">3.</h3><h3 class="table-cell">連絡先</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    TEL : 03-5774-5557　E-MAIL : privacy@solidseed.co.jp
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">4.</h3><h3 class="table-cell">利用目的</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    弊社サービスの業務を遂行するため、会員への連絡・サービスの案内など、会員の承諾に基づく個人情報の開示、弊社サービスの改善、
                                    マーケティング調査、新たなサービスの開発、弊社サービスの運営上必要な各種お知らせ等の配信、弊社サービスの運営上必要な各種お知らせ等の配信。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">5.</h3><h3 class="table-cell">第三者提供</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    法令等の定めに基づく場合を除き、弊社で上記目的に使用するだけで、他に提供いたしません。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">6.</h3><h3 class="table-cell">委託</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    弊社は事業運営上、お客様により良いサービスを提供するために業務の一部を外部に委託する場合があります。その際、業務委託先に個人情報を預託することがあります。
                                    この場合、弊社は、個人情報を適切に保護できる管理体制を敷き実行していることを条件として委託先を厳選したうえで、
                                    契約等において個人情報の適正管理・機密保持などによりお客様の個人情報の漏洩防止に必要な事項を取決め、適切な管理を実施させます。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">7.</h3><h3 class="table-cell">提供の任意性とその結果</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    個人情報を提供するか否かは任意ですが、連絡先をご提示いただけない場合には回答をお送りすることができません。
                                </p>
                            </div>
                        </div>

                        <div class="block table">
                            <div class="table-row">
                                <h3 class="table-cell">8.</h3><h3 class="table-cell">個人情報の開示等の要求</h3>
                            </div>
                            <div class="table-row">
                                <p class="table-cell">&nbsp;</p>
                                <p class="table-cell text">
                                    以下の求めがあった場合には以下の通り応じます。個人情報保護管理者へ連絡して、「個人情報開示等依頼書（本人申請用）」を請求後、記載して手続を行なってください。</br>
                                    (1) 利用目的の通知を求められた場合には遅滞なく応じます。</br>
                                    (2) 本人が識別される個人情報の開示を求められた場合には遅滞なく書面により開示いたします。</br>
                                    (3) 本人が識別される個人情報の訂正、追加または削除（以下、訂正等という）を求められた場合には遅滞なく必要な調査を行い、その結果に基づいて訂正等を行い、
                                    その旨および内容（訂正等に応じなかった場合を含む）を遅滞なく通知いたします。</br>
                                    (4) 本人が識別される個人情報の利用の停止、消去または第三者への提供の停止（以下、利用停止等という）を求められた場合にはこれに応じ、
                                    その旨（利用停止等に応じなかった場合を含む）を遅滞なく通知いたします。</br>
                                    上記(1)及び(2)につきまして、手数料はいただきません。
                                </p>
                            </div>
                        </div>

                    </div><!-- ./privacyPolicyInr -->
                </div>
            </div><!-- END CONTENT -->
        </div><!-- END WRAP -->

        @include('front.common.footer')
        <!-- END FOOTER -->
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>
    </body>
</html>
