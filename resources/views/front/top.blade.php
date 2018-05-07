@extends('front.common.layout')
@section('title', 'フリーランスエンジニアのIT求人・案件 | エンジニアルート')
@section('h1', 'フリーランスエンジニア向けIT系求人・仕事・案件情報サイト')
@section('description', 'IT・WEB業界のフリーランスエンジニア・デザイナー向けの案件・仕事の求人情報を掲載しています。初めてフリーランスになる方からキャリアアップを目指す方まで幅広く皆様をサポートしています。案件をお探しの方はEngineer-Route（エンジニアルート）へご相談ください。')
@section('canonical', url('/'))

@section('content')
<?php
use App\Libraries\ModelUtility as mdlUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Models\Ms_skill_categories;
use App\Models\Ms_sys_types;
use App\Models\Ms_skills;
use App\Models\Ms_biz_categories;
use App\Models\Ms_areas;
use App\Models\Ms_job_types;
use App\Models\Tr_slide_images;
?>
<div class="wrap">
  <div id="top">
    <div id="slider">
      <ul class="slider slider-item">
        @foreach(Tr_slide_images::getDeleteFlagOFF() as $image)
          <li>
            <a href="{{ $image->link }}">
              <img src="{{ asset('/front/images/slide/'. $image->id .'.jpg') }}?<?php echo filemtime(base_path().'/public/front/images/slide/'.$image->id.'.jpg'); ?>" alt="{{ $image->title }}">
            </a>
          </li>           
        @endforeach
      </ul>
    </div><!-- ./slider -->
@include('front.common.keyword_sp')

@if(!FrntUtil::isLogin())
    <div class="user_regist_btn invisible-pc invisible-tab ">
      <a href="{{ url('/user/regist/auth') }}">
        <img src="/front/images/bnrTourokuSP.png" alt="新規会員登録" />
      </a>
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
          <h3 class="alignleft">新着案件</h3>
          <p class="alignright invisible-sp"><a href="/item/search">新着案件一覧へ</a></p>
          <ul class="fs0 clear">
@foreach($newItemList as $newItem)
            <li>
              <a href="/item/detail?id={{ $newItem->id }}" target="_blank">
                <div class="topJobInr">
                  <img src="/front/images/ico-newjob.png" alt="新着">
                  <h4>{{ $newItem->name }}</h4>
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

@if(!$pickUpItemList->isEmpty())
      <section class="attentionJob">
        <div class="topJobWrap">
          <h3 class="alignleft">急募案件</h3>
          <p class="alignright invisible-sp"><a href="/item/tag/1">急募案件一覧へ</a></p>
          <ul class="fs0 clear">
@foreach($pickUpItemList as $pickUpItem)
            <li>
              <a href="/item/detail?id={{ $pickUpItem->id }}"  target="_blank">
                <div class="topJobInr">
                  <img src="/front/images/ico-attentionJob.png" alt="急募">
                  <h4>{{ $pickUpItem->name }}</h4>
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
@endif
    </div><!-- /.topItemList -->

    @include('front.common.feature')

    <section class="conditions invisible-sp">
      <div class="contentInr">
        <h2>条件から案件を探す</h2>
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

          <form name="form" method="GET" action="{{ url('/item/search') }}">
            <div class="tabBox">
              <!-- スキル -->
              <div class="tabBoxInr">
                @foreach($skillCategories as $skill_category)
                  <div class="tabContent">
                    <h3>{{ $skill_category->name }}</h3>
                    <ul>
                      @foreach(Ms_skills::getSkills($skill_category->id) as $skill)
                        <li class="tabContentElementOneThird">
                          <label>
                            <input class="srchCndtns_chkBx" type="checkbox" name="skills[]" value="{{ $skill->id }}">
                            {{ $skill->name }}
                          </label>
                        </li> 
                      @endforeach  
                    </ul>
                  </div>
                @endforeach
              </div>
              <!-- システム種別 -->
              <div class="tabBoxInr">
                <div class="tabContent">
                  <ul>
                    @foreach(Ms_sys_types::getNotIndexOnly() as $sys_type)
                      <li class="tabContentElementHalf">
                        <label>
                          <input class="srchCndtns_chkBx" type="checkbox" name="sys_types[]" value="{{ $sys_type->id }}">{{ $sys_type->name }}
                        </label>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <!-- 報酬 -->
              <div class="tabBoxInr">
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
              <!-- 業種 -->
              <div class="tabBoxInr">
                <div class="tabContent">
                  <ul>
                    @foreach(Ms_biz_categories::getNotIndexOnly() as $biz_category)
                      <li class="tabContentElementHalf">
                        <label>
                          <input class="srchCndtns_chkBx" type="checkbox" name="biz_categories[]" value="{{ $biz_category->id }}">{{ $biz_category->name }}
                        </label>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <!-- 勤務地 -->
              <div class="tabBoxInr">
                <div class="tabContent">
                  <ul>
                    @foreach(Ms_areas::getNotIndexOnly() as $area)
                      <li class="tabContentElementHalf">
                        <label>
                          <input class="srchCndtns_chkBx" type="checkbox" name="areas[]" value="{{ $area->id }}">{{ $area->name }}
                        </label>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <!-- ポジション -->
              <div class="tabBoxInr">
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
                <div class="searchElement">
                <p class="attention">選択した項目</p>
                <div id="tagSelectedSkill">
                  <p class="tagSelectedName">スキル</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                <div id="tagSelectedSysType">
                  <p class="tagSelectedName">システム種別</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                <div id="tagSelectedRate">
                  <p class="tagSelectedName">報酬</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                <div id="tagSelectedBizCategory">
                  <p class="tagSelectedName">業種</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                <div id="tagSelectedArea">
                  <p class="tagSelectedName">勤務地</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                <div id="tagSelectedPosition">
                  <p class="tagSelectedName">ポジション</p>
                  <ul></ul>
                  <hr class="partitionLine">
                </div>
                </div>
              </div>
            </div>
            <div class="clear"></div>

            <div class="cmmn-btn">
              <button type="submit">検　索</button>
              <a id="check-all-clear-btn" class="bg-color-red" href="javascript:void(0);">選択をクリア</a>
            </div>
          </form>
        </div>
      </div>
    </section><!-- /.conditions -->

    <div class="contentInr">
      <!-- カテゴリーから案件を探す パソコン版-->
      <section class="category-search invisible-sp">
        <h2>カテゴリーから案件を探す</h2>
        <div class="category-search__content">
          @if(!empty($parents))
            @foreach($parents as $parent)
              <div class="category__parent">&nbsp;
                <a href="/item/category/{{ $parent->id }}">{{ $parent->name }}</a>
              </div>
              <div class="category__child">
                @if(!empty($children))
                  @foreach($children as $child)
                    @if($parent->id == $child->parent_id)
                      <a href="/item/category/{{ $child->id }}">{{ $child->name }}</a>
                      <span>|</span>
                    @endif
                  @endforeach
                @endif
              </div>
              <hr class="category__partition">
            @endforeach
          @endif
          @include('front.common.keyword_pc')
        </div>
      </section>
      <!-- カテゴリーから案件を探す スマートフォン版-->
      <section class="category-search invisible-pc invisible-tab">
        <h2>カテゴリーから案件を探す</h2>
        <div class="category-search__content">
          @if(!empty($parents))
            @foreach($parents as $parent)
              <div>
                <div class="category__parent">{{ $parent->name }}
                  <div class="arrow">
                    <span class="arrow arrow-left"></span>
                    <span class="arrow arrow-right"></span>
                  </div>
                </div>
                <ul class="js__category-childs">
                  <li class="category__child">
                    <a href="/item/category/{{ $parent->id }}">
                      {{ $parent->name }}一覧
                    </a>
                  </li>
                  @if(!empty($children))
                    @foreach($children as $child)
                      @if($parent->id == $child->parent_id)
                        <li class="category__child">
                          <a href="/item/category/{{ $child->id }}">
                            {{ $child->name }}
                          </a>
                        </li>
                      @endif
                    @endforeach
                  @endif
                </ul>
              </div>
            @endforeach
          @endif
        </div>
      </section>
      @include('front.common.keyword_sp')

@if(!FrntUtil::isLogin())
      <div class="user_regist_btn invisible-pc invisible-tab ">
        <a href="{{ url('/user/regist/auth') }}">
          <img src="/front/images/bnrTourokuSP.png" alt="新規会員登録" />
        </a>
      </div>
@endif

      <div class="main-content-right">
        @include('front.common.sideInfo')
      </div>

    </div><!-- category & sideInfo -->

    <section class="about">
      <div class="contentInr invisible-sp">
        <h2>最新コラム</h2>
      @include('front.common.recent_posts')
      </div>

      <div class="contentInr">
        <h2>人気プログラミング言語ランキング</h2>
      @include('front.common.programming_lang_ranking')
      </div>


      <div class="contentInr">
        <h2>エンジニアルートとは？</h2>
        <div class="about__text">
          <p>
            Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、それぞれのご希望に合ったお仕事探しから、カウンセリング、参画後のフォロー、プロジェクト終了まで 全てのプロセスを徹底的にサポートいたします。
          </p>
          <p>
            それぞれの道（ルート）を見定め、そして目標に向かって一歩ずつ進んでいく為に。<br>
            そして私達も、皆様とともに一緒に歩んでいく為に、 一つずつ丁寧にお手伝いしていきます。
          </p>
        </div>

        <hr class="hr-1px-dashed-5e8796">

        <div class="appeal-point">
          <h3>1．取引先を担当している営業がカウンセリング</h3>
          <p>
            コーディネーターなどの専任スタッフが事前にお会いするケースが通常多いですが、エンジニアルートでは、日々飛び回っている営業や役員がカウンセラーとして直接皆様とお会いし、最新でリアルな情報をお伝えいたします。
            また、状況毎に担当が変わることがないため、話も伝わりやすく安心して作業をして頂ける環境です。
          </p>
          <hr class="hr-1px-dashed-5e8796">

          <h3>2．営業力を活かした幅広く豊富な案件</h3>
          <p>
            汎用機系～オープン・WEB系、インフラ・サーバ系や、コンサルティング、PM、PMO、システムエンジニア、プログラマー、ヘルプデスク・サポート系、ディレクター、WEBデザイナーなど幅広い職種や言語にも対応しております。その他、英語や簿記（会計）を活かした求人もございます。役員や営業チームが抱えている案件のほか、万が一ご希望に合わなかった場合は、ヒアリングした内容を元に、新規開拓を行い、新たな案件をご提案いたします。
          </p>
          <hr class="hr-1px-dashed-5e8796">

          <h3>3．サイト上には掲載していない公開NG案件・求人も多数</h3>
          <p>
            WEB上には公開できない案件・求人情報が多く存在しています。その他、特に良い案件はサイトに公開する前に決まってしまうケースも非常に多いため、まずは一度弊社でご登録・カウンセリングをしていただくことをお勧めいたします。
          </p>
          <hr class="hr-1px-dashed-5e8796">

          <h3>4．初めてフリーランスになる方へ</h3>
          <p>
            今まで正社員として働いていたが、初めてフリーランスになろうと考えている方。「自分のスキルで勝負したい」「たくさん稼ぎたい」「やりたい仕事を選びたい」など考えてはいるものの、フリーランスってどうしたら良いのか分からない事も多いと思います。エンジニアルートでは経験豊富なスタッフがお話をお聞きして、アドバイスをいたします。
          </p>
          <hr class="hr-1px-dashed-5e8796">

          <br>
          <br>
          <h3 class="underline">まずは今までのご経験や今後のご希望を私たちにお聞かせください</h3>
          <p>
            ・とにかく今は条件（単価）優先で稼ぎたい<br>
            ・腰を据えて安定している現場で長期にわたって作業したい<br>
            ・通勤時間を極力減らしたい<br>
            ・始業開始時間が遅い現場がいい<br>
            ・完全に禁煙（分煙）なオフィスがいい<br>
            ・最新のツールなどが常に使える環境で働きたい<br>
            ・大手企業の情報システム部などで働いてみたい<br>
          </p>
          <p>など、できる限りご希望に合った案件をご紹介させていただきます。</p>
        </div>
      </div>
    </section>

  </div>
</div>

<script type="text/javascript">
    jQuery(function($){
        // topJob
        $('.topJobInr').tile();
        // slick-slider
        if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
            $('.slider-item').slick({
                arrows: false,        // 前へ/次へナビ
                infinite: true,       // 無限ループ
                dots:false,           // カレントナビ(ドット)
                slidesToShow: 1,      // 見えているスライド数
                centerMode: true,     // 中央寄せ
                centerPadding:'20px', // 両サイドの見えている部分のサイズ
                autoplay:true,        // 自動再生
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