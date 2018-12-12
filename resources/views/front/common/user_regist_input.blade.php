<?php
  use App\Libraries\HtmlUtility as HtmlUtil;
  use App\Libraries\FrontUtility as FrntUtil;
  use App\Models\Ms_prefectures;
  use App\Libraries\ModelUtility as MdlUtil;
  use Carbon\Carbon;
?>
<form method="post" name="regist-auth" action="{{ url('/user/regist/auth') }}" enctype="multipart/form-data">
  <div class="input_field fs0">
    <div class="input_f_name"><p>氏名</p></div>
    <div class="input_f_value input_name">
      <label>
        <input type="text" maxlength="15" name="last_name" value="{{ old('last_name') }}" placeholder="例) 田中">
        @if($errors->first('last_name'))
          <p class="help-block">{{$errors->first('last_name')}}</p>
        @endif
      </label>
      <label>
        <input type="text" maxlength="15" name="first_name" value="{{ old('first_name') }}" placeholder="例) 太郎">
        @if($errors->first('last_name'))
          <p class="help-block">{{$errors->first('first_name')}}</p>
        @endif
      </label>
    </div>
  </div>
  <div class="input_field fs0">
    <div class="input_f_name"><p>かな</p></div>
    <div class="input_f_value input_name">
      <label>
        <input type="text" maxlength="15" name="last_name_kana" value="{{ old('last_name_kana') }}" placeholder="例) たなか">
        @if($errors->first('last_name_kana'))
          <p class="help-block">{{$errors->first('last_name_kana')}}</p>
        @endif
      </label>
      
      <label>
        <input type="text" maxlength="15" name="first_name_kana" value="{{ old('first_name_kana') }}" placeholder="例) たろう">
        @if($errors->first('first_name_kana'))
          <p class="help-block">{{$errors->first('first_name_kana')}}</p>
        @endif
      </label>
      
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name"><p>性別</p></div>
    <div class="input_f_value input_gender">
      <label>
        <input type="radio" name="gender" @if(old('gender') == "Male") checked @endif value="Male">男性
      </label>
      <label>
        <input type="radio" name="gender" @if(old('gender') == "Female") checked @endif value="Female">女性
      </label>
      @if($errors->first('gender'))
        <p class="help-block">{{ $errors->first('gender') }}</p>
      @endif
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name"><p>メールアドレス</p></div>
    <div class="input_f_value input_email">
      <label>
        @if(isset($_GET['mail']))
          <p style="padding: .6rem;"><?php echo $_GET['mail']; ?></p>
        @else
          <input type="text" name="mail" maxlength="256" value="{{ old('email')}}" placeholder="例) info@solidseed.co.jp">
          @if($errors->first('mail'))
            <p class="help-block">{{$errors->first('mail')}}</p>
          @endif
        @endif
      </label>
    </div>
  </div>
  
  <div class="input_field fs0">
    <div class="input_f_name"><p>パスワード</p></div>
    <div class="input_f_value input_password">
      <label>
        <input type="password" maxlength="20" name="password" placeholder="6〜20文字の半角英数字記号">
        @if($errors->first('password'))
          <p class="help-block">{{$errors->first('password')}}</p>
        @endif
      </label>
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name"><p>電話番号</p></div>
    <div class="input_f_value input_phone_num">
      <label>
        <input type="text" maxlength="14" name="phone_num" value="{{ old('phone_num') }}" placeholder="例) 03-5774-5557">
        @if($errors->first('phone_num'))
          <p class="help-block">{{$errors->first('phone_num')}}</p>
        @endif
      </label>
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name"><p>お住まい</p></div>
    <div class="input_f_value input_prefecture_id">
      <label>
        <select class="slctBx_prefecture" name="prefecture_id">
          @foreach(Ms_prefectures::getNotIndexOnly() as $value)
            @if(!is_null(old('prefecture_id')))
              <option @if($value->id == old('prefecture_id')) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
            @else
              <option @if($value->id == MdlUtil::PREFECTURES_ID_TOKYO) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
            @endif
          @endforeach
        </select>
      </label>
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name"><p>生年月日</p></div>
    <div class="input_f_value input_birth">
      <label>
        <select id="js-slctBx-birth_y" class="slctBx_birth-y" name="birth_year">
          @for($year = Carbon::today()->year - 18; $year >= 1940; $year--)
            <option @if(old('birth_year') == $year) selected @endif value="{{ $year }}">{{ $year }}</option>
          @endfor
        </select>
      </label>
      <label>
        <select id="js-slctBx-birth_m" class="slctBx_birth-m" name="birth_month">
           @for($month = 1; $month <= 12; $month++)
            <option @if(old('birth_month') == $month) selected @endif value="{{ $month }}">{{ $month }}</option>
          @endfor
        </select>
      </label>
      <label>
        <select id="js-slctBx-birth_d" class="slctBx_birth-d" name="birth_day">
          @for($day = 1; $day <= 31; $day++)
            <option @if(old('birth_day') == $day) selected @endif value="{{ $day }}">{{ $day }}</option>
          @endfor
        </select>
      </label>
    </div>
  </div>

  <div class="input_field fs0">
    <div class="input_f_name_any"><p style="font-weight:bold;">規約への同意</p></div>
    <div class="input_f_value input_terms_of_agreement">
      <label>エンジニアルートへのご登録およびご利用に関する<a class="any" href="{{ url('/terms') }}" style="color:blue;font-weight:bold;">利用規約</a>と<a class="any" href="{{ url('/privacy') }}" style="color:blue;font-weight:bold;">個人情報の取り扱いについて</a>同意したうえで以下の登録ボタンをクリックしてください。</label>
    </div>
  </div>

  <div class="cmmn-btn red-btn">
    <button type="submit">規約に同意して送信する</button>
  </div>
  <p class="login-title"><a href="/login">既に会員の方はこちらからログイン</a></p>
  {{ csrf_field() }}
</form>