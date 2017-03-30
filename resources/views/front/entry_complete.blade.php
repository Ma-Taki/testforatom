@extends('front.common.layout')
@section('title', 'エントリー完了 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<div class="wrap">

  @include('front.common.breadcrumbs')

  <div class="main-content entry-complete">
    <h2 class="main-content__title">エントリー完了</h2>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <div class="content__body">
          <p>
            お申し込み頂きありがとうございます。<br>
            下記の内容でエントリーを受け付けました。<br>
            2営業日以内に弊社担当者よりご連絡させて頂きます。
          </p>
          <div class="entry_content">
            <p class="entry_content_title"><span>エントリー内容</span></p>
            <div class="entry_content_body">
              <ul>
                <li><p>エントリーID</p><p>{{ $entry->id }}</p></li>
                <li><p>案件ID</p><p>{{ $item->id }}</p></li>
                <li><p>案件名</p><p>{{ $item->name }}</p></li>
              </ul>
            </div>
          </div>

@if(!$entry->skillsheet_upload)
          <div class="skillsheet">
            <p class="skillsheet_title"><span>スキルシートについて</span></p>
            <div class="skillsheet_body">
                <p>スキルシートを下記のメーアドレス宛てにお送り下さいますよう、宜しくお願い申し上げます。</p>
                <p>送信先 : <a href="mailto:entry@engineer-route.com">entry@engineer-route.com</a></p>
            </div>
          </div>
@endif

          <div class="cmmn-btn">
            <a href="{{ url('/') }}">トップへ戻る</a>
          </div>
        </div>
      </div>
    </div>
  </div><!-- END main-content -->
</div><!-- END .wrap -->
@endsection
