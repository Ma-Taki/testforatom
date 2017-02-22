@extends('front.common.layout')
@section('title', 'エンジニアルート | エントリー内容確認・スキルシート提出')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<div class="wrap">

  @include('front.common.breadcrumbs')

  <div class="main-content entry-confirm">
    <h2 class="main-content__title">エントリー内容確認・スキルシート提出</h2>
    <hr class="hr-2px-solid-5e8796">

    <div class="main-content__body">
      <div class="content__body">
        <div class="content__element">
          <p class="content__info">
            下記の内容を確認後、スキルシートの提出方法をお選び頂き、「この内容でエントリーする」ボタンをクリックしてください。
          </p>

          @include ('front.common.validation_error')

          <hr class="hr-1px-dashed-333">

          <form method="post" action="{{ url('/entry') }}" enctype="multipart/form-data">

            <div class="element__user-info">
              <h3>会員情報</h3>
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
              </div><!-- END .dataTable -->
            </div><!-- END .element__user -->

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
            </div><!-- END .dataTable -->

            <h2>スキルシート提出</h2>
            <div class="dataTable">
              <div class="data">
                <div class="upload_info">
                  <ul>
                    <li>・スキルシートは当フォーム上からのアップロード、又はメールでの提出が可能です。</li>
                    <li>・スキルシートのフォーマットは<a href="/entry/download" class="hover-thin">こちらから</a>ダウンロードできます。</li>
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
                  <p>
                    <a class="skillsheet__email hover-thin" href="mailto:entry@engineer-route.com">
                      entry@engineer-route.com
                    </a>
                    宛てにスキルシートを添付してお送りください。
                  </p>
                </div>
              </div>
            </div><!-- END .dataTable -->

            <div class="cmmn-btn">
              <button type="submit">この内容でエントリーする</button>
            </div>
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            {{ csrf_field() }}
          </form>

        </div><!-- END .content__element -->
      </div><!-- END .content__body -->
    </div><!-- END .main-content__body -->
  </div><!-- END .main-content -->
</div><!-- END .wrap -->
@endsection
