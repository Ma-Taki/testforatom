@extends('front.common.layout')
@section('title', '案件一覧 - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use Carbon\Carbon;
?>
<div class="wrap">
    <div id="item" class="content">
        <div class="content-left">
            <h1 class="title">案件詳細</h1>
            <hr class="partitionLine_02">
            <div id="itemDetail">
            <div class="item">
                <div class="itemHeader">
                    <div class="table-row">
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                        <p class="new">新着</p>
@endif
                        <p class="name">{{ $item->name }}<span class="sys_type">案件ID：AN{{ $item->id }}</span></p>
                    </div>
                </div>
                <div class="itemInfo clear">
                    <div class="itemInfoInr">
                        <div class="pickUp">
                            <p class="rate">報　酬</p>
                            <p class="rate_detail">{{ $item->rate_detail }}</p>
                            <p class="pckUpWhtSpce"></p>
                            <p class="area">エリア</p>
                            <p class="area_detail">{{ $item->area_detail }}</p>
                        </div>
                        <div class="other">
                            <p class="otherName">業種</p>
                            <p class="otherValue">
                                {{ $item->bizCategorie->name }}
                            </p>
                        </div>
                        <div class="other">
                            <p class="otherName">システム種別</p>
                            <p class="otherValue">
                                @foreach($item->sysTypes as $sys_type)
                                {{ $sys_type->name }}<span class="wordPunctuation">、</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="other">
                            <p class="otherName">ポジション</p>
                            <p class="otherValue">
                                @foreach($item->jobTypes as $jobType)
                                {{ $jobType->name }}<span class="wordPunctuation">、</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="other">
                            <p class="otherName">就業期間</p>
                            <p class="otherValue">
                                {{ $item->employment_period }}
                            </p>
                        </div>
                        <div class="other">
                            <p class="otherName">就業時間</p>
                            <p class="otherValue">
                                {{ $item->working_hours }}
                            </p>
                        </div>
                        <div class="other">
                            <p class="otherName">詳細</p>
                            <p class="pre-wrap otherValue">{{ $item->detail }}</p>
                        </div>
                        <div class="other">
                            <p class="otherName">エントリー受付期間</p>
                            <p class="otherValue">
                                {{ $item->service_start_date->format('Y年n月j日').' 〜 '.$item->service_end_date->format('Y年n月j日') }}
                            </p>
                        </div>
                        <div class="itemTagList">
@foreach($item->tags as $tag)<a href="/front/tag/{{ $tag->id }}"><p class="tag">{{ $tag->term }}</p></a>@endforeach
                        </div>
                        <div class="commonCenterBtn">
                            <a href="#"><button>この案件にエントリーする</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="keyWordSearch clear invisible-sp">
            <h1>キーワードから案件を探す</h1>
            <form class="keyWordSearchForm" method="post" action="/item/search">
                <input class="keyWordSearchInputForm" type="text" name="search_keyWord">
                <button class="keyWordSearchSearchBtn" type="submit">検　索</button>
            </form>
        </section><!-- /.keyWordSearch for pc,tablet -->

        </div><!-- END CONTENT-LEFT -->
        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->

        <div class="clear "></div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
<section class="recommended bg">
    <div class="recommendedInr">
        <h3>こちらもおすすめ</h3>
        <div class="recoItems">
@foreach($recoItemList as $item)
            <div class="recoItem">
                <p class="header">{{ $item->name }}</p>
                <div class="detail">
                    <div class="table">
                        <p class="name">報酬</p>
                        <p class="rate">{{ $item->rate_detail }}</p>
                    </div>
                    <div class="table">
                        <p class="name">エリア</p>
                        <p class="area">{{ $item->area_detail }}</p>
                    </div>
                    <p class="skill">要求スキル</p>
                    <p>{{ HtmlUtil::convertSkillsMdlToNameStr($item->skills) }}</p>
                </div>
            </div>
@endforeach
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $(".recoItem .header").tile();
        });
    </script>
</section>
<div class="wrap">
    <div class="content">
        @include('front.common.feature')
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
