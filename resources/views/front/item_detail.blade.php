@extends('front.common.layout')

<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use Carbon\Carbon;
?>

@section('title', $item->name .' - AN' .$item->id .' | エンジニアルート')
@section('canonical', Request::url().'?id='.$item->id)
@section('description', HtmlUtil::getDescriptionText($item))

@section('content')
<div class="wrap">

  @include('front.common.breadcrumbs')

  <div class="main-content item-detail">
    <div class="main-content-left">
      <h2 class="main-content__title">案件詳細</h2>
      <hr class="hr-2px-solid-5e8796">

      <div class="main-content__body">
        <div class="content__element">

@if(!$canEntry)
          <div class="alert alert-danger">
            <ul>
              <li>この案件は掲載期間をすぎています。エントリーすることはできません。</li>
            </ul>
          </div>
@endif
          <div class="item">
            <div class="itemHeader">
              <div class="table-row">
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                <p class="new">新着</p>
@endif
                <p class="name">{{ $item->name }}</p>
                <p class="item_id">案件ID：AN{{ $item->id }}</p>
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
                  <p class="otherName">業種</p>
                  <p class="otherValue">
                    {{ $item->bizCategorie->name }}
                  </p>
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
                <div class="other">
                  <p class="otherName">就業期間</p>
                  <p class="otherValue">
                    {{ $item->employment_period }}
                  </p>
                </div>
                <div class="other">
                  <p class="otherName">就業時間</p>
                  <p class="otherValue">
                    {{ $item->working_hours }}
                  </p>
                </div>
                <div class="other">
                  <p class="otherName">詳細</p>
                  <p class="pre-wrap otherValue">{{ $item->detail }}</p>
                </div>
                <div class="other">
                  <p class="otherName">エントリー受付期間</p>
                  <p class="otherValue">
                    {{ $item->service_start_date->format('Y年n月j日').' 〜 '.$item->service_end_date->format('Y年n月j日') }}
                  </p>
                </div>
                <div class="itemTagList invisible-sp">

@foreach($item->tags as $tag)
                  <a href="/item/tag/{{ $tag->id }}">
                    <p class="tag">{{ $tag->term }}</p>
                  </a>
@endforeach

                </div>

@if($canEntry)
                <div class="cmmn-btn">
                  <a href="/entry?id={{ $item->id }}">この案件にエントリーする</a>
                </div>
@endif

              </div><!-- END .itemInfoInr -->
            </div><!-- END .itemInfo -->
            <div class="itemTagList invisible-pc invisible-tab">
@foreach($item->tags as $tag)
              <a href="/item/tag/{{ $tag->id }}">
                <p class="tag">{{ $tag->term }}</p>
              </a>
@endforeach
            </div>
          </div><!-- END .item -->
        </div><!-- END .content__element -->
      </div><!-- END .main-content__body -->
    </div><!-- END .main-content-left -->

    <div class="main-content-right invisible-sp">
      @include('front.common.sideInfo')
    </div><!-- END .main-content-right -->

    <div class="clear "></div>
  </div><!-- END .main-content -->
</div><!-- END WRAP -->

@if(!$recoItemList->isEmpty())
<section class="recommended">
  <div class="recommended__inner">
    <h3>こちらもおすすめ</h3>
    <div class="reco-items">
@foreach($recoItemList as $item)
      <div class="reco-item">
        <a href="/item/detail?id={{ $item->id }}"  target="_blank">
          <p class="header">{{ $item->name }}</p>
          <div class="detail">
            <div class="table">
              <p class="name">報酬</p>
              <p class="rate">{{ $item->rate_detail }}</p>
            </div>
            <div class="table">
              <p class="name">エリア</p>
              <p class="area">{{ $item->area_detail }}</p>
            </div>
            <p class="skill">要求スキル</p>
            <p class="skills">{{ HtmlUtil::convertSkillsMdlToNameStr($item->skills) }}</p>
          </div>
        </a>
      </div>
@endforeach
    </div>
  </div>
  <script type="text/javascript">
    $(function() {
      $(".reco-item .header").tile();
    });
  </script>
</section>
@endif

<div id="pucat">
  @include('front.common.feature')
</div>
<div class="main-content-right invisible-pc invisible-tab">
  @include('front.common.sideInfo')
</div><!-- END CONTENT-RIGHT -->

@include('front.common.keyword_pc')

@endsection
