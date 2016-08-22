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
        <h1 class="pageTitle">エントリー内容確認・スキルシート提出</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">下記の内容を確認後、スキルシートの提出方法をお選び頂き、「この内容でエントリーする」ボタンをクリックしてください。</p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/entry') }}" enctype="multipart/form-data">

                <h2>会員情報</h2>
                <div class="dataTable">
                    <div class="data">
                        <div class="name">氏名</div>
                        <div class="value">{{ $user->last_name .' ' .$user->first_name }}</div>
                    </div>
                    <div class="data">
                        <div class="name">住所(都道府県)</div>
                        <div class="value">{{ $user->prefecture->name }}</div>
                    </div>
                    <div class="data">
                        <div class="name">メールアドレス</div>
                        <div class="value">{{ $user->mail }}</div>
                    </div>
                    <div class="data">
                        <div class="name">電話番号</div>
                        <div class="value">{{ $user->tel }}</div>
                    </div>
                </div>

                <h2>案件内容</h2>
                <div class="dataTable">
                    <div class="data">
                        <div class="name">案件名</div>
                        <div class="value">{{ $item->name }}</div>
                    </div>
                    <div class="data">
                        <div class="name">業種</div>
                        <div class="value">{{ $item->bizCategorie->name }}</div>
                    </div>
                    <div class="data">
                        <div class="name">ポジション</div>
                        <div class="value">{{ MdlUtil::getNameAll($item->jobTypes) }}</div>
                    </div>
                    <div class="data">
                        <div class="name">システム種別</div>
                        <div class="value">{{ MdlUtil::getNameAll($item->sysTypes) }}</div>
                    </div>
                    <div class="data">
                        <div class="name">報酬</div>
                        <div class="value">{{ $item->rate_detail }}</div>
                    </div>
                    <div class="data">
                        <div class="name">エリア</div>
                        <div class="value">{{ $item->area_detail }}</div>
                    </div>
                    <div class="data">
                        <div class="name">就業時間</div>
                        <div class="value">{{ $item->working_hours}}</div>
                    </div>
                    <div class="data">
                        <div class="name">詳細</div>
                        <div class="pre-wrap value detail">{{ $item->detail }}</div>
                    </div>
                </div>

                <h2>スキルシート提出</h2>
                <div class="dataTable">
                    <div class="data">
                        <div class="upload_info">
                            <ul>
                                <li>・キルシートは当フォーム上からのアップロード、又はメールでの提出が可能です。</li>
                                <li>・スキルシートのフォーマットはこちらからダウンロードできます。</li>
                                <li>・アップロード可能なファイル形式はMicrosoft Excel(.xls,xlsx)、Word(.doc,.docx)、PowerPoint(.ppt,pptx)、PDF(.pdf)、ファイルサイズは1MB以内です。</li>
                                <li>・上記以外のファイルはメールで提出してください。</li>
                            </ul>
                        </div>
                    </div>
                    <div class="data">
                        <div class="name">アップロードで提出</div>
                        <div class="value upload">
                            <input type="file" name="skillSheet">
                            <p>※ メールで提出する場合は空欄のままにしてください。</p>
                        </div>
                    </div>
                    <div class="data">
                        <div class="name">メールで提出</div>
                        <div class="value">
                            <p><a href="mailto:entry@engineer-route.com">entry@engineer-route.com</a>宛てにスキルシートを添付してお送りください。</p>
                        </div>
                    </div>
                </div>

                <div class="commonCenterBtn">
                    <button type="submit">この内容でエントリーする</button>
                </div>
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                {{ csrf_field() }}
            </form>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
