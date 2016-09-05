@extends('front.common.layout')
@section('title', 'Q&A - エンジニアルート')
@section('content')
<div class="wrap">
    <div class="content">
        <div class="content-left">
            <h1 class="pageTitle">Q&amp;A&nbsp;&nbsp;ーよくある質問ー</h1>
            <hr class="hr-2px-solid-5e8796">
            <div id="question">
                <h2 class="subTitle">エンジニアルートについて</h2>

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">パスワードを忘れてしまった</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>下記URLからパスワードの再設定を行います。</p>
                                <a href="/user/reminder">
                                        <p class="url">{{ url('/user/reminder') }}</p>
                                </a>
                                <p>ご自身のメールアドレスを入力して頂き、送信して下さい。</p>
                                <p>その後、パスワード再設定ページのURLが記載されたメールが届きますので、そちらからパスワードを再設定して頂けます。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">利用料について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>会員登録、案件参照など当サイトは一切費用は掛かりません。すべて無料でお使い頂けます。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">取り扱う案件の種類について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>システム開発案件が中心ですが、インフラ系・デザイン系・事務系など幅広くご紹介することが可能です。</p>
                                <p>非公開案件もございますので、まずはご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">契約形態について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>業務請負契約(個人)、契約社員、派遣契約、法人契約のいずれかになります。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">支払いサイトについて</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>案件によって異なりますが、月末締めの翌月末（30日）お支払いから月末締めの翌々月末（60日）お支払いになります。</p>
                                <p>その他の支払希望ございましたら弊社担当者へご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">掲載単価と受取金額について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>スキルや経験によって異なりますが、弊社からお渡しする想定金額です。</p>
                                <p>ただし、必ずしも表示されている金額とならない場合がございますので、予めご了承ください。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">就業後のフォローについて</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>就業後も、弊社担当者が随時フォローいたします。</p>
                                <p>何かありましたらお気軽にご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">プロジェクト終了後について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>案件終了の1か月前(現プロジェクト期間中)から次の案件などのご相談を伺います。</p>
                                <p>期間を空けたくない、少しお休みしたいなどのご希望に沿って再度お探し致します。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">応募について</h2>

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">応募までの手順</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p><a href="/frow">「ご利用の流れ」</a>をご参照ください。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">応募してから結果が出るまでの期間について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>カウンセリング済みの場合、案件によっても異なりますが、早いもので1日で就業が決まることもございます。</p>
                                <p>平均すると登録～就業まで10日前後です。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">応募取り消し方法について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>弊社担当者までご連絡をお願い致します。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">在職中でも応募について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>在職中の応募も可能です。応募の際、就業開始可能日をお教え下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">掲載案件の応募期間について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>いつでも応募可能です。</p>
                                <p>ただし掲載日が1ヶ月以上前の案件は既に充足している場合はございます。</p>
                                <p>案件は日々掲載しておりますので、ご興味のある案件がございましたら是非ご応募下さい。</p>
                                <p>また、非公開案件も多数ございますので、弊社担当者にお気軽にお問い合わせ下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">応募資格をすべて満たしていない場合について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>案件によっては多少のスキル不足があっても、周りの方のサポートを受けながら参画できる案件もあります。</p>
                                <p>少しでも気になる案件を見つけたら積極的に問い合わせされることをお勧め致します。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">未経験の言語や業界・業種への就業について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>就業は可能ですが、条件面がご希望に満たない可能性もございますので、その際はご相談させて頂きます。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">年齢制限について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>案件により年齢制限がある場合もございますが、様々な案件がございますので、まずは一度カウンセリングをさせて頂ければと思います。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">面接について</h2>

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">カウンセリング（事前面談）の時間調整・出張面談について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>平日でしたらできる限りご希望の時間に合わせて調整をさせて頂きます。</p>
                                <p>また、状況によっては出張面談も致します。詳細はお気軽にご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">面接時の服装について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>平カウンセリングについては私服でも問題ありません。</p>
                                <p>お客様面談についてはスーツが基本となりますが、都度お伝えさせて頂きます。
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">面接の内容について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>カウンセリング時に今までのご経歴・業務概要・得意分野・希望事項などを伺います。</p>
                                <p>アピールポイント・希望条件がございましたら是非お聞かせください。</p>
                                <p>お客様面談については、面談が入った際は、事前に弊社担当者から想定される質問などをお伝えいたします。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">来社について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>よりマッチした案件をご紹介するため、可能なかぎりご来社いただき、お話させて頂いております。</p>
                                <p>また、サイト上では公開できない非公開案件などの情報もございますので、是非一度ご来社下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">職務経歴書のフォーマットについて</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>フォーマットはご用意しておりますが、お手持ちの職務経歴書がある場合はそちらでも構いません。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">職務経歴書の書き方について</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>ご不明点がございましたら<a href="/contact">「お問い合わせ」</a>フォーム、又は弊社までお気軽にご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">職務経歴書の内容を更新したい場合</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>再度アップロード頂くか、弊社担当者へご連絡をお願い致します。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <h2 id="aboutFreeEngineer" class="subTitle">フリーエンジニアについて</h2>

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">フリーエンジニアになるためには</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>フリーエンジニアになるために必要な経験は特にございません。</p>
                                <p>正社員として2～3年の経験を経た後、独立される方も多くいらっしゃいます。</p>
                                <p>独立する際に相談に来られる方もいらっしゃいますので、お気軽にご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">フリーの年金・保険・確定申告</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p>まず申告書の入手から行いましょう。</p>
                                <p>申告書は最寄の税務署や国税庁のHPより入手することが出来ます。</p>
                                <p>不明点があれば税務署に問い合わせされることをお勧め致します。</p>
                                <p>また、国税庁のHPに電子納付(e-TAX)や確定申告の作成コーナーなどもあります。</p>
                                <p>※弊社契約社員としてご就業頂く場合は、厚生年金基金・健康保険・雇用保険にご加入頂きます。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">その他</h2>

                <div class="question">
                    <div class="questionElement">
                        <img alt="クエスチョン" src="/front/images/ico-question.png">
                        <p class="questionText">Q&amp;Aで解決できない場合</p>
                        <p class="questionOpen">+</p>
                    </div>
                    <div class="anserElement">
                        <div class="anserElementInr">
                            <img alt="アンサー" src="/front/images/ico-answer.png">
                            <div class="anserText">
                                <p><a href="/contact">「お問い合わせ」</a>フォーム、又は弊社までお気軽にご相談下さい。</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine">

            </div>
        </div><!-- END CONTENT-LEFT -->

        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>
    </div>
</div><!-- END WRAP -->
<script type="text/javascript">
    $(function() {
        $('.anserText').each(function(){
            var q_img = $(this).parents('.question').find('.questionElement img').height();
            var q_text = $(this).parents('.question').find('.questionText').height();
            $(this).css('padding-top',(q_img - q_text) / 2);
            $(this).css('padding-bottom',(q_img - q_text) / 2);
        });

        $('.anserElement').hide();
        $(".questionText").click(function(){
            var $ansr = $(this).parents('.question').find('.anserElement');
            var $mark = $(this).parents('.questionElement').find('.questionOpen');
            $ansr.slideToggle('normal', function(){
                if($ansr.is(':hidden')) {
                    $mark.text('+');
                } else {
                    $mark.text('-');
			    };
            });
		});
    });
</script>
@endsection
