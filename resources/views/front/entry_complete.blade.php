@extends('front.common.layout')
@section('title', 'エントリー - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<div class="wrap">
    <div id="entry" class="content">
        <h1 class="pageTitle">エントリー完了</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">お申し込み頂きありがとうございます。</p>
            <p>下記の内容で#stextエントリーを受け付けました。</p>
            <p>2営業日以内に弊社担当者よりご連絡させて頂きます。</p>

@if(!$entry.skillsheetUpload)
            <p>スキルシートについて
            	スキルシートを下記のメーアドレス宛てにお送り下さいますよう、宜しくお願い申し上げます。
            	送信先 : <a href="mailto:entry@engineer-route.com">entry@engineer-route.com</a>
            </p>
@endif

                <div class="commonCenterBtn">
                    <button type="submit">トップへ戻る</button>
                </div>
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                {{ csrf_field() }}
            </form>
        </div>
	    <table class="dataTable">
		<caption>エントリー内容</caption>
			<th>エントリー番号</th
				$!{entryNumber}
			</td>
		</tr>
		<tr>
			<th>案件番号</th>
                {{ $item->id }}
			<th>案件名</th>
			<td>
				$!{itemEntry.item.name}
			</td>
		</tr>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
