@extends('front.common.layout')
@section('title', 'お問い合わせ内容確認 - エンジニアルート')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div id="company_confirm" class="content">
        <h1 class="pageTitle">お問い合わせ内容確認</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="company">
            <p class="pageInfo">
                お問い合わせ内容をご確認頂き、「個人情報の取扱いについて」に同意の上、
                「個人情報の取扱いに同意して送信する」ボタンをクリックしてください。
            </p>
            <hr class="hr-1px-dashed-333">

            <form method="post">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お問い合わせ項目<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ FrntUtil::COMPANY_CONTACT_TYPE[$contact_type] }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お名前<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $last_name .' ' .$first_name .'（' .$last_name_kana .' '.$first_name_kana .'）' }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>会社名</p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $company_name }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>部署名</p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $department_name }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>住所</p>
                    </div>
                    <div class="input_f_value input_address">
                        <p>{{ $post_num }}</p>
                        <p>{{ $address }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>電話番号<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $phone_num }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>メールアドレス<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $mail }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>URL</p>
                    </div>
                    <div class="input_f_value">
                        <p>{{ $url }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お問い合わせ内容<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <p class="pre-wrap">{{ $contactMessage }}</p>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="textArea">
                    <div class="textAreaInr">

                        <div class="title">個人情報の取扱いについて</div>
                        <p class="titleDetail">弊社は個人情報を管理するにあたって細心の注意を払い、以下に掲げた通りに取扱います。</p>
                        <div class="caption">1.事業者の名称</div>
                        <p>ソリッドシード株式会社</p>

                        <div class="caption">2.当社の個人情報管理者</div>
                        <p>個人情報保護管理者：妹尾　準</p>

                        <div class="caption">3.連絡先</div>
                        <p>TEL : 03-5774-5557　E-MAIL : privacy@solidseed.co.jp</p>

                        <div class="caption">4.利用目的</div>
                        <p>
                            弊社サービスの業務を遂行するため、会員への連絡・サービスの案内など、会員の承諾に基づく個人情報の開示、弊社サービスの改善、マーケティング調査、
                            新たなサービスの開発、弊社サービスの運営上必要な各種お知らせ等の配信、弊社サービスの運営上必要な各種お知らせ等の配信
                        </p>

                        <div class="caption">5.第三者提供</div>
                        <p>法令等の定めに基づく場合を除き、弊社で上記目的に使用するだけで、他に提供いたしません。</p>

                        <div class="caption">6.委託</div>
                        <p>
                            弊社は事業運営上、お客様により良いサービスを提供するために業務の一部を外部に委託する場合があります。その際、業務委託先に個人情報を預託することがあります。
                            この場合、弊社は、個人情報を適切に保護できる管理体制を敷き実行していることを条件として委託先を厳選したうえで、
                            契約等において個人情報の適正管理・機密保持などによりお客様の個人情報の漏洩防止に必要な事項を取決め、適切な管理を実施させます。
                        </p>

                        <div class="caption">7.提供の任意性とその結果</div>
                        <p>個人情報を提供するか否かは任意ですが、連絡先をご提示いただけない場合には回答をお送りすることができません。</p>

                        <div class="caption">8.個人情報の開示等の要求</div>
                        <p>
                            以下の求めがあった場合には以下の通り応じます。個人情報保護管理者へ連絡して、「個人情報開示等依頼書（本人申請用）」を請求後、記載して手続を行なってください。<br />
                            (1)利用目的の通知を求められた場合には遅滞なく応じます。<br />
                            (2)本人が識別される個人情報の開示を求められた場合には遅滞なく書面により開示いたします。<br />
                            (3)本人が識別される個人情報の訂正、追加または削除（以下、訂正等という）を求められた場合には遅滞なく必要な調査を行い、その結果に基づいて訂正等を行い、
                            その旨および内容（訂正等に応じなかった場合を含む）を遅滞なく通知いたします。<br />
                            (4)本人が識別される個人情報の利用の停止、消去または第三者への提供の停止（以下、利用停止等という）を求められた場合にはこれに応じ、
                            その旨（利用停止等に応じなかった場合を含む）を遅滞なく通知いたします。<br />
                            上記(1)及び(2)につきまして、手数料はいただきません。
                        </p>
                    </div>
                </div>

                <div class="commonCenterBtn">
                        <button class="submit" name="send">個人情報の取扱いに同意して送信する</button>
                        <button class="submit" name="edit">入力内容を修正する</button></a>
                </div>
                <input type="hidden" name="contact_type" value="{{ $contact_type }}">
                <input type="hidden" name="last_name" value="{{ $last_name }}">
                <input type="hidden" name="first_name" value="{{ $first_name }}">
                <input type="hidden" name="last_name_kana" value="{{ $last_name_kana }}">
                <input type="hidden" name="first_name_kana" value="{{ $first_name_kana }}">
                <input type="hidden" name="company_name" value="{{ $company_name }}">
                <input type="hidden" name="department_name" value="{{ $department_name }}">
                <input type="hidden" name="post_num" value="{{ $post_num }}">
                <input type="hidden" name="address" value="{{ $address }}">
                <input type="hidden" name="phone_num" value="{{ $phone_num }}">
                <input type="hidden" name="mail" value="{{ $mail }}">
                <input type="hidden" name="mail_confirmation" value="{{ $mail }}">
                <input type="hidden" name="url" value="{{ $url }}">
                <input type="hidden" name="contactMessage" value="{{ $contactMessage }}">
                {{ csrf_field() }}
            </form>
        </div>
        <script type="text/javascript">
            jQuery(function($){
                $('.submit').click(function (){
                    switch ($(this).attr('name')) {
                        case 'edit':
                            $(this).parents('form').attr('action', '/company');
                            break;
                        case 'send':
                            $(this).parents('form').attr('action', '/company/complete');
                            break;
                    }
                    $(this).parents('form').submit();
                });

                $(".commonCenterBtn button").tile();
            });
        </script>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
