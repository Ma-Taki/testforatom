@extends('front.common.layout')
@section('title', '案件一覧 - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
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
            <hr class="partitionLine_02">

            <div class="conditions">
                <div class="search">
                    <div class="tabSelected">
                        <p class="title">選択した項目<span class="openTab">＋　検索条件を変更する</span></p>
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

                        <div class="tabBox">
                            <div class="tabBoxInr">
                                <p class="attention">10個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
@foreach(Ms_skill_categories::getNotIndexOnly() as $skill_category)
@if(!$skill_category->skills->isEmpty())
                                <div class="tabContent">
                                    <h3>{{ $skill_category->name }}</h3>
                                    <ul>
@foreach($skill_category->skills as $skill)
@if($skill->master_type !== mdlUtil::MASTER_TYPE_INDEX_ONLY)
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
                </div>
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
                {!! $itemList->appends($params)->links() !!}
            </div>

        </div><!-- END CONTENT-LEFT -->
        <div class="content-right">
            @include('front.common.sideInfo')
        </div><!-- END CONTENT-RIGHT -->
        <div class="clear"></div>

        @include('front.common.feature')

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
