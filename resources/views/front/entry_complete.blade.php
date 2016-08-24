@extends('front.common.layout')
@section('title', 'エントリー完了 - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<div class="wrap">
    <div id="entry_complete" class="content">
        <h1 class="pageTitle">エントリー完了</h1>
        <hr class="partitionLine_02">
        <div class="contentInr alignCenter">
            <p>お申し込み頂きありがとうございます。</p>
            <p>下記の内容でエントリーを受け付けました。</p>
            <p>2営業日以内に弊社担当者よりご連絡させて頂きます。</p>
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

@if(!$entry.skillsheet_upload)
            <div class="skillsheet">
                <p class="skillsheet_title"><span>スキルシートについて</span></p>
                <div class="skillsheet_body">
                    <p>スキルシートを下記のメーアドレス宛てにお送り下さいますよう、宜しくお願い申し上げます。</p>
                    <p>送信先 : <a href="mailto:entry@engineer-route.com">entry@engineer-route.com</a></p>
                </div>
            </div>
@endif

            <div class="commonCenterBtn">
                <button type="submit">トップへ戻る</button>
            </div>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
