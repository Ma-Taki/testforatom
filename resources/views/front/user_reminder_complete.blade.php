@extends('front.common.layout')
@section('title', 'パスワード再設定URLの通知完了 - エンジニアルート')
@section('content')
<div class="wrap">
    <div class="main-content user-reminder-complete">
        <h1 class="main-content__title">パスワード再設定案内メール&nbsp;送信完了</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">

                <p class="content__info">
                    パスワード再設定ページのURLが記載されたメールを、登録メールアドレス宛に送信しました。<br />
                    60分以内にパスワード再設定URLにアクセスし、パスワードを再設定してください。
                </p>

                <div class="cmmn-btn">
                    <a href="{{ url('/') }}"><button>トップページへ</button></a>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
