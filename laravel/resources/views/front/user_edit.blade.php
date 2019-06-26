@extends('front.common.layout')
@section('title', 'プロフィール変更 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

@section('content')
<?php
    use App\Models\Ms_contract_types;
    use App\Models\Ms_prefectures;
    use App\Libraries\ModelUtility as MdlUtil;
    use App\Libraries\HtmlUtility as HtmlUtil;
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
      <span itemprop="name">プロフィール変更</span>
      <meta property="position" content="3">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-edit">
    <h1 class="main-content__title">プロフィール変更</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <div class="alert_design"></div>
        @include('front.common.validation_error')
        <div class="content__info">
          <p>◆&nbsp;編集後に「変更内容を登録する」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須項目です。</p>
        </div>
        <hr class="hr-1px-dashed-333">

        <div class="content__body">
          <form method="post" name="userForm" action="{{ url('/user/edit') }}">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>お名前<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value input_name">
                <label>性　<input type="text" maxlength="15" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="ソリッド"></label>
                <label>名　<input type="text" maxlength="15" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="太郎"></label>
                <label>せい
                  <input type="text" maxlength="15" name="last_name_kana" value="{{ old('last_name_kana', $user->last_name_kana) }}" placeholder="そりっど">
                </label>
                <label>めい
                  <input type="text" maxlength="15" name="first_name_kana" value="{{ old('first_name_kana', $user->first_name_kana) }}" placeholder="たろう">
                </label>
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>性別<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value input_gender">
                <label>
                  <input type="radio" name="gender" @if(old('gender', $user->sex) == "Male") checked @endif value="Male">男性
                </label>
                <label>
                  <input type="radio" name="gender" @if(old('gender', $user->sex) == "Female") checked @endif value="Female">女性
                </label>
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>生年月日<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value input_birth">
                <label>
                  <span class="selectBox">
                    <select id="js-slctBx-birth_y" class="slctBx_birth-y" name="birth_year">
                      @for($year = Carbon::today()->year - 18; $year >= 1940; $year--)
                        <option @if(old('birth_year', $user->birth_date->year ) == $year) selected @endif value="{{ $year }}">
                          {{ $year }}
                        </option>
                      @endfor
                    </select>
                  </span>
                  年
                </label>
                <label>
                  <span class="selectBox">
                    <select id="js-slctBx-birth_m" class="slctBx_birth-m" name="birth_month">
                      @for($month = 1; $month <= 12; $month++)
                        <option @if(old('birth_month', $user->birth_date->month) == $month) selected @endif value="{{ $month }}">
                          {{ $month }}
                        </option>
                      @endfor
                    </select>
                  </span>
                  月
                </label>
                <label>
                  <span class="selectBox">
                    <select id="js-slctBx-birth_d" class="slctBx_birth-d" name="birth_day">
                      @for($day = 1; $day <= 31; $day++)
                        <option @if(old('birth_day', $user->birth_date->day) == $day) selected @endif value="{{ $day }}">
                          {{ $day }}
                        </option>
                      @endfor
                    </select>
                  </span>
                  日
                </label>
              </div>
            </div>
            <hr class="clear hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>最終学歴</p>
              </div>
              <div class="input_f_value">
                <input type="text" name="education" maxlength="50" value="{{ old('education', $user->education_level) }}" placeholder="例）〇〇大学〇〇学部〇〇学科 卒">
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>国籍</p>
              </div>
              <div class="input_f_value">
                <input type="text" name="country" maxlength="20" value="{{ old('country', $user->nationality) }}" placeholder="例）日本">
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>希望の契約形態</p>
              </div>
              <div class="input_f_value input_contract">
                @foreach(Ms_contract_types::getActual() as $value)
                  <label>
                    <input type="checkbox" @if(in_array($value->id, old('contract_types', HtmlUtil::convertModelListToIdList($user->contractTypes)))) checked @endif name="contract_types[]" value="{{ $value->id }}">{{ $value->name }}
                  </label>
                @endforeach
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>住所（都道府県）<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value">
                <label>
                  <span class="selectBox">
                    <select class="slctBx_prefecture" name="prefecture_id">
                      @foreach(Ms_prefectures::getNotIndexOnly() as $value)
                        @if(!empty(old('prefecture_id', $user->prefecture_id)))
                          <option @if($value->id == old('prefecture_id', $user->prefecture_id)) selected @endif value="{{ $value->id }}">
                            {{ $value->name }}
                          </option>
                        @else
                          <option @if($value->id == MdlUtil::PREFECTURES_ID_TOKYO) selected @endif value="{{ $value->id }}">
                            {{ $value->name }}
                          </option>
                        @endif
                      @endforeach
                    </select>
                  </span>
                </label>
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>最寄り駅</p>
              </div>
              <div class="input_f_value">
                <input type="text" maxlength="30" name="station" value="{{ old('station', $user->station) }}" placeholder="例）〇〇線〇〇駅">
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>電話番号<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value input_email">
                <label>
                  <input type="text" maxlength="14" name="phone_num" value="{{ old('phone_num', $user->tel) }}" placeholder="例）03-5774-5557">
                  （半角 ※ハイフン付き）
                </label>
              </div>
            </div>
            <hr class="hr-1px-dashed-333">

            <div class="input_field fs0">
              <div class="input_f_name">
                <p>メールアドレス<span class="color-red">※</span></p>
              </div>
              <div class="input_f_value input_email">
                <p>{{ $user->mail }}</p>
                <p class="email-change-text">変更する場合は<a class="hover-thin" href="/user/edit/email">コチラ</a></p>
                <!--
                <label><input type="text" maxlength="256" name="email" value="{{ old('email', $user->mail) }}" placeholder="例）info@solidseed.co.jp">（半角）</label>
                <label><input type="text" maxlength="256" name="email_confirmation" value="{{ old('email_confirmation') }}" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                -->
              </div>
            </div>
            <hr class="hr-1px-dashed-333">
            <div class="input_field fs0">
              <div class="input_f_name">
                <p>メールマガジン</p>
              </div>
              <div class="input_f_value input_magazine">
                <label>
                  <input type="checkbox" @if(old('magazine_flag', $user->magazine_flag)) checked @endif name="magazine_flag_temp">配信を希望する
                </label>
              </div>
            </div>
            <hr class="hr-1px-dashed-333">
              <div class="input_field">
                <div class="input_f_name">
                  <p>履歴書・職務経歴書・スキルシート<span class="color-red">※</span></p>
                </div>
                <div class="input_f_value">
                  <!-- アップロード済スキルシートを表示 -->
                  @if($user->skillsheet_upload)
                    <div class="uploadfile_edit">
                      @for($num=0; $num<=2; $num++)
                        <?php $num_plus = $num + 1; $skillsheet = 'skillsheet_'.$num_plus; ?>
                        @if($user->$skillsheet)
                          <div class="uploadfile_edit_frame">
                            <div class="uploadfile_img">
                              <img src="/front/images/uploadfile.png" width="13" height="13" alt="アップロードファイル">
                            </div>
                            <p id="<?php echo 'filename'.$num;?>" data-file="{{$user->$skillsheet}}">{{$user->$skillsheet}}</p>
                            <div class="download_btn">
                              <a href="/user/skillsheet/download/oneByOne?id={{$user->id}}&skillsheet=<?php echo $num_plus;?>">
                                <img src="/front/images/download.png" style="width: initial;" alt="ダウンロード" width="28" height="28">
                              </a>
                            </div>
                            <input type="image" class="delete_btn" style="width: initial;" src="/front/images/delete.png" alt="削除" width="28" height="28">
                          </div>
                        @endif
                      @endfor
                    </div>
                    <hr class="hr-1px-dashed-333">
                  @endif 
                  <div class="input_fresume_type_value input_resume_type_edit"　@if(2<$file_count) style="display:none;"@endif >
                    <div class="spNone">
                      <label>
                        <!-- ~640px 641px~1024pxのときはドラッグ&ドロップ非表示 -->
                        <input type="radio" name="file_type" id="file_ddrop" value="user_edit_dd">ドラッグ&amp;ドロップ
                      </label>
                    </div>
                    <label>
                      <input type="radio" name="file_type" id="file_explorer" value="user_edit_fe">ファイルを選択
                    </label>
                    <label class="file_mail">
                      <input type="radio" name="file_type" id="file_mail" value="user_edit_fma">メール
                    </label>
                  </div>
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
                    @for($num=$file_count; $num<=2; $num++)
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
                  <!-- 説明文 -->
                  <div class="input_f_value resume-note-edit" @if(2<$file_count) style="display:none;" @endif >
                    <p>・スキルシートのフォーマットは<a href="/entry/download" class="hover-thin">こちらから</a>ダウンロードできます。</p>
                    <p>
                      ・アップロード可能なファイル形式はMicrosoft Excel(xls,xlsx)、Word(doc,docx)、PowerPoint(ppt,pptx)、PDF(pdf)です。<br>
                      ・アップロードは合計3ファイルまでです。<br>
                      ・1つのファイルサイズは1MB以内です。<br>
                      ・上記以外のファイルはメールで提出してください。<br>
                    </p>
                  </div>
                  <div class="input_f_value resume-note" @if($file_count<=2) style="display:none;" @endif >
                    <p>
                      ・アップロードは合計3ファイルまでです。<br>
                      ・ファイルを削除後アップロードしてください。
                    </p>
                  </div>
                </div>
              </div>
            <hr class="hr-1px-dashed-333">
            <div class="cmmn-btn">
              <button type="submit" id="confirmBtn">変更内容を登録する</button>
            </div>
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{ csrf_field() }}
          </form>
        </div>
        <script type="text/javascript">
          $('form[name="userForm"]').submit(function(){
            var year = $('#js-slctBx-birth_y').val();
            var month = $('#js-slctBx-birth_m').val();
            var day = $('#js-slctBx-birth_d').val();
            var $magazine_flag = $('<input />').attr('type', 'hidden').attr('name', 'magazine_flag');
            if ($('input[name="magazine_flag_temp"]').prop('checked')) {
                $magazine_flag.attr('value', 1).appendTo($(this));
            } else {
                $magazine_flag.attr('value', 0).appendTo($(this));
            }
            $('<input />')
              .attr('type', 'hidden')
              .attr('name', 'birth')
              .attr('value', year+'/'+month+'/'+day)
              .appendTo($(this));
          });
        </script>
      </div>
    </div>
  </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
