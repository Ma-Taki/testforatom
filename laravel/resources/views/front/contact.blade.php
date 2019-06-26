@extends('front.common.layout')
@section('title', 'お問い合わせ | エンジニアルート')
@section('description', 'エンジニアルートのお問い合わせページです。')
@section('canonical', url('/contact'))
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
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
      <span itemprop="name">お問い合わせ</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content contact">
        <h2 class="main-content__title">お問い合わせ</h2>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">

@include('front.common.validation_error')
                <div class="content__info">
                    <p>必要な項目をご記入いただき、「内容確認」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須です。</p>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">
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
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>会社名</p>
                            </div>
                            <div class="input_f_value">
                                <input type="text" value="{{ isset($company_name) ? $company_name : old('company_name') }}" name="company_name" maxlength="30" placeholder="例）ソリッドシード株式会社">
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>メールアドレス<span class="color-red">※</span></p>
                            </div>
                            <div class="input_f_value input_email">
                                <label><input type="text" value="{{ isset($mail) ? $mail : old('mail') }}" name="mail" maxlength="256" placeholder="例）info@solidseed.co.jp">（半角）</label>
                                <label><input type="text" value="{{ isset($mail_confirmation) ? $mail_confirmation : old('mail_confirmation') }}" name="mail_confirmation" maxlength="256" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>電話番号</p>
                            </div>
                            <div class="input_f_value">
                                <input type="text" value="{{ isset($phoneNumber) ? $phoneNumber : old('phoneNumber') }}" name="phoneNumber" placeholder="例）03-5774-5557">
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>お問い合わせ内容<span class="color-red">※</span></p>
                            </div>
                            <div class="input_f_value">
                                <textarea name="contactMessage" rows="10" maxlength="500" placeholder="お問い合わせ内容をご記入ください。">{{ isset($contactMessage) ? $contactMessage : old('contactMessage') }}</textarea>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="cmmn-btn">
                            <button type="submit">記入内容を確認する</button>
                        </div>

                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
