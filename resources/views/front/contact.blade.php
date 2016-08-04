@extends('front.common.layout')
@section('title', 'お問い合わせ - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
?>
<div class="wrap">
    <div id="contact" class="content">
        <h1 class="pageTitle">お問い合わせ</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">必要な項目をご記入いただき、「内容確認」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須です。</p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/front/contact/confirm') }}">

                <div class="inputTable">
                    <div class="inputName"><p>お名前<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('user_name') }}</p>
                        <div class="userNameTable">
                            <div class="table-row">
                                <label>性　<input type="text" value="{{ isset($user_last_name) ? $user_last_name : old('user_last_name') }}" name="user_last_name" placeholder="ソリッド"></label>
                                <label>名　<input type="text" value="{{ isset($user_first_name) ? $user_first_name : old('user_first_name') }}" name="user_first_name" placeholder="太郎"></label>
                            </div>
                            <div class="table-row">
                                <label>せい<input type="text" value="{{ isset($user_last_name_kana) ? $user_last_name_kana : old('user_last_name_kana') }}" name="user_last_name_kana" placeholder="そりっど"></label>
                                <label>めい<input type="text" value="{{ isset($user_first_name_kana) ? $user_first_name_kana : old('user_first_name_kana') }}" name="user_first_name_kana" placeholder="たろう"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>会社名</p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('company_name') }}</p>
                        <input type="text" value="{{ isset($company_name) ? $company_name : old('company_name') }}" name="company_name" maxlength="30" placeholder="例）ソリッドシード株式会社">
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>メールアドレス<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('mail') }}</p>
                        <label><input type="text" value="{{ isset($mail) ? $mail : old('mail') }}" name="mail" maxlength="256" placeholder="例）example@solidseed.co.jp"></label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>メールアドレス（確認）<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('mail') }}</p>
                        <label><input type="text" value="{{ isset($mail_confirmation) ? $mail_confirmation : old('mail_confirmation') }}" name="mail_confirmation" maxlength="256" placeholder="例）example@solidseed.co.jp"></label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>電話番号</p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('phoneNumber') }}</p>
                        <label><input type="text" value="{{ isset($phoneNumber) ? $phoneNumber : old('$phoneNumber') }}" name="$phoneNumber" placeholder="例）03-5774-5557"></label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>お問い合わせ内容<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('contactMessage') }}</p>
                        <textarea name="contactMessage" rows="10" maxlength="500" placeholder="お問い合わせ内容をご記入ください。">{{ isset($contactMessage) ? $contactMessage : old('contactMessage') }}</textarea>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="confirmBtn">
                    <div class="button">
                        <button type="submit">記入内容を確認する</button>
                    </div>
                </div>

                {{ csrf_field() }}
            </form>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
