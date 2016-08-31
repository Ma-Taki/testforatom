@extends('front.common.layout')
@section('title', 'プロフィール変更 - エンジニアルート')
@section('content')
<?php
    use App\Models\Ms_contract_types;
    use App\Models\Ms_prefectures;
    use App\Libraries\ModelUtility as MdlUtil;
    use App\Libraries\HtmlUtility as HtmlUtil;
    use Carbon\Carbon;
 ?>
<div class="wrap">
    <div id="user_edit" class="content">
        <h1 class="pageTitle">プロフィール変更</h1>
        <hr class="partitionLine_02">
        <div class="user_edit">
{{-- error：validation --}}
@if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
    @endforeach
                </ul>
            </div>
@endif
{{-- error：custom --}}
@if(count($errors) == 0)
    @if(Session::has('custom_error_messages'))
            <div class="alert alert-danger">
                <ul>
        @foreach(Session::get('custom_error_messages') as $message)
                    <li>{{ $message }}</li>
        @endforeach
                </ul>
            </div>
    @endif
@endif
            <p class="pageInfo">◆&nbsp;編集後に「変更内容を登録する」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須項目です。</p>
            <hr class="partitionLine_01">

            <form method="post" name="userForm" action="{{ url('/user/edit') }}">
                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お名前<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_name">
                        <label>性　<input type="text" maxlength="15" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="ソリッド"></label>
                        <label>名　<input type="text" maxlength="15" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="太郎"></label>
                        <label>せい<input type="text" maxlength="15" name="last_name_kana" value="{{ old('last_name_kana', $user->last_name_kana) }}" placeholder="そりっど"></label>
                        <label>めい<input type="text" maxlength="15" name="first_name_kana" value="{{ old('first_name_kana', $user->first_name_kana) }}" placeholder="たろう"></label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>性別<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_gender">
                        <label><input type="radio" name="gender" @if(old('gender', $user->sex) == "Male") checked @endif value="Male">男性</label>
                        <label><input type="radio" name="gender" @if(old('gender', $user->sex) == "Female") checked @endif value="Female">女性</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>生年月日<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_birth">
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_y" name="birth_year">
@for($year = Carbon::today()->year - 18; $year >= 1940; $year--)
                                    <option @if(old('birth_year', $user->birth_date->year ) == $year) selected @endif value="{{ $year }}">{{ $year }}</option>
@endfor
                                </select>
                            </span>
                        年</label>
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_m" name="birth_month">
@for($month = 1; $month <= 12; $month++)
                                    <option @if(old('birth_month', $user->birth_date->month) == $month) selected @endif value="{{ $month }}">{{ $month }}</option>
@endfor
                                </select>
                            </span>
                        月</label>
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_birth_d" name="birth_day">
@for($day = 1; $day <= 31; $day++)
                                    <option @if(old('birth_day', $user->birth_date->day) == $day) selected @endif value="{{ $day }}">{{ $day }}</option>
@endfor
                                </select>
                            </span>
                        日</label>
                    </div>
                </div>
                <hr class="clear partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>最終学歴</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" name="education" maxlength="50" value="{{ old('education', $user->education_level) }}" placeholder="例）〇〇大学〇〇学部〇〇学科 卒">
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>国籍</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" name="country" maxlength="20" value="{{ old('country', $user->nationality) }}" placeholder="例）日本">
                    </div>
                </div>
                <hr class="partitionLine_01">

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
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>住所（都道府県）<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
                        <label>
                            <span class="selectBox">
                                <select id="slctBx_prefecture" name="prefecture_id">
@foreach(Ms_prefectures::getNotIndexOnly() as $value)
    @if(!empty(old('prefecture_id', $user->prefecture_id)))
                                    <option @if($value->id == old('prefecture_id', $user->prefecture_id)) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
    @else
                                    <option @if($value->id == MdlUtil::PREFECTURES_ID_TOKYO) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
    @endif
@endforeach
                                </select>
                            </span>
                        </label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>最寄り駅</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" maxlength="30" name="station" value="{{ old('station', $user->station) }}" placeholder="例）〇〇線〇〇駅">
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>メールアドレス<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="text" maxlength="256" name="email" value="{{ old('email', $user->mail) }}" placeholder="例）info@solidseed.co.jp">（半角）</label>
                        <label><input type="text" maxlength="256" name="email_confirmation" value="{{ old('email_confirmation') }}" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>電話番号<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="text" maxlength="14" name="phone_num" value="{{ old('phone_num', $user->tel) }}" placeholder="例）03-5774-5557">（半角 ※ハイフン付き）</label>
                        <label><input type="text" maxlength="14" name="phone_num_confirmation" value="{{ old('phone_num_confirmation') }}" placeholder="確認のため、もう一度入力してください。">（半角 ※ハイフン付き）</label>
                    </div>
                </div>
                <hr class="partitionLine_01">

            <div class="commonCenterBtn">
                <button type="submit" id="confirmBtn">変更内容を登録する</button>
            </div>
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{ csrf_field() }}
            </form>
        </div>
        <script type="text/javascript">
            $('form[name="userForm"]').submit(function(){
                var year = $('#slctBx_birth_y').val();
                var month = $('#slctBx_birth_m').val();
                var day = $('#slctBx_birth_d').val();
                $('<input />')
                    .attr('type', 'hidden')
                    .attr('name', 'birth')
                    .attr('value', year+'/'+month+'/'+day)
                    .appendTo($(this));
            });
        </script>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection