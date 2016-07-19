<?php
use App\Libraries\ModelUtility as mdlUtil;
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。">
        <meta name="keywords" content="IT案件,案件情報,求人,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP">
        <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
        <title>フリーランス、フリーエンジニアのためのIT系求人・案件情報提供サイト「エンジニアルート」</title>
        <link rel="canonical" href="http://www.engineer-route.com/">
        <link rel="icon" href="{{ url('/front/favicon.ico') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/front/css/slick-theme.css') }}">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/slick.min.js') }}"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/jquery.tile.js') }}"></script>
        <script type="text/javascript" charset="utf-8" src="{{ url('/front/js/all.js') }}"></script>

    </head>
    <body>
        <header>
            <div class="headerInr">
                <p>フリーランス、フリーエンジニアのためのIT系求人・案件情報提供サイト<span class="onlyVisiPc">「エンジニアルート」</span></p>
                <div class="user">
                    <ul>
                        <li><a href="#" class="signin">新規登録</a></li>
                        <li><a href="#">ログイン</a></li>
                    </ul>
                </div>
                <h1 class="alignleft"><a href="#" title="エンジニアルート"></a></h1>
                <div class="search">
                    <form action="#" method="post">
                        <input type="search" name="search" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                        <input type="submit" name="submit" value="検索" class="searchBtn">
                    </form>
                </div><!-- /.search -->
            </div><!-- /.headerInr -->
        </header>
        <div class="wrapper">
            <div class="main">
                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="#">HOME</a></li>
                        <li class="nav-item"><a href="hoge1">エンジニアルートとは</a></li>
                        <li class="nav-item"><a href="hoge2">新着一覧</a></li>
                        <li class="nav-item"><a href="hoge3">Q&amp;A</a></li>
                        <li class="nav-item"><a href="hoge4">ご利用の流れ</a></li>
                        <li class="nav-item"><a href="hoge5">企業の皆様へ</a></li>
                    </ul>
                </nav><!-- /Nav -->

                <div class="content">
                    <ul class="slider center-item">
                        <li><a href="#"><img src="/front/images/bnr001.jpg"></a></li>
                        <li><a href="#"><img src="/front/images/bnr002.jpg"></a></li>
                        <li><a href="#"><img src="/front/images/bnr001.jpg"></a></li>
                        <li><a href="#"><img src="/front/images/bnr002.jpg"></a></li>
                    </ul>
                    <script>
                        $(function() {
                            $('.center-item').slick({
                                arrows: true,
                                infinite: true,
                                dots:false,
                                slidesToShow: 1,
                                centerMode: true, //要素を中央寄せ
                                centerPadding:'150px', //両サイドの見えている部分のサイズ
                                autoplay:true, //自動再生
                                responsive: [{
                                    breakpoint: 480,
                                    settings: {
                                        centerMode: false,
                                    }
                                }]
                            });
                        });
                    </script><!-- /.slider -->

                    <div class="topItemList">
                        <section class="newJob">
                            <div class="topJobWrap">
                                <h1 class="alignleft">新着案件</h1>
                                <p class="alignright"><a href="#">新着案件一覧へ</a></p>
                                <ul class="fs0 clear">
@foreach($newItemList as $newItem)
                                    <li>
                                        <a href="#">
                                            <div class="topJobInr">
                                                <img src="/front/images/ico-newjob.png">
                                                <h2>{{ $newItem->name }}</h2>
                                                <p class="location">{{ $newItem->area_detail }}</p>
                                                <p class="remuneration">{{ $newItem->rate_detail }}</p>
                                                <p class="update">{{ $newItem->service_start_date->format('m/d') }}</p>
                                            </div>
                                        </a>
                                    </li>
@endforeach
                                </ul>
                            </div>
                        </section><!-- /.newJob -->

                        <section class="attentionJob">
                            <div class="topJobWrap">
                                <h1 class="alignleft">急募案件</h1>
                                <p class="alignright"><a href="#">急募案件一覧へ</a></p>
                                <ul class="fs0 clear">
@foreach($pickUpItemList as $pickUpItem)
                                    <li>
                                        <a href="#">
                                            <div class="topJobInr">
                                                <img src="/front/images/ico-attentionJob.png">
                                                <h2>{{ $pickUpItem->name }}</h2>
                                                <p class="location">{{ $pickUpItem->area_detail }}</p>
                                                <p class="remuneration">{{ $pickUpItem->rate_detail }}</p>
                                                <p class="update">{{ $pickUpItem->service_start_date->format('m/d') }}</p>
                                            </div>
                                        </a>
                                    </li>
@endforeach
                                </ul>
                            </div>
                        </section><!-- /.attentionJob -->
                    </div><!-- /.topItemList -->

                    <section class="pickupCat">
                        <div class="contentInr">
                            <h1>特集から案件を探す</h1>
                            <ul class="fs0">
                                <li class="pucat01"><a href="#">残業少なめ</a></li>
                                <li class="pucat02"><a href="#">年齢不問</a></li>
                                <li class="pucat03"><a href="#">高単価</a></li>
                                <li class="pucat04"><a href="#">ロースキル</a></li>
                                <li class="pucat05"><a href="#">現場直</a></li>
                            </ul>
                        </div>
                    </section><!-- /.pickupCat -->

                    <section class="conditions">
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

                                <form name="form" role="form" method="POST" action="{{ url('/item/search') }}">

                                    <div class="tabBox">
                                        <div class="tabBoxInr">
                                            <p class="attention">10個まで選択可能<span>※他の条件と組み合わせて検索できます。</span></p>
@foreach($skill_categories as $skill_category)
@if(!$skill_category->skills->isEmpty())
                                            <div class="tabContent">
                                                <h2>{{ $skill_category->name }}</h2>
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
                                                        <label><input type="radio" class="srchCndtns_radio" name="search_rate" value="{{ $value }}">{{ $key }}</label>
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
                        <section class="categorySearch">
                            <div class="categorySearchInr">
                                <h1>カテゴリーから案件を探す</h1>
                                <div class="categorySearchContent">
@foreach($parentList as $parentKey => $parent)
                                    <div class="parentCategory">&nbsp;<a href="/item/search?category_id={{ $parentKey }}">{{ $parent }}</div>
                                        <div class="childCategory">
@foreach($childList[$parentKey] as $child)
                                            <a href="/item/search?category_id={{ $child->id }}">{{ $child->name }}</a>
@if(next($childList[$parentKey]) !== FALSE)
                                            <span>|</span>
@endif
@endforeach
                                        </div>
                                        <hr class="categoryPartitionLine">
@endforeach
                                </div>
                            </div>
                        </section><!-- /.categorySearch -->

                        <section class="sideInfo">
                            <div class="sideInfoInr">
                                <ul>
                                    <li>
                                        <div class="sideInfoCstmBnr">
                                            <a href="#">
                                                <div class="sideInfoCstmBnrMWrd">無料会員登録</div>
                                                <div class="sideInfoCstmBnrSWrd">案件紹介をご希望の方はこちら</div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sideInfoImgBnr">
                                            <a href="#"><img src="/front/images/sBnr01.png"></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sideInfoImgBnr">
                                            <a href="#"><img src="/front/images/sBnr02.png"></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sideInfoImgBnr">
                                            <a href="#"><img src="/front/images/sBnr03.png"></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sideInfoImgBnr">
                                            <a href="#"><img src="/front/images/sBnr04.png"></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section><!-- /.sideInfo -->
                    </div><!-- category & sideInfo -->

                    <section class="keyWordSearch clear">
                        <div class="contentInr">
                            <h1>キーワードから案件を探す</h1>
                            <div class="">
                                <form class="keyWordSearchForm" method="post" action="/item/search">
                                    <input class="keyWordSearchInputForm" type="text" name="search_keyWord">
                                    <button class="keyWordSearchSearchBtn" type="submit">検　索</button>
                                </form>
                            </div>
                        </div>
                    </section>
                    <script>
                        jQuery(function($){
                            var tabmenu = $('.tabMenu ul li');
                            $('.tabBox .tabBoxInr').hide().eq(0).show();
                            tabmenu.eq(0).addClass('navhit');
                            tabmenu.click(function () {
                                var no = $(this).parent().children().index(this);
                                tabmenu.removeClass('navhit');
                                $(this).addClass('navhit');
                                $('.tabBox .tabBoxInr').hide().eq(no).show();
                            });
                        });

                        jQuery(function($){
                            $('.srchCndtns_chkBx').each(function(){
                                $(this).click(function(){
                                    var click_chkBox = $(this);
                                    var chkBox_label = $(this).parent('label').text();
                                    var selected_cndtns_type;
                                    switch (click_chkBox.attr('name')) {
                                        case 'skills[]': selected_cndtns_type = $('#tagSelectedSkill'); break;
                                        case 'sys_types[]': selected_cndtns_type = $('#tagSelectedSysType'); break;
                                        case 'biz_categories[]': selected_cndtns_type = $('#tagSelectedBizCategory'); break;
                                        case 'areas[]': selected_cndtns_type = $('#tagSelectedArea'); break;
                                        case 'job_types[]': selected_cndtns_type = $('#tagSelectedPosition'); break;
                                    }
                                    if($(this).prop('checked')){
                                        var addValue = $('<li>' + chkBox_label + '<span id="' + chkBox_label + '">×</span></li>');
                                        addValue.children('span').click(function(){
                                            $(this).parent('li').remove();
                                            if (selected_cndtns_type.find('li').length <= 0) {
                                                selected_cndtns_type.hide();
                                            }
                                            click_chkBox.prop('checked', false);

                                        });
                                        selected_cndtns_type.show();
                                        addValue.appendTo(selected_cndtns_type.children('ul'));

                                    } else {
                                        selected_cndtns_type.find('li').each(function(){
                                            if($(this).children('span').attr('id') == chkBox_label){
                                                $(this).remove();
                                                if (selected_cndtns_type.find('li').length <= 0) {
                                                    selected_cndtns_type.hide();
                                                }
                                            }
                                        });
                                    }
                                });
                            });
                        });

                        jQuery(function($){
                            $('.srchCndtns_radio').change(function (){
                                if ($('#cndtns_rate')[0]) {
                                    $('#cndtns_rate').parent('li').remove();
                                }
                                if ($(this).val() != 0) {
                                    var addText = $(this).parent('label').text();
                                    var addValue = $('<li>' + addText + '<span id="cndtns_rate" >×</span></li>');
                                    addValue.children('span').click(function(){
                                        $(this).parent('li').remove();
                                        $('.srchCndtns_radio').each(function(){
                                            if($(this).val() == 0){
                                                $('#tagSelectedRate').hide();
                                                $(this).prop('checked', true);
                                            }
                                        });
                                    });
                                    $('#tagSelectedRate').show();
                                    addValue.appendTo('#tagSelectedRate ul');
                                } else {
                                    $('#tagSelectedRate').hide();
                                }
                            });
                        });

                        jQuery(function($){
                            $('.topJobInr').tile();
                        });
                    </script>
                </div>
            </div>
        </div>

        <footer class="bg clear">
            <ul>
                <li><a href="#">運営会社</a></li>
                <li><a href="#">プライバシーポリシー</a></li>
                <li><a href="#">利用規約</a></li>
                <li><a href="#">お問い合わせ</a></li>
            </ul>
            <p>&copy; SolidSeed Co.,Ltd.</p>
        </footer>
    </body>
</html>
