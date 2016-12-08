@extends('front.common.layout')
@if(isset($html_title))
    @section('title', $html_title.' - エンジニアルート')
@else
    @section('title', '案件一覧 - エンジニアルート')
@endif
@section('canonical', Request::url())

@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
    use App\Libraries\ModelUtility as mdlUtil;
    use Carbon\Carbon;
    use App\Models\Ms_skill_categories;
    use App\Models\Ms_sys_types;
    use App\Models\Ms_biz_categories;
    use App\Models\Ms_areas;
    use App\Models\Ms_job_types;
?>
<div class="wrap">
    <div id="item" class="content">
        <div class="content-left">
            <div class="titleArea">
                <h1 class="title">該当案件一覧</h1>
                <span class="itemCount">該当件数：<span class="num">{{ $itemList->total() }}</span>件</span>
            </div>
            <hr class="hr-2px-solid-5e8796">

@if(strstr(Request::url(), '/item/search'))
            <div class="sp_condition_search_btn invisible-pc invisible-tab">
                <div class="commonCenterBtn">
                    <button><p>条件を指定して検索する<p></button>
                </div>
            </div>
@endif

            <div class="conditions">
@if(strstr(Request::url(), '/item/search'))
                <div class="search">
                    <div class="tabSelected">
                        <p class="title">選択した項目
                            <span class="openTab">＋<span class="openTabText">検索条件を変更する</span></span>
                        </p>
                        <div class="tabSelectedInr">
                            <div class="searchElement">
                                <div id="tagSelectedSkill">
                                    <p class="tagSelectedName">スキル</p>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                                <div id="tagSelectedSysType">
                                    <p class="tagSelectedName">システム種別</a>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                                <div id="tagSelectedRate">
                                    <p class="tagSelectedName">報酬</a>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                                <div id="tagSelectedBizCategory">
                                    <p class="tagSelectedName">業種</a>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                                <div id="tagSelectedArea">
                                    <p class="tagSelectedName">勤務地</a>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                                <div id="tagSelectedPosition">
                                    <p class="tagSelectedName">ポジション</a>
                                    <ul></ul>
                                    <hr class="partitionLine">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab">
                        <div class="tabMenu">
                            <ul>
                                <li>スキル</li>
                                <li>システム種別</li>
                                <li>報　酬</li>
                                <li>業　種</li>
                                <li>勤務地</li>
                                <li>ポジション</li>
                            </ul>
                        </div><!-- /.tabMenu -->

                        <form id="tabForm" method="GET" action="{{ url('/item/search') }}">
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">10個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
@foreach(Ms_skill_categories::getNotIndexOnly() as $skill_category)
@if(!$skill_category->skills->isEmpty())
                                <div class="tabContent">
                                    <h3>{{ $skill_category->name }}</h3>
                                    <ul>
@foreach($skill_category->skills as $skill)
@if($skill->master_type != mdlUtil::MASTER_TYPE_INDEX_ONLY)
                                        <li class="tabContentElementOneThird">
                                            <label><input class="srchCndtns_chkBx" type="checkbox" name="skills[]" value="{{ $skill->id }}">{{ $skill->name }}</label>
                                        </li>
@endif
@endforeach
                                    </ul>
                                </div>
@endif
@endforeach
                            </div>
                        </div>
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                <div class="tabContent">
                                    <ul>
@foreach(Ms_sys_types::getNotIndexOnly() as $sys_type)
                                        <li class="tabContentElementHalf">
                                            <label><input class="srchCndtns_chkBx" type="checkbox" name="sys_types[]" value="{{ $sys_type->id }}">{{ $sys_type->name }}</label>
                                        </li>
@endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">1個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                <div class="tabContent">
                                    <ul>
@foreach(FrntUtil::SEARCH_CONDITION_RATE as $key => $value)
                                        <li class="tabContentElementHalf">
                                            <label><input type="radio" class="srchCndtns_radio" name="search_rate" value="{{ $key }}">{{ $value }}</label>
                                        </li>
@endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                <div class="tabContent">
                                    <ul>
@foreach(Ms_biz_categories::getNotIndexOnly() as $biz_category)
                                        <li class="tabContentElementHalf">
                                            <label><input class="srchCndtns_chkBx" type="checkbox" name="biz_categories[]" value="{{ $biz_category->id }}">{{ $biz_category->name }}</label>
                                        </li>
@endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                <div class="tabContent">
                                    <ul>
@foreach(Ms_areas::getNotIndexOnly() as $area)
                                        <li class="tabContentElementHalf">
                                            <label><input class="srchCndtns_chkBx" type="checkbox" name="areas[]" value="{{ $area->id }}">{{ $area->name }}</label>
                                        </li>
@endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                <div class="tabContent">
                                    <ul>
@foreach(Ms_job_types::getNotIndexOnly() as $job_type)
                                        <li class="tabContentElementHalf">
                                            <label><input class="srchCndtns_chkBx" type="checkbox" name="job_types[]" value="{{ $job_type->id }}">{{ $job_type->name }}</label>
                                        </li>
@endforeach
                                    </ul>
                                </div>
                            </div>
                        </div><!-- /.tabBox -->
                    </div>
                    <div class="centerBtn clear">
                        <button type="submit">検　索</button>
                    </div>
                </form>
                </div>
@endif

                <div class="sort">
                    <label>
                        <span class="selectBox">
                            <select id="order" class="">
                                <option value="RegistrationDesc" {{ $params['order'] == 'RegistrationDesc' ? "selected" : ""}}>新着順</option>
                                <option value="ServiceAsc" {{ $params['order'] == 'ServiceAsc' ? "selected" : ""}}>受付終了日が近い順</option>
                                <option value="RateDesc" {{ $params['order'] == 'RateDesc' ? "selected" : ""}}>報酬が高い順</option>
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

            <div id="itemList">
@foreach($itemList as $item)
            <div class="item">
                <div class="itemHeader">
                    <div class="table-row">
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                        <p class="new">新着</p>
@endif
                        <p class="name">{{ $item->name }}</p>
                        <p class="sys_type">{{ $item->bizCategorie->name }}</p>
                    </div>
                </div>

                <div class="itemInfo clear">
                    <div class="itemInfoInr">
                        <div class="pickUp">
                            <div class="pickUpRate">
                                <div class="rate"><p>報　酬</p></div>
                                <div class="rate_detail"><p>{{ $item->rate_detail }}</p></div>
                            </div>
                            <div class="pickUpArea">
                                <div class="area"><p>エリア</p></div>
                                <div class="area_detail"><p>{{ $item->area_detail }}</p></div>
                            </div>
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
                        <a href="/item/detail?id={{ $item->id }}"  target="_blank"><button><p>詳細を見る<p></button></a>
                        </div>
                    </div>
                </div>
            </div>
@endforeach
            </div>

            <div class="paginate invisible-sp">
                {!! (new App\Libraries\Pagination\CustomBootstrapPresenter($itemList->appends($params)))->render() !!}
            </div>

@if($itemList->hasMorePages())
            <div class="commonCenterBtn read_more_btn invisible-tab invisible-pc">
                <button id="sp_morePage"><p>もっと見る<p></button>
            </div>
@endif

            <section class="keyWordSearch clear invisible-sp">
                <h1>キーワードから案件を探す</h1>
                <form class="keyWordSearchForm" method="get" action="/item/keyword">
                    <input class="keyWordSearchInputForm" type="text" name="keyword">
                    <button class="keyWordSearchSearchBtn" type="submit">検　索</button>
                </form>
            </section><!-- /.keyWordSearch for pc,tablet -->

        </div><!-- END CONTENT-LEFT -->

        <div class="content-right invisible-sp">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>
    </div><!-- END CONTENT -->

    <div id="pucat">
        @include('front.common.feature')
    </div>

    <div class="content">
        <div class="content-right invisible-pc invisible-tab">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->

<script type="text/javascript">
    jQuery(function($){
        $(".openTab").parents('.search').find('.tab').hide();
        $(".openTab").click(function(){
            var tabBox = $(this).parents('.search').find('.tab');
		    tabBox.slideToggle(700, function(){
                if (tabBox.is(':visible')) {
                    $(".openTab").text('-').append($('<span class="openTabText">タブを閉じる</span>'));
                } else {
                    $(".openTab").text('+').append($('<span class="openTabText">検索条件を変更する</span>'));
                };
			});
		});
	});
</script>
@endsection
