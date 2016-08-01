@extends('front.common.layout')
@section('title', '案件一覧 - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use Carbon\Carbon;
?>
<link rel="stylesheet" href="{{ url('/front/css/simplePagination.css') }}">
<script type="text/javascript" charset="utf-8" src="{{ url('/front/js/jquery.simplePagination.js') }}"></script>
<div class="wrap">
    <div id="itemList" class="content">
        <div class="content-left">
            <h1 class="title">該当案件一覧<span class="itemCount">該当件数：<span class="num">{{ $itemList->total() }}</span>件</span></h1>
            <hr class="partitionLine_02">

            <div class="conditions">
                <div class="search"></div>
                <span>
                    <select>
                        <option value="RegistrationDesc">新着順</option>
                        <option value="ServiceAsc">受付終了日が近い順</option>
                        <option value="RateDesc">報酬が高い順</option>
                    </select>
                </span>
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
                        <a href="/front/search?id={{ $item->id }}"><button><p>詳細を見る<p></button></a>
                        </div>
                    </div>
                </div>
            </div>
@endforeach

            <div class="paginate">
                <ul class="page">
                    {!! HtmlUtil::paginate($itemList) !!}
                </ul>
            </div>

        </div><!-- END CONTENT-LEFT -->
        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
