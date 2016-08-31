@extends('front.common.layout')
@section('content')
<?php
use App\Libraries\ModelUtility as mdlUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Models\Ms_skill_categories;
use App\Models\Ms_sys_types;
use App\Models\Ms_biz_categories;
use App\Models\Ms_areas;
use App\Models\Ms_job_types;
use App\Models\Tr_search_categories;
?>
    <div class="content" id="top">
        <div id="slider">
            <ul class="slider slider-item">
                <li><a href="/about"><img src="/front/images/topBnr001.jpg"></a></li>
                <li><a href="/item/category/1"><img src="/front/images/topBnr002.jpg"></a></li>
                <li><a href="/item/search?job_types%5B%5D=12"><img src="/front/images/topBnr003.jpg"></a></li>
                <li><a href="/item/tag/32"><img src="/front/images/topBnr004.jpg"></a></li>
                <li><a href="/item/tag/2"><img src="/front/images/topBnr005.jpg"></a></li>
                <li><a href="/item/search?job_types%5B%5D=1"><img src="/front/images/topBnr006.jpg"></a></li>
            </ul>
        </div><!-- ./slider -->

        <section class="keyWordSearch clear invisible-pc invisible-tab">
            <div class="alignCenter">
                <div class="keyWordSearchForm">
                    <form action="/item/keyword" method="get">
                        <input type="text" name="keyword" placeholder="キーワード検索" size="40" maxlength="255" class="searchBox">
                        <input type="submit" class="searchBtn">
                    </form>
                </div>
            </div>
        </section><!-- /.keyWordSearch for sp -->

@if(!FrntUtil::isLogin())
        <div class="user_regist_btn invisible-pc invisible-tab ">
            <img src="/front/images/bnrTourokuSP.png" alt="新規会員登録" />
        </div>
@else
        <section class="hello_user clear">
            <div class="contentInr">
                <p>こんにちは、<a href="/user">{{ FrntUtil::getLoginUserName() }}さん</a></p>
            </div>
        </section>
@endif

            <div class="topItemList clear">
                <section class="newJob">
                    <div class="topJobWrap">
                        <h1 class="alignleft">新着案件</h1>
                        <p class="alignright invisible-sp"><a href="/front/search?order=RegistrationDesc">新着案件一覧へ</a></p>
                        <ul class="fs0 clear">
@foreach($newItemList as $newItem)
                            <li>
                                <a href="/item/detail?id={{ $newItem->id }}">
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
                            <a href="/item/search">新着案件一覧へ</a>
                        </div>
                    </div>
                </section><!-- /.newJob -->

                <section class="attentionJob">
                    <div class="topJobWrap">
                        <h1 class="alignleft">急募案件</h1>
                        <p class="alignright invisible-sp"><a href="/item/tag/1">急募案件一覧へ</a></p>
                        <ul class="fs0 clear">
@foreach($pickUpItemList as $pickUpItem)
                            <li>
                                <a href="/item/detail?id={{ $pickUpItem->id }}">
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
                            <a href="/item/tag/1">急募案件一覧へ</a>
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

                        <form name="form" role="form" method="GET" action="{{ url('/item/search') }}">

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
@foreach(Tr_search_categories::getParentCategories() as $parent)
                            <div class="parentCategory">&nbsp;<a href="/item/category/{{ $parent->id }}">{{ $parent->name }}</div>
                                <div class="childCategory">
@foreach(Tr_search_categories::getChildByParent($parent->id) as $child)
                                    <a href="/item/category/{{ $child->id }}">{{ $child->name }}</a>
                                    <span>|</span>
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
@foreach(Tr_search_categories::getParentCategories() as $parent)
                                <li>
                                    <ul>
                                        <li class="parentCategory">{{ $parent->name }}<span class="alignright">+</span></li>
                                        <div class="childCategories">
                                            <a href="/item/category/{{ $parent->id }}">
                                                <li class="childCategory">{{ $parent->name }}一覧<span class="alignright">></span></li>
                                            </a>
@foreach(Tr_search_categories::getChildByParent($parent->id) as $child)
                                            <a href="/item/category/{{ $child->id }}">
                                                <li class="childCategory">{{ $child->name }}<span class="alignright">></span></li>
                                            </a>
@endforeach
                                        </div>
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
                            <form action="/item/keyword" method="get">
                                <input type="text" name="keyword" placeholder="キーワード検索" size="40" maxlength="255" class="searchBox">
                                <input type="submit" class="searchBtn">
                            </form>
                        </div>
                    </div>
                </section><!-- /.keyWordSearch for sp -->

@if(!FrntUtil::isLogin())
                <div class="user_regist_btn invisible-pc invisible-tab ">
                    <img src="/front/images/bnrTourokuSP.png" alt="新規会員登録" />
                </div>
@endif

                <div class="content-right">
                    @include('front.common.sideInfo')
                </div>

            </div><!-- category & sideInfo -->

            <section class="keyWordSearch clear invisible-sp">
                <div class="contentInr">
                    <h1>キーワードから案件を探す</h1>
                    <div class="">
                        <form class="keyWordSearchForm" method="get" action="/item/keyword">
                            <input class="keyWordSearchInputForm" type="text" name="keyword">
                            <button class="keyWordSearchSearchBtn" type="submit">検　索</button>
                        </form>
                    </div>
                </div>
            </section><!-- /.keyWordSearch for pc,tablet -->

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($){
        // topJob
        $('.topJobInr').tile();

        // slick-slider
        if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
            $('.slider-item').slick({
                arrows: false,            // 前へ/次へナビ
                infinite: true,           // 無限ループ
                dots:false,               // カレントナビ(ドット)
                slidesToShow: 1,          // 見えているスライド数
                centerMode: true,         // 中央寄せ
                centerPadding:'20px',     // 両サイドの見えている部分のサイズ
                autoplay:true,            // 自動再生
            });
        } else {
            $('.slider-item').slick({
                arrows: true,
                infinite: true,
                dots:true,
                slidesToShow: 1,
                centerMode: true,
                centerPadding:'150px',
			    autoplay:true,
            });
        };
    });
</script>

@endsection
