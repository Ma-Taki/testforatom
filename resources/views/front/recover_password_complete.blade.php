@extends('front.common.layout')
@section('title', 'パスワード再設定完了 - エンジニアルート')
@section('content')
<div class="wrap">
    <div id="recover_password_complete" class="content">
        <h1 class="pageTitle">パスワード再設定完了</h1>
        <hr class="partitionLine_02">
        <div class="recover_password_complete">
            <p class="pageInfo wsnw">
                パスワードを再設定致しました。<wbr>ログイン画面よりログインしてください。
            </p>
            <div class="commonCenterBtn">
                <a href="{{ url('/login') }}"><button>ログイン画面</button></a>
            </div>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
