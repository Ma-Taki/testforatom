@extends('front.common.layout')
@section('title', 'エンジニアルートとは - エンジニアルート')
@section('description', 'Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、 それぞれの方に合った案件検索から、カウンセリング、プロジェクトの終了まで 全てのプロセスを徹底的にサポート致します。')
@section('canonical', url('/about'))

@section('content')
<div class="wrap">
    <div class="main-content about">
        <div class="main-content-left">
            <h1 class="main-content__title">エンジニアルートとは</h1>
            <hr class="hr-2px-solid-5e8796">
            <div class="main-content__body">
                <div class="content__element">
                    <div class="about__image">
                        <img src="/front/images/sky.jpg" alt="エンジニアルートとは">
                        <p class="about__image-text wsnw">
                            エンジニアルートは、プロジェクト終了後まで<br />
                            エンジニアの皆様を全力でサポート致します。
                        </p>
                    </div>
                    <div class="about__text">
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
            </div>
        </div><!-- END main-content-left -->

        <div class="main-content-right">
            @include('front.common.sideInfo')
        </div><!-- END main-content-right -->
        <div class="clear"></div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
