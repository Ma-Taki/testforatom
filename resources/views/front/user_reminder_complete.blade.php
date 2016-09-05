@extends('front.common.layout')
@section('title', 'パスワード再設定URLの通知完了 - エンジニアルート')
@section('content')
<div class="wrap">
    <div id="user_reminder_complete" class="content">
        <h1 class="pageTitle">パスワード再設定案内メール&nbsp;送信完了</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="user_reminder_complete">
            <p class="pageInfo">
                パスワード再設定ページのURLが記載されたメールを、登録メールアドレス宛に送信しました。<br />
                60分以内にパスワード再設定URLにアクセスし、パスワードを再設定してください。
            </p>
            <div class="commonCenterBtn">
                <a href="{{ url('/') }}"><button>トップページへ</button></a>
            </div>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
