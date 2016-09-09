@extends('front.common.layout')
@section('title', '認証メール送信完了 - エンジニアルート')
@section('content')
<div class="wrap">
    <div class="main-content mail-auth-complete">
        <h1 class="main-content__title">認証メール&nbsp;送信完了</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">
                <p>
                    会員登録ページのURLが記載されたメールを、入力されたメールアドレス宛に送信しました。<br />
                    24時間以内にURLにアクセスし、会員登録を完了してください。
                </p>
                <div class="cmmn-btn">
                    <a href="{{ url('/') }}"><button>トップページへ</button></a>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
