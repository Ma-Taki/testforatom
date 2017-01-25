@extends('front.common.layout')
@section('title', '企業の皆様へ - エンジニアルート')
@section('description', '弊社では業種・言語問わず、様々な企業様のご支援をさせて頂いております。システム開発・WEB制作に関することでお悩みがございましたら、まずはお気軽にご相談ください。')
@section('canonical', url('/company'))

@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div class="main-content company">
        <h1 class="main-content__title">企業の皆様へ</h1>
        <hr class="hr-2px-solid-5e8796">

        <div class="main-content__body">
            <div class="content__element">
                <div class="content__detail">
                    <p>弊社では業種・言語問わず、様々な企業様のご支援をさせて頂いております。</p>
                    <p>システム開発・WEB制作に関することでお悩みがございましたら、まずはお気軽にご相談ください。</p>
                    <p>エンジニアが御社へ常駐しての開発ご支援や、弊社へ持ち帰っての受託開発でのご支援などをさせて頂きます。</p>
                </div>

                @include('front.common.validation_error')
                <div class="content__info">
                    <p>必要な項目をご記入いただき、「記入内容を確認する」ボタンをクリックしてください。<span class="color-red">※</span>印の項目は入力必須です。</p>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">
                    <form method="post" action="{{ url('/company/confirm') }}">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>お問い合わせ項目<span class="color-red">※</span></p>
                            </div>
                            <div class="input_f_value">
                                <span class="selectBox">
                                    <select id="js-slctBx-contact-type" name="contact_type">
                                        <option @if(!isset($contact_type) && !old('contact_type')) selected @endif disabled value="default">選択してください</option>
@foreach(FrntUtil::COMPANY_CONTACT_TYPE as $key => $value)
    @if(isset($contact_type) && $contact_type === (string)$key)
                                        <option selected value="{{ $key }}">{{ $value }}</option>
    @elseif(old('contact_type') === (string)$key)
                                        <option selected value="{{ $key }}">{{ $value }}</option>
    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
    @endif
@endforeach
                                    </select>
                                </span>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

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
                                <p>部署名</p>
                            </div>
                            <div class="input_f_value"><!-- or　を試す -->
                                <input type="text" value="{{ isset($department_name) ? $department_name : old('department_name') }}" name="department_name" maxlength="30" placeholder="例）営業部">
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>住所</p>
                            </div>
                            <div class="input_f_value input_address">
                                <input type="text" value="{{ isset($post_num) ? $post_num : old('post_num') }}" name="post_num" placeholder="例）107-0062">
                                <input type="text" value="{{ isset($address) ? $address : old('address') }}" name="address" placeholder="例）東京都港区南青山5-4-27 Barbizon104 3F">
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>電話番号<span class="color-red">※</span></p>
                            </div>
                            <div class="input_f_value">
                                <input type="text" value="{{ isset($phone_num) ? $phone_num : old('phone_num') }}" name="phone_num" maxlength="20" placeholder="例）03-5774-5557">
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
                                <p>URL</p>
                            </div>
                            <div class="input_f_value">
                                <input type="text" value="{{ isset($url) ? $url : old('url') }}" name="url" placeholder="例）http://solidseed.co.jp/">
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
    </div><!-- END main-content  -->
</div><!-- END wrap -->
<script type="text/javascript">
    jQuery(function($){
        var $contact = $('#js-slctBx-contact-type option:selected');
        if ($contact.val() != 'default') {
            $contact.parent('select').css('color','black');
        }
        $('#js-slctBx-contact-type').change(function(){
            $(this).css('color','black');
        });
    });
</script>
@endsection
