@extends('front.common.layout')
@section('title', '条件を指定して案件を探す - エンジニアルート')
@section('content')
<?php
use App\Libraries\HtmlUtility as HtmlUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Ms_skill_categories;
use App\Models\Ms_sys_types;
use App\Models\Ms_biz_categories;
use App\Models\Ms_areas;
use App\Models\Ms_job_types;
?>
<div class="wrap">
    <section id="sp_condition" class="condition_search">
        <form method="get" action="{{ url('/item/search') }}">
        <div class="searchHeader">
            <h1>条件を指定して案件を探す</h1>
        </div>
        <div class="searchContent">
            <ul class="fs0">

                <div class="condition_content">
                    <li class="condition_name">スキル<span>+</span></li>

<div class="condition_slideArea">
@foreach(Ms_skill_categories::getNotIndexOnly() as $skill_category)
@if(!$skill_category->skills->isEmpty())
                    <li class="condition_elmnt_name">{{$skill_category->name}}</li>
                    <div class="condition_elmnt_value">
@foreach($skill_category->skills as $skill)
@if($skill->master_type != mdlUtil::MASTER_TYPE_INDEX_ONLY)
                        <li class="condition_elmnt">
                            <label><input class="srchCndtns_chkBx" type="checkbox" name="skills[]" value="{{ $skill->id }}">{{ $skill->name }}</label>
                        </li>
@endif
@endforeach
                    </div>
@endif
@endforeach
</div>
                </div>

                <div class="condition_content">
                    <li class="condition_name">システム種別<span>+</span></li>
<div class="condition_slideArea">
@foreach(Ms_sys_types::getNotIndexOnly() as $sys_type)
                    <li class="condition_elmnt_value">
                        <label><input class="srchCndtns_chkBx" type="checkbox" name="sys_types[]" value="{{ $sys_type->id }}">{{ $sys_type->name }}</label>
                    </li>
@endforeach
</div>
                </div>

                <div class="condition_content">
                    <li class="condition_name">報酬<span>+</span></li>
<div class="condition_slideArea">
@foreach(FrntUtil::SEARCH_CONDITION_RATE as $key => $value)
                        <li class="condition_elmnt">
                            <label><input type="radio" class="srchCndtns_radio" name="search_rate" value="{{ $key }}">{{ $value }}</label>
                        </li>
@endforeach
</div>
                </div>

                <div class="condition_content">
                    <li class="condition_name">業種<span>+</span></li>
<div class="condition_slideArea">
@foreach(Ms_biz_categories::getNotIndexOnly() as $biz_category)
                    <li class="condition_elmnt_value">
                        <label><input class="srchCndtns_chkBx" type="checkbox" name="biz_categories[]" value="{{ $biz_category->id }}">{{ $biz_category->name }}</label>
                    </li>
@endforeach
</div>
                </div>

                <div class="condition_content">
                    <li class="condition_name">勤務地<span>+</span></li>
<div class="condition_slideArea">
@foreach(Ms_areas::getNotIndexOnly() as $area)
                    <li class="condition_elmnt_value">
                        <label><input class="srchCndtns_chkBx" type="checkbox" name="areas[]" value="{{ $area->id }}">{{ $area->name }}</label>
                    </li>
@endforeach
</div>
                </div>

                <div class="condition_content">
                    <li class="condition_name">ポジション<span>+</span></li>
<div class="condition_slideArea">
@foreach(Ms_job_types::getNotIndexOnly() as $job_type)
                    <li class="condition_elmnt_value">
                        <label><input class="srchCndtns_chkBx" type="checkbox" name="job_types[]" value="{{ $job_type->id }}">{{ $job_type->name }}</label>
                    </li>
@endforeach
</div>
                </div>
            </ul>
        </div>
        <div class="tabSelected">
            <p class="title">選択した項目</p>
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
        <div class="clear commonCenterBtn">
            <button type="submit">検　索</button>
        </div>
    </form>
    </section>
</div><!-- END WRAP -->
@endsection
