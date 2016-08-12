@extends('front.common.layout')
@section('content')
<?php use App\Libraries\ModelUtility as mdlUtil; ?>
    <div class="content" id="top">
        <div id="slider">
            <ul class="slider slider-item">
                <li><a href="#"><img src="/front/images/bnr001.jpg"></a></li>
                <li><a href="#"><img src="/front/images/bnr002.jpg"></a></li>
                <li><a href="#"><img src="/front/images/bnr001.jpg"></a></li>
                <li><a href="#"><img src="/front/images/bnr002.jpg"></a></li>
            </ul>
        </div><!-- ./slider -->

        <section class="keyWordSearch clear invisible-pc invisible-tab">
            <div class="alignCenter">
                <div class="keyWordSearchForm">
                    <form action="/front/keyword" method="get">
                        <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                        <input type="submit" class="searchBtn">
                    </form>
                </div>
            </div>
            <div class="sideInfoInr invisible-pc invisible-tab ">
                <div class="alignCenter">
                    <p>新規会員登録<span class="alignright invisible-pc invisible-tab">></span></p>
                </div>
            </div>
        </section><!-- /.keyWordSearch for sp -->

            <div class="topItemList clear">
                <section class="newJob">
                    <div class="topJobWrap">
                        <h1 class="alignleft">新着案件</h1>
                        <p class="alignright invisible-sp"><a href="/front/search?order=RegistrationDesc">新着案件一覧へ</a></p>
                        <ul class="fs0 clear">
@foreach($newItemList as $newItem)
                            <li>
                                <a href="/front/detail?id={{ $newItem->id }}">
                                    <div class="topJobInr">
                                        <img src="/front/images/ico-newjob.png">
                                        <h2>{{ $newItem->name }}</h2>
                                        <p class="location">{{ $newItem->area_detail }}</p>
                                        <p class="remuneration">{{ $newItem->rate_detail }}</p>
                                        <p class="update">{{ $newItem->service_start_date->format('Y/m/d') }}</p>
                                    </div>
                                </a>
                            </li>
@endforeach
                        </ul>
                        <div class="newItemListLink clear invisible-pc invisible-tab">
                            <a href="/front/search?order=RegistrationDesc">新着案件一覧へ</a>
                        </div>
                    </div>
                </section><!-- /.newJob -->

                <section class="attentionJob">
                    <div class="topJobWrap">
                        <h1 class="alignleft">急募案件</h1>
                        <p class="alignright invisible-sp"><a href="/front/tag/1?order=RegistrationDesc">急募案件一覧へ</a></p>
                        <ul class="fs0 clear">
@foreach($pickUpItemList as $pickUpItem)
                            <li>
                                <a href="/front/detail?id={{ $pickUpItem->id }}">
                                    <div class="topJobInr">
                                        <img src="/front/images/ico-attentionJob.png">
                                        <h2>{{ $pickUpItem->name }}</h2>
                                        <p class="location">{{ $pickUpItem->area_detail }}</p>
                                        <p class="remuneration">{{ $pickUpItem->rate_detail }}</p>
                                        <p class="update">{{ $pickUpItem->service_start_date->format('Y/m/d') }}</p>
                                    </div>
                                </a>
                            </li>
@endforeach
                        </ul>
                        <div class="wantedItemListLink clear invisible-pc invisible-tab">
                            <a href="/front/tag/1?order=RegistrationDesc">急募案件一覧へ</a>
                        </div>
                    </div>

                </section><!-- /.attentionJob -->
            </div><!-- /.topItemList -->

            @include('front.common.feature')

            <section class="conditions invisible-sp">
                <div class="contentInr">
                    <h1>条件から案件を探す</h1>
                    <div class="tabWrap alignleft">
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

                        <form name="form" role="form" method="GET" action="{{ url('/front/search') }}">

                            <div class="tabBox">
                                <div class="tabBoxInr">
                                    <p class="attention">10個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
@foreach($skill_categories as $skill_category)
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

                                <div class="tabBoxInr">
                                    <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                    <div class="tabContent">
                                        <ul>
@foreach($sys_types as $sys_type)
                                            <li class="tabContentElementHalf">
                                                <label><input class="srchCndtns_chkBx" type="checkbox" name="sys_types[]" value="{{ $sys_type->id }}">{{ $sys_type->name }}</label>
                                            </li>
@endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="tabBoxInr">
                                    <p class="attention">1個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                    <div class="tabContent">
                                        <ul>
@foreach($seach_rateList as $key => $value)
                                            <li class="tabContentElementHalf">
                                                <label><input type="radio" class="srchCndtns_radio" name="search_rate" value="{{ $key }}">{{ $value }}</label>
                                            </li>
@endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="tabBoxInr">
                                    <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                    <div class="tabContent">
                                        <ul>
@foreach($biz_categories as $biz_category)
                                            <li class="tabContentElementHalf">
                                                <label><input class="srchCndtns_chkBx" type="checkbox" name="biz_categories[]" value="{{ $biz_category->id }}">{{ $biz_category->name }}</label>
                                            </li>
@endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="tabBoxInr">
                                    <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                    <div class="tabContent">
                                        <ul>
@foreach($areas as $area)
                                            <li class="tabContentElementHalf">
                                                <label><input class="srchCndtns_chkBx" type="checkbox" name="areas[]" value="{{ $area->id }}">{{ $area->name }}</label>
                                            </li>
@endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="tabBoxInr">
                                    <p class="attention">5個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
                                    <div class="tabContent">
                                        <ul>
@foreach($job_types as $job_type)
                                            <li class="tabContentElementHalf">
                                                <label><input class="srchCndtns_chkBx" type="checkbox" name="job_types[]" value="{{ $job_type->id }}">{{ $job_type->name }}</label>
                                            </li>
@endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- /.tabBox -->

                            <div class="tabSelected">
                                <div class="tabSelectedInr">
                                    <p class="attention">選択した項目</p>
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

                            <div class="centerBtn clear">
                                <button type="submit">検　索</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section><!-- /.conditions -->

            <div class="contentInr">
                <section class="categorySearch invisible-sp">
                    <div class="categorySearchInr">
                        <h1>カテゴリーから案件を探す</h1>
                        <div class="categorySearchContent">
@foreach($parentList as $parentKey => $parent)
                            <div class="parentCategory">&nbsp;<a href="/front/category/{{ $parentKey }}">{{ $parent }}</div>
                                <div class="childCategory">
@foreach($childList[$parentKey] as $child)
                                    <a href="/front/category/{{ $child->id }}">{{ $child->name }}</a>
@if(next($childList[$parentKey]) !== FALSE)
                                    <span>|</span>
@endif
@endforeach
                                </div>
                                <hr class="categoryPartitionLine">
@endforeach
                        </div>
                    </div>
                </section><!-- /.categorySearch for pc,tablet-->

                <section class="categorySearch invisible-pc invisible-tab">
                    <div class="categorySearchInr">
                        <h1>カテゴリーから案件を探す</h1>
                        <div class="categorySearchContent">
                            <ul>
@foreach($parentList as $parentKey => $parent)
                                <li>
                                    <ul>
                                        <a href="/front/category/{{ $parentKey }}">
                                            <li class="parentCategory">{{ $parent }}<span class="alignright">+</span></li>
                                        </a>
@foreach($childList[$parentKey] as $child)
                                        <a href="/front/category/{{ $child->id }}">
                                            <li class="childCategory">{{ $child->name }}<span class="alignright">></span></li>
                                        </a>
@endforeach
                                    </ul>
                                </li>
@endforeach
                            </ul>
                        </div>
                    </div>
                </section><!-- /.categorySearch for sp-->

                <section class="keyWordSearch clear invisible-pc invisible-tab">
                    <div class="alignCenter">
                        <div class="keyWordSearchForm">
                            <form action="front" method="get">
                                <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                                <input type="submit" class="searchBtn">
                            </form>
                        </div>
                    </div>
                </section><!-- /.keyWordSearch for sp -->

                <div class="sideInfoInr invisible-pc invisible-tab ">
                    <div class="alignCenter">
                        <p>新規会員登録<span class="alignright invisible-pc invisible-tab">></span></p>
                    </div>
                </div>

                <div class="content-right">
                    @include('front.common.sideInfo')
                </div>

            </div><!-- category & sideInfo -->

            <section class="keyWordSearch clear invisible-sp">
                <div class="contentInr">
                    <h1>キーワードから案件を探す</h1>
                    <div class="">
                        <form class="keyWordSearchForm" method="get" action="/front/keyword">
                            <input class="keyWordSearchInputForm" type="text" name="keyword">
                            <button class="keyWordSearchSearchBtn" type="submit">検　索</button>
                        </form>
                    </div>
                </div>
            </section><!-- /.keyWordSearch for pc,tablet -->

        </div>
    </div>
</div>
@endsection
