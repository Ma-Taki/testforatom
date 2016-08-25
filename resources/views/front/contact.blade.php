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
            <p class="pageInfo">必要な項目をご記入いただき、「内容確認」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須です。</p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/contact/confirm') }}">
                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お名前<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_name">
                        <label>性　<input type="text" value="{{ isset($last_name) ? $last_name : old('last_name') }}" name="last_name" placeholder="ソリッド"></label>
                        <label>名　<input type="text" value="{{ isset($first_name) ? $first_name : old('first_name') }}" name="first_name" placeholder="太郎"></label>
                        <label>せい<input type="text" value="{{ isset($last_name_kana) ? $last_name_kana : old('last_name_kana') }}" name="last_name_kana" placeholder="そりっど"></label>
                        <label>めい<input type="text" value="{{ isset($first_name_kana) ? $first_name_kana : old('first_name_kana') }}" name="first_name_kana" placeholder="たろう"></label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>会社名</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" value="{{ isset($company_name) ? $company_name : old('company_name') }}" name="company_name" maxlength="30" placeholder="例）ソリッドシード株式会社">
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>メールアドレス<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value input_email">
                        <label><input type="text" value="{{ isset($email) ? $email : old('email') }}" name="email" maxlength="256" placeholder="例）info@solidseed.co.jp">（半角）</label>
                        <label><input type="text" value="{{ isset($email_confirmation) ? $email_confirmation : old('email_confirmation') }}" name="email_confirmation" maxlength="256" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>電話番号</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" value="{{ isset($phoneNumber) ? $phoneNumber : old('phoneNumber') }}" name="phoneNumber" placeholder="例）03-5774-5557">
                    </div>
                </div>
                <hr class="partitionLine_03">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>お問い合わせ内容<span class="color-red">※</span></p>
                    </div>
                    <div class="input_f_value">
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
