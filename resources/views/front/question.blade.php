@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div class="content_">
        <div class="content-left">
            <h1 class="title">Q&amp;A&nbsp;&nbsp;ーよくある質問ー</h1>
            <hr>
            <div id="question">
                <h2 class="subTitle">エンジニアルートについて</h2>

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">パスワードを忘れてしまった</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>下記URLからパスワードの再設定を行います。</p>
                            <a href="{{ url('/user/reminder') }}">
                                <p class="url">https://www.engineer-route.com/user/reminder</p>
                            </a>
                            </br>
                            <p>ご自身のメールアドレスを入力して頂き、送信して下さい。</p>
                            <p>その後、パスワード再設定ページのURLが記載されたメールが届きますので、そちらからパスワードを再設定して頂けます。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">利用料について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>会員登録、案件参照など当サイトは一切費用は掛かりません。すべて無料でお使い頂けます。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">取り扱う案件の種類について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                システム開発案件が中心ですが、インフラ系・デザイン系・事務系など幅広くご紹介することが可能です。</br>
                                非公開案件もございますので、まずはご相談下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">契約形態について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>業務請負契約(個人)、契約社員、派遣契約、法人契約のいずれかになります。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">支払いサイトについて</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                案件によって異なりますが、月末締めの翌月末（30日）お支払いから月末締めの翌々月末（60日）お支払いになります。</br>
                                その他の支払希望ございましたら弊社担当者へご相談下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">掲載単価と受取金額について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                スキルや経験によって異なりますが、弊社からお渡しする想定金額です。</br>
                                ただし、必ずしも表示されている金額とならない場合がございますので、予めご了承ください。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">就業後のフォローについて</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                就業後も、弊社担当者が随時フォローいたします。</br>
                                何かありましたらお気軽にご相談下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">プロジェクト終了後について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                案件終了の1か月前(現プロジェクト期間中)から次の案件などのご相談を伺います。</br>
                                期間を空けたくない、少しお休みしたいなどのご希望に沿って再度お探し致します。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">応募について</h2>

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">応募までの手順</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>「ご利用の流れ」（リンク）をご参照ください。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">応募してから結果が出るまでの期間について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                カウンセリング済みの場合、案件によっても異なりますが、早いもので1日で就業が決まることもございます。</br>
                                平均すると登録～就業まで10日前後です。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">応募取り消し方法について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>弊社担当者までご連絡をお願い致します。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">在職中でも応募について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>在職中の応募も可能です。応募の際、就業開始可能日をお教え下さい。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">掲載案件の応募期間について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                いつでも応募可能です。</br>
                                ただし掲載日が1ヶ月以上前の案件は既に充足している場合はございます。</br>
                                案件は日々掲載しておりますので、ご興味のある案件がございましたら是非ご応募下さい。</br>
                                また、非公開案件も多数ございますので、弊社担当者にお気軽にお問い合わせ下さい。</br>
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">応募資格をすべて満たしていない場合について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                案件によっては多少のスキル不足があっても、周りの方のサポートを受けながら参画できる案件もあります。</br>
                                少しでも気になる案件を見つけたら積極的に問い合わせされることをお勧め致します。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">未経験の言語や業界・業種への就業について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>就業は可能ですが、条件面がご希望に満たない可能性もございますので、その際はご相談させて頂きます。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">年齢制限について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>案件により年齢制限がある場合もございますが、様々な案件がございますので、まずは一度カウンセリングをさせて頂ければと思います。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">面接について</h2>

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">カウンセリング（事前面談）の時間調整・出張面談について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                平日でしたらできる限りご希望の時間に合わせて調整をさせて頂きます。</br>
                                また、状況によっては出張面談も致します。詳細はお気軽にご相談下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">面接時の服装について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                カウンセリングについては私服でも問題ありません。</br>
                                お客様面談についてはスーツが基本となりますが、都度お伝えさせて頂きます。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li class="qaMark questionMark">Q</li>
                        <li class="questionText"><span class="clickText">面接の内容について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                カウンセリング時に今までのご経歴・業務概要・得意分野・希望事項などを伺います。</br>
                                アピールポイント・希望条件がございましたら是非お聞かせください。</br>
                                </br>
                                お客様面談については、面談が入った際は、事前に弊社担当者から想定される質問などをお伝えいたします。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">来社について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                よりマッチした案件をご紹介するため、可能なかぎりご来社いただき、お話させて頂いております。</br>
                                また、サイト上では公開できない非公開案件などの情報もございますので、是非一度ご来社下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li class="qaMark questionMark">Q</li>
                        <li class="questionText"><span class="clickText">職務経歴書のフォーマットについて</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>フォーマットはご用意しておりますが、お手持ちの職務経歴書がある場合はそちらでも構いません。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">職務経歴書の書き方について</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>ご不明点がございましたら「お問い合わせ」フォーム、又は弊社までお気軽にご相談下さい。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">職務経歴書の内容を更新したい場合</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>再度アップロード頂くか、弊社担当者へご連絡をお願い致します。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <h2 id="aboutFreeEngineer" class="subTitle">フリーエンジニアについて</h2>

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">フリーエンジニアになるためには</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                フリーエンジニアになるために必要な経験は特にございません。</br>
                                正社員として2～3年の経験を経た後、独立される方も多くいらっしゃいます。</br>
                                独立する際に相談に来られる方もいらっしゃいますので、お気軽にご相談下さい。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">フリーの年金・保険・確定申告</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>
                                まず申告書の入手から行いましょう。</br>
                                申告書は最寄の税務署や国税庁のHPより入手することが出来ます。</br>
                                不明点があれば税務署に問い合わせされることをお勧め致します。</br>
                                また、国税庁のHPに電子納付(e-TAX)や確定申告の作成コーナーなどもあります。</br>
                                ※弊社契約社員としてご就業頂く場合は、厚生年金基金・健康保険・雇用保険にご加入頂きます。
                            </p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
                </div>
                <hr class="partitionLine">

                <h2 class="subTitle">その他</h2>

                <div class="question">
                    <ul class="questionElement">
                        <li><p class="qaMark questionMark">Q</p></li>
                        <li class="questionText"><span class="clickText">Q&amp;Aで解決できない場合</span></li>
                        <li class="questionOpen">+</li>
                    </ul>
                    <ul class="anserElement">
                        <li><p class="qaMark anserMark">A</p></li>
                        <li class="anserText">
                            <p>「お問い合わせ」フォーム、又は弊社までお気軽にご相談下さい。</p>
                        </li>
                        <li class="questionOpen"></li>
                    </ul>
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
@endsection
