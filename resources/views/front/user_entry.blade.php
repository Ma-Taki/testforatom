@extends('front.common.layout')
@section('title', 'エンジニアルート | エントリー履歴')
@section('content')
<?php
use App\Libraries\ModelUtility as MdlUtil;
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
      <a class="hover-thin" itemprop="item" href="/user">
        <span itemprop="name">マイページ</span>
      </a>
      <meta property="position" content="2">
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">エントリー履歴</span>
      <meta property="position" content="3">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-entry">
    <h1 class="main-content__title">エントリー履歴<span class="main-content__title__count">該当件数：<span class="main-content__title__count__num ">{{ count($entry_list) }}</span>件</span></h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <div class="content__body">

@if (count($entry_list) > 0)
          <div class="items">
@foreach($entry_list as $entry)
            <div class="item">

              <div class="item__header">
                <p class="item__header__name">{{ $entry->item->name }}<span class="item__header__id">エントリーID：EN{{ $entry->id }}</span></p>
              </div>

              <div class="item__info">

                <div class="item__info__pickup">
                  <div class="item__info__pickup__element">
                    <div class="item__info__pickup__name">報　酬</div>
                    <div class="item__info__pickup__value">{{ $entry->item->rate_detail }}</div>
                  </div>
                  <div class="item__info__pickup__element">
                    <div class="item__info__pickup__name">エリア</div>
                    <div class="item__info__pickup__value">{{ $entry->item->area_detail }}</div>
                  </div>
                </div>

                <div class="item__info__other">
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">エントリー受付日</div>
                    <div class="item__info__other__value">
                      {{ $entry->entry_date->format('Y/m/d H:i') }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">業種</div>
                    <div class="item__info__other__value">
                      {{ $entry->item->bizCategorie->name }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">システム種別</div>
                    <div class="item__info__other__value">
                      {{ MdlUtil::getNameAll($entry->item->sysTypes) }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">ポジション</div>
                    <div class="item__info__other__value">
                      {{ MdlUtil::getNameAll($entry->item->jobTypes) }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">就業期間</div>
                    <div class="item__info__other__value">
                      {{ $entry->item->employment_period }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">就業時間</div>
                    <div class="item__info__other__value">
                      {{ $entry->item->working_hours }}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">詳細</div>
                    <div class="item__info__other__value">
                      {!! nl2br($entry->item->detail) !!}
                    </div>
                  </div>
                  <div class="item__info__other__element">
                    <div class="item__info__other__name">エントリー受付期間</div>
                    <div class="item__info__other__value">
                      {{ $entry->item->service_start_date->format('Y年n月j日').' 〜 '.$entry->item->service_end_date->format('Y年n月j日') }}
                    </div>
                  </div>
                </div>
@if($entry->skillsheet_upload)
                <div class="cmmn-btn">
                  <a href="/user/entry/download?id={{ $entry->id }}"><button>スキルシートをダウンロード</button></a>
                </div>
@endif
              </div>
            </div>
@endforeach
          </div>
@else
          <p>エントリー済み案件はありません。</p>
@endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
