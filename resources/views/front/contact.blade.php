@extends('front.common.layout')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
?>
<div class="wrap">
    <div id="contact" class="content">
        <h1 class="pageTitle">お問い合わせ</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">以下のフォームに必要な情報を入力してください。※印の項目は入力必須です。</p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/front/contact/confirm') }}">

                <div class="inputTable">
                    <div class="inputName"><p>氏名<span class="color-red">※</span></p></div>
                    <div class="inputField">
                            <p class="errorMessage">{{ $errors->first('user_name') }}</p>
                            <input type="text" value="{{ isset($user_name) ? $user_name : old('user_name') }}" name="user_name" maxlength="30" placeholder="ソリッド　太郎">
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>会社名</p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('company_name') }}</p>
                        <input type="text" value="{{ isset($company_name) ? $company_name : old('company_name') }}" name="company_name" maxlength="30" placeholder="ソリッドシード株式会社">
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>メールアドレス<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('mail') }}</p>
                        <label><input type="text" value="{{ isset($mail) ? $mail : old('mail') }}" name="mail" maxlength="256" placeholder="info@solidseed.co.jp">（半角）</label>
                        <p class="mailConfirmText">確認のため、もう一度入力してください</p>
                        <label><input type="text" value="{{ isset($mail_confirmation) ? $mail_confirmation : old('mail_confirmation') }}" name="mail_confirmation" maxlength="256" placeholder="info@solidseed.co.jp">（半角）</label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="inputTable">
                    <div class="inputName"><p>お問い合わせ内容<span class="color-red">※</span></p></div>
                    <div class="inputField">
                        <p class="errorMessage">{{ $errors->first('contactMessage') }}</p>
                        <p>500文字以内で入力してください。</p>
                        <textarea name="contactMessage" rows="10" maxlength="500">{{ isset($contactMessage) ? $contactMessage : old('contactMessage') }}</textarea>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="confirmBtn">
                    <div class="button">
                        <button type="submit">入力内容の確認</button>
                    </div>
                </div>

                {{ csrf_field() }}
            </form>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
