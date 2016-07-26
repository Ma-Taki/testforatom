@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div class="content">
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
        </div><!-- END CONTENT-LEFT -->

        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
