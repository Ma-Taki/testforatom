@extends('front.common.layout')
@if(isset($html_title)) @section('title', 'エンジニアルート | '.$html_title)
@else                   @section('title', 'エンジニアルート | 案件一覧')
@endif

@if(!$params["nodata"])

@if ($itemList->previousPageUrl())
@if ($itemList->currentPage() == 2)
@section('prev', Request::url())
@else
@section('prev', $itemList->previousPageUrl())
@endif
@endif
@if ($itemList->nextPageUrl())
@section('next', $itemList->nextPageUrl())
@endif


@if ($itemList->currentPage() == 1)
@section('canonical', Request::url())
@else
@section('canonical', $itemList->url($itemList->currentPage()))
@endif

@endif

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
    use App\Libraries\CookieUtility as CkieUtil;
    use App\Models\Tr_users;
    use App\Http\Controllers\front\ConsiderController;
?>

<div class="wrap">

  @include('front.common.breadcrumbs')

  <div class="main-content item-all">
    <div class="main-content-left">
        <h2 class="main-content__title">該当案件一覧</h2>
        <span class="item-all-count">該当件数：<span class="num">{{ $itemList->total() }}</span>件</span>
        <hr class="hr-2px-solid-5e8796">
      <div class="main-content__body">
        <div class="content__element">

@if(strstr(Request::url(), '/item/search'))
          <div class="add-conditions invisible-pc invisible-tab">
            <button class="shadow">条件を指定して検索する</button>
          </div>
@endif

        <div class="conditions">
@if(strstr(Request::url(), '/item/search'))
          <div class="search">
            <div class="tabSelected">
              <p class="title">選択した項目</p>
              <span class="toggle-btn shadow">
                <span class="mark">+</span>
                <span class="text">検索条件を変更する</span>
              </span>
              <div class="tabSelectedInr">
                <div class="searchElement">
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
            <form id="tabForm" method="GET" action="{{ url('/item/search') }}">
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
              <div class="cmmn-btn">
                <button type="submit">検　索</button>
              </div>
            </form>
          </div>
@endif
@if(!$params["nodata"])
          <div class="sort">
            <span class="selectBox">
              <select id="order">
                <option value="RegistrationDesc" {{ $params['order'] == 'RegistrationDesc' ? "selected" : ""}}>新着順</option>
                <option value="ServiceAsc" {{ $params['order'] == 'ServiceAsc' ? "selected" : ""}}>受付終了日が近い順</option>
                <option value="RateDesc" {{ $params['order'] == 'RateDesc' ? "selected" : ""}}>報酬が高い順</option>
              </select>
            </span>
            <label>
              <span class="selectBox">
                <select id="limit">
                  <option value="1" {{ $params['limit'] == 1 ? "selected" : ""}}>10</option>
                  <option value="2" {{ $params['limit'] == 2 ? "selected" : ""}}>20</option>
                  <option value="3" {{ $params['limit'] == 3 ? "selected" : ""}}>50</option>
                </select>
              </span>
              件表示
            </label>
          </div>
@endif
        </div>

<?php
  //ヒット数が０だった場合...
  if($params['nodata']){
    $itemList = $params['nodata']; //ランダムに取得した掲載終了案件に入れ替え
  }
?>

@if($params['nodata'])
        <h2 style="margin-top:20px;">終了した案件ですが、探す際の参考としてご覧ください。</h2>
@endif
<div id="itemList">
@foreach($itemList as $item)
          <div class="item">
            <div class="itemHeader">
              <div class="table-row">
@if(!$params['nodata'])
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                <p class="new">新着</p>
@endif
@endif
                <p class="name">{{ $item->name }}</p>
                <p class="sys_type">
@if(!$params['nodata'])
                  {{ $item->bizCategorie->name }}
@endif
                </p>
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
@if(!$params['nodata'])
                <div class="other">
                  <p class="otherName">システム種別</p>
                  <p class="otherValue">
@foreach($item->sysTypes as $sys_type)
                    {{ $sys_type->name }}<span class="wordPunctuation">、</span>
@endforeach
                  </p>
                </div>
@endif
@if(!$params['nodata'])
                <div class="other">
                <p class="otherName">ポジション</p>
                <p class="otherValue">
@foreach($item->jobTypes as $jobType)
                    {{ $jobType->name }}<span class="wordPunctuation">、</span>
@endforeach
                </p>
                </div>
@endif
                <p class="detail">{{ $item->detail }}</p>
                <div class="cmmn-btn">
                  <a href="/item/detail?id={{ $item->id }}" target="_blank">詳細を見る</a>
<?php $styles = ConsiderController::makeConsiderButtonStyle($item->id); ?>
                  <a href="javascript:void(0);" class="consider-btn {{ $styles['class'] }}" name="{{ $item->id }}">{{ $styles['text'] }}</a>
                </div>
              </div>
            </div>
          </div>
@endforeach
</div>

@if(!$params['nodata'])
        <div class="paginate invisible-sp">
          {!! (new App\Libraries\Pagination\CustomBootstrapPresenter($itemList->appends($params)))->render() !!}
        </div>
@if($itemList->hasMorePages())
        <div class="read-more cmmn-btn invisible-tab invisible-pc">
          <button id="js__read-more">もっと見る</button>
        </div>
@endif
@endif
        </div><!-- END CONTENT-LEFT -->
      </div>
    </div>

    <div class="main-content-right invisible-sp">
      @include('front.common.sideInfo')
    </div><!-- END .main-content-right -->
    <div class="clear"></div>
  </div><!-- END .main-content -->

  <div id="pucat">
    @include('front.common.feature')
  </div>

  @include('front.common.keyword_pc')

</div><!-- END WRAP -->

<script type="text/javascript">

  jQuery(function($){

    var $conditions_box = $(".conditions .tab");
    $conditions_box.hide();

    $(".conditions .toggle-btn").click(function(){

      if ($conditions_box.is(':visible')) {
        $(this).children('.mark').text('+');
        $(this).children('.text').text('検索条件を変更する');
      } else {
        $(this).children('.mark').text('-');
        $(this).children('.text').text('閉じる');
      };
      $conditions_box.slideToggle(700);
	  });
	});
</script>
@endsection
