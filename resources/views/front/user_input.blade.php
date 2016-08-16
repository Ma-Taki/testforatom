@extends('front.common.layout')
@section('content')
<?php
    use App\Models\Ms_contract_types;
    use App\Models\Ms_prefectures;
    use App\Libraries\ModelUtility as MdlUtil;
    use Carbon\Carbon;
 ?>
<div class="wrap">
    <div id="user_input" class="content">
        <h1 class="pageTitle">新規会員登録</h1>
        <hr class="partitionLine_02">
        <div class="contact">
{{-- error：validation --}}
@if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
    @endforeach
                </ul>
            </div>
@endif
{{-- error：custom --}}
@if(count($errors) == 0)
    @if(Session::has('custom_error_messages'))
            <div class="alert alert-danger">
                <ul>
        @foreach(Session::get('custom_error_messages') as $message)
                    <li>{{ $message }}</li>
        @endforeach
                </ul>
            </div>
    @endif
@endif
            <p class="pageInfo">以下のフォームに必要な情報を入力してください。<span class="color-red">※</span>印の項目は入力必須です。</p>
            <hr class="partitionLine_01">

            <form method="post" name="userForm" action="{{ url('/user/regist') }}">
                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お名前<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_name">
                        <label>性　<input type="text" maxlength="15" name="last_name" value="{{ old('last_name') }}" placeholder="ソリッド"></label>
                        <label>名　<input type="text" maxlength="15" name="first_name" value="{{ old('first_name') }}" placeholder="太郎"></label>
                        <label>せい<input type="text" maxlength="15" name="last_name_kana" value="{{ old('last_name_kana') }}" placeholder="そりっど"></label>
                        <label>めい<input type="text" maxlength="15" name="first_name_kana" value="{{ old('first_name_kana') }}" placeholder="たろう"></label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>性別<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_gender">
                        <label><input type="radio" name="gender" @if(old('gender') == "Male") checked @endif value="Male">男性</label>
                        <label><input type="radio" name="gender" @if(old('gender') == "Female") checked @endif value="Female">女性</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>生年月日<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_birth">
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_y" name="birth_year">
@for($year = Carbon::today()->year - 18; $year >= 1940; $year--)
                                    <option @if(old('birth_year') == $year) selected @endif value="{{ $year }}">{{ $year }}</option>
@endfor
                                </select>
                            </span>
                        年</label>
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_m" name="birth_month">
@for($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $month }}</option>
@endfor
                                </select>
                            </span>
                        月</label>
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_d" name="birth_day">
@for($day = 1; $day <= 31; $day++)
                                    <option value="{{ $day }}">{{ $day }}</option>
@endfor
                                </select>
                            </span>
                        日</label>
                    </div>
                </div>
                <hr class="clear partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>最終学歴</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" name="education" maxlength="50"　placeholder="例）〇〇大学〇〇学部〇〇学科 卒">
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>国籍</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" name="country" maxlength="20" value="日本" placeholder="例）日本">
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>希望の契約形態</p>
                    </div>
                    <div class="input_f_value input_contract">
@foreach(Ms_contract_types::getActual() as $value)
                        <label>
                            <input type="checkbox" name="contract_types[]" value="{{ $value->id }}">{{ $value->name }}
                        </label>
@endforeach
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>住所（都道府県）<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_prefecture" name="prefecture_id">
@foreach(Ms_prefectures::getNotIndexOnly() as $value)
                                    <option @if($value->id == MdlUtil::PREFECTURES_ID_TOKYO) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
@endforeach
                                </select>
                            </span>
                        </label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>最寄り駅</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" maxlength="30" name="station" placeholder="例）〇〇線〇〇駅">
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>メールアドレス<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="text" maxlength="256" name="email" placeholder="例）info@solidseed.co.jp">（半角）</label>
                        <label><input type="text" maxlength="256" name="email_confirmation" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>電話番号<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="text" maxlength="20" name="phone" placeholder="例）03-5774-5557">（半角 *ハイフン付き）</label>
                        <label><input type="text" maxlength="20" name="phone_confirmation" placeholder="確認のため、もう一度入力してください。">（半角*ハイフン付き）</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>パスワード<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="password" maxlength="20" name="password" placeholder="*6~20文字以内の半角英数字記号"></label>
                        <label><input type="password" maxlength="20" name="password_confirmation" placeholder="確認のため、もう一度入力してください。"></label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <p class="textAreaInfo">下記の「利用規約」、「個人情報の取扱いについて」に同意の上、「利用規約・個人情報の取扱いに同意して会員登録する」ボタンをクリックしてください。</p>
                <div class="textArea">
                    <div class="textAreaInr">

                        <div class="title">利用規約</div>
                        <div class="caption">1.「Engineer Route」のサービス</div>
                        <p>
                            「Engineer Route(エンジニアルート)」のサービスとは、
                            ソリッドシード株式会社(以下、弊社)が運営するIT技術者支援関連のサービスサイトおよびそれに関するサービスの総称をいいます。
                        </p>

                        <div class="caption">2.会員(利用者)</div>
                        <p>
                            会員とは、Engineer Routeに個人情報およびその他の情報を登録し、弊社がこれを承認した方をいいます。
                            当該会員登録および承認後、会員はEngineer Routeにおける会員向けサービスを利用可能になります。 会員はEngineer Routeに登録した時点で、
                            本規約の内容すべてに承諾したものとみなされます。不承諾の意思表示は、Engineer Routeへの登録をしないことをもって認められることとします。
                        </p>

                        <div class="caption">3.審査</div>
                        <p>
                            弊社審査の手続きに従い、Engineer Routeの会員として不適格と判断した際にはご利用をお断りすることができるものとします。
                            この審査に関してのご質問についてはご返答をさし控えさせていただきます。
                        </p>

                        <div class="caption">4.責任</div>
                        <p>
                            Engineer Routeの会員は、自らの意思および責任をもって、Engineer Routeに登録し、また会員は自らの意思によってEngineer Routeを利用し、
                            利用にかかわるすべての責任を負うこととします。 登録した情報は、本人がその内容について責任を負うこととします。
                            会員は、弊社がお知らせする方法に従いEngineer Routeを利用することとします。
                            会員は、本規約に違反し弊社に対し損害を与えた場合、会員は、弊社に対し直接・間接を問わず一切の損害の賠償義務を負担することとします。
                        </p>

                        <div class="caption">5.会員(利用者)の禁止事項</div>
                        <p>
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

                        <div class="caption">6.除名</div>
                        <p>
                            弊社は、会員が本規約に違反したと判断した場合、事前に通知することなく、当該会員の会員サービスの全部または一部の利用を一時中止、または除名することができます。
                        </p>

                        <div class="caption">7.サービスの変更</div>
                        <p>
                            弊社は、会員への事前の通知なくして、サービスの変更、一時的な中断をすることができることとします。このことにより会員に損害が発生した場合でも、弊社は責任の負担をしないものとします。
                        </p>

                        <div class="caption">8.個人情報の取扱い</div>
                        <p>
                            個人情報の取扱いに関して、Engineer Routeに申し込む際に弊社の「 個人情報の取扱いについて 」に同意したものとみなします。
                        </p>

                        <div class="caption">9.損害賠償</div>
                        <p>
                            会員が本規約の事項に違反した結果、弊社、第三者もしくは関係者に損害を与えた場合は、会員がその損害の賠償責任を負うものとします。
                        </p>

                        <div class="caption">10.免責</div>
                        <p>
                            Engineer Routeの利用から生じた一切の損害について、故意または重大な過失のない限り、弊社はその責任を負わないものとします。
                        </p>

                        <div class="caption">11.規約の変更</div>
                        <p>
                            弊社は、会員の承諾を得ることなく、本規約を随時変更することができることとします。変更の内容についてはEngineer Route上での掲載をもって、すべての会員が承諾したこととみなします。
                        </p>

                        <div class="caption">12.管轄裁判所</div>
                        <p>
                            本規約に関して生じる一切の紛争については、東京地方裁判所または東京簡易裁判所を第一審の管轄裁判所とします。
                        </p>

                        <div class="caption">13.著作権</div>
                        <p>
                            Engineer Routeに掲載されているすべてのコンテンツの著作権は弊社に帰属します。
                        </p>
                        <hr class="partitionLine_01">

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
                <button type="submit" id="confirmBtn" class="wsnw">利用規約・個人情報の取扱いに<wbr>同意して会員登録する</button>
            </div>
            {{ csrf_field() }}
            </form>
        </div>
        <script type="text/javascript">
            $('form[name="userForm"]').submit(function(){
                var year = $('#slctBx_birth_y').val();
                var month = $('#slctBx_birth_m').val();
                var day = $('#slctBx_birth_d').val();
                $('<input />')
                    .attr('type', 'hidden')
                    .attr('name', 'birth')
                    .attr('value', year+'/'+month+'/'+day)
                    .appendTo($(this));
            });
        </script>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
