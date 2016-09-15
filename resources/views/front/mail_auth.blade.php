@extends('front.common.layout')
@section('title', 'メールアドレス認証 - エンジニアルート')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div class="main-content mail-auth">
        <h1 class="main-content__title">メールアドレス認証</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">
@include('front.common.validation_error')
{{-- SNS認証用メッセージ --}}
@if(Session::has('custom_info_messages'))
        <div class="alert alert-info">
            <ul>
    @foreach(Session::get('custom_info_messages') as $message)
                <li>{!! $message !!}</li>
    @endforeach
            </ul>
        </div>
@endif
                <div class="content__info">
                    <p>
                        入力されたメールアドレスに、認証用のメールを送信させていただきます。<br>
                        メール本文に記載されたURLより、会員登録(無料)を行ってください。
                    </p>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">
                    <form method="post" action="{{ url('/user/regist/auth') }}">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>メールアドレス</p>
                            </div>
                            <div class="input_f_value input_email">
                                <label><input type="text" name="mail" maxlength="256" value="{{ old('mail') }}" placeholder="例）info@solidseed.co.jp">（半角）</label>
                                <label><input type="text" name="mail_confirmation" maxlength="256" value="{{ old('mail_confirmation') }}" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="cmmn-btn">
                            <button type="submit">送信</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
