@extends('front.common.layout')
@section('title', 'エントリー内容確認・スキルシート提出 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

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
          <div class="alert_design"></div>
          @include ('front.common.validation_error')
          <hr class="hr-1px-dashed-333">
          <form method="post" name="js-entry-form" action="{{ url('/entry') }}" enctype="multipart/form-data">
            <div class="user-info">
              <div class="data-table">
                <h3 class="data-table__title">会員情報</h3>
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
              </div><!-- END .data-table -->
            </div><!-- END .user-info -->
            <div class="item-info">
              <div class="data-table">
                <h3 class="data-table__title">案件情報</h3>
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
                  <div class="value pre-wrap">{{ $item->detail }}</div>
                </div>
              </div><!-- END .data-table -->
            </div><!-- END .item-info -->
            <div class="send-skillsheet">
              <div class="data-table">
                <h3 class="data-table__title">履歴書・職務経歴書・スキルシート</h3>
                <div class="data">
                  <div class="data__info">
                    <p>・スキルシートのフォーマットは<a href="/entry/download" class="hover-thin">こちらから</a>ダウンロードできます。</p>
                    <p>・アップロード可能なファイル形式はMicrosoft Excel(xls,xlsx)、Word(doc,docx)、PowerPoint(ppt,pptx)、PDF(pdf)です。</p>
                    <p>・アップロードは3ファイルまでです。</p>
                    <p>・1つのファイルサイズは1MB以内です。</p>
                    <p> ・上記以外のファイルはメールで提出してください。</p>
                  </div>
                </div>
                <div class="data">
                  <div class="name">提出方法</div>
                  <div class="value">
                    <div class="spNone">
                      <label>
                      <!-- ~640px 641px~1024pxのときはドラッグ&ドロップ非表示 -->
                        <input type="radio" name="file_type" id="file_ddrop" value="entry_dd">ドラッグ&amp;ドロップ
                      </label>
                    </div>
                    <label>
                      <input type="radio" name="file_type" id="file_explorer" value="entry_fe">ファイルを選択
                    </label>
                    <label>
                      <input type="radio" name="file_type" id="file_mail" value="entry_fma">メール
                    </label>
                  </div>
                </div>
                <div class="data upload-box" style="display:none;">
                  <div class="value-box">
                    <!-- ドラッグ&ドロップ -->
                    <div class="input_fma_value ddrop_files" id="ddrop" style="display:none;">
                      <div id="dragandrop" class="registry-upload-dd">
                        <p id="js-dd-uploaded" class="registry-upload-dd__txt">
                          ここへファイルを<br>
                          1つずつドロップしてください。
                        </p>
                        <span id="dd_text" style="display:block;"></span>
                      </div>
                    </div>
                    <!-- ファイル選択 -->
                    <div class="input_fma_value explorer_files">
                      @for($num=0; $num<=2; $num++)
                        <div class="input-file-box">
                          <div class="input-file-btn">
                            <p>ファイルを選択</p>
                            <input type="file" class="input-file">
                          </div>
                          <span>選択されていません</span>
                        </div>
                      @endfor
                    </div>
                    <!-- メール -->
                    <div class="input_fma_value mail_files">
                      <p>
                        <a class="skillsheet__email hover-thin" href="mailto:entry@engineer-route.com">entry@engineer-route.com</a>宛てにスキルシートを添付してお送りください。
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
