@extends('front.common.layout')
@if(isset($html_title)) @section('title', 'エンジニアルート | '.$html_title)
@else                   @section('title', 'エンジニアルート | 案件一覧')
@endif
@section('content')
<?php
    use Carbon\Carbon;
?>
<div class="wrap">
  <div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a class="hover-thin" itemprop="item" href="/">
        <span itemprop="name">エンジニアルート</span>
      </a>
      <meta itemprop="position" content="1" />
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">検討中案件一覧</span>
      <meta property="position" content="2">
    </span>
  </div><!-- END .breadcrumbs -->
  <div class="main-content item-all">
    <div class="main-content-left">
      <h2 class="main-content__title">検討中案件一覧</h2>
      <hr class="hr-2px-solid-5e8796">
      <p id="no_consider_message" style="padding-left:10px;">@if(count($itemList)==0) 検討している案件はありません @endif</p>
    <div class="main-content__body">
      <div class="content__element">
        <div id="itemList">
@foreach($itemList as $item)
          <div class="item considers_item">
            <div class="itemHeader">
              <div class="table-row">
@if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7)))
                <p class="new">新着</p>
@endif
                <p class="name">{{ $item->name }}</p>
                <p class="sys_type">
                  {{ $item->bizCategorie->name }}
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
                <div class="cmmn-btn">
                  <a href="/item/detail?id={{ $item->id }}" target="_blank">詳細を見る</a>
                  <a href="javascript:void(0);" class="consider_delete-btn" name ="{{ $item->id }}">検討中から外す</a>
                </div>
              </div>
            </div>
          </div>
@endforeach
      　</div>
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

@endsection
