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
            <div class="titleArea">
                <h1 class="title">該当案件一覧</h1>
                <span class="itemCount">該当件数：<span class="num">{{ $itemList->total() }}</span>件</span>
            </div>
            <hr class="partitionLine_02">

            <div class="conditions">
                <div class="search"></div>
                <div class="sort">
                    <label>
                        <span class="selectBox">
                            <select id="order" class="">
                                <option value="RegistrationDesc" {{ $params['order'] === 'RegistrationDesc' ? "selected" : ""}}>新着順</option>
                                <option value="ServiceAsc" {{ $params['order'] === 'ServiceAsc' ? "selected" : ""}}>受付終了日が近い順</option>
                                <option value="RateDesc" {{ $params['order'] === 'RateDesc' ? "selected" : ""}}>報酬が高い順</option>
                            </select>
                        </span>
                        <span class="selectBox">
                            <select id="limit" class="">
                                <option value="1" {{ $params['limit'] == 1 ? "selected" : ""}}>10</option>
                                <option value="2" {{ $params['limit'] == 2 ? "selected" : ""}}>20</option>
                                <option value="3" {{ $params['limit'] == 3 ? "selected" : ""}}>50</option>
                            </select>
                        </span>
                        件表示
                    </label>
                </div>
            </div>

@foreach($itemList as $item)
            <div class="item">
                <div class="itemHeader">
                    <div class="table-row">
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                        <p class="new">新着</p>
@endif
                        <p class="name">{{ $item->name }}<span class="sys_type">{{ $item->bizCategorie->name }}</span></p>
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
                        <p class="detail">{{ $item->detail }}</p>
                    <div class="commonCenterBtn">
                        <a href="/front/detail?id={{ $item->id }}"><button><p>詳細を見る<p></button></a>
                        </div>
                    </div>
                </div>
            </div>
@endforeach

            <div class="paginate">
                {!! $itemList->appends(['page' => $params['page'],
                                        'order' => $params['order'],
                                        'limit' => $params['limit']])->links() !!}
            </div>

        </div><!-- END CONTENT-LEFT -->
        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>

        <section class="pickupCat">
            <h1 class="SearchTitle">特集から案件を探す</h1>
            <ul class="fs0">
                <li class="pucat01"><a href="#">残業少なめ</a></li>
                <li class="pucat02"><a href="#">年齢不問</a></li>
                <li class="pucat03"><a href="#">高単価</a></li>
                <li class="pucat04"><a href="#">ロースキル</a></li>
                <li class="pucat05"><a href="#">現場直</a></li>
                <li class="pucat06"><a href="#">女性が活躍</a></li>
            </ul>
        </section><!-- /.pickupCat -->

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
