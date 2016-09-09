@extends('front.common.layout')
@section('title', 'ご利用の流れ - エンジニアルート')
@section('content')
<div class="wrap">
    <div class="main-content flow">
        <div class="main-content-left">
            <h1 class="main-content__title">ご利用の流れ</h1>
            <hr class="hr-2px-solid-5e8796">
            <div class="main-content__body">
                <div class="content__element">
                    <p class="content__info">エンジニアルートでは、案件のプロジェクト終了後までエンジニアの皆様を全力でサポート致します。</p>

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
                    <div class="clear"><!-- 回りこみ解除 --></div>

                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 1</h2><h2>会員登録</h2></li>
                        <li class="flowWordDetail"><p>まずはじめに、<a href="/user/regist/auth">会員登録ページ</a>から申し込みをして下さい。</p></li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 2</h2><h2>案件を探す</h2></li>
                        <li class="flowWordDetail">
                            <p>エンジニアルートでは、キーワード検索だけでなく、案件の特徴やスキル／単価等の細かい条件検索で、より希望に近い案件を探し出すことができます。</p>
                            <p>また個別に案件を紹介させていただくことも可能ですので、お気軽にお問い合わせ下さい！</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 3</h2><h2>案件にエントリー</h2></li>
                        <li class="flowWordDetail">
                            <p>気になる案件が見つかりましたら、早速エントリーしましょう！</p>
                            <p>エンジニアルートでは、Webから簡単にエントリーできます。また、アップロードによるスキルシートの提出が可能です。</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 4</h2><h2>担当とのカウンセリング</h2></li>
                        <li class="flowWordDetail">
                            <p>今までのご経験やご要望をお伺いし、キャリアプランのお手伝いをさせて頂きます。</p>
                            <p>Web上では掲載していない未公開案件などもご紹介させていただきます。フリーランスとして初めて就業される方もお気軽にご相談ください。</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 5</h2><h2>クライアントとの面談</h2></li>
                        <li class="flowWordDetail">
                            <p>ご希望の案件が見つかり次第、クライアント様にご提案させていただき、面談を調整致します。</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 6</h2><h2>条件の調整</h2></li>
                        <li class="flowWordDetail">
                            <p>事前に頂いている条件・業務内容・就業時期などで、問題がないか最終確認をいたします。</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 7</h2><h2>就業開始</h2></li>
                        <li class="flowWordDetail">
                            <p>就業後も、担当コンサルタントが随時フォローいたします。</p>
                            <p>何かありましたらお気軽にご相談ください。</p>
                        </li>
                    </ul>
                    <ul class="flowWords">
                        <li class="flowWordTitle"><h2>Step 8</h2><h2>案件の終了</h2></li>
                        <li class="flowWordDetail">
                            <p>案件（プロジェクト）の終了の1ヶ月前（現プロジェクト期間）から次の案件のご相談を伺います。「期間を空けたくない」「少しお休みしたい」などのご希望に沿って再度お探しいたします。</p>
                        </li>
                    </ul>

                </div><!-- END content__element -->
            </div>
        </div>
        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
