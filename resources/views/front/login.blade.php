@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div id="login" class="content">
        <h1 class="pageTitle">ログイン</h1>
        <hr class="partitionLine_02">
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
        <div class="loginInr">
            <form action="/login" method="post">
                <div class="login_input fs0">
                    <div class="input_field">
                        <p>メールアドレス</p>
                        <input type="text" name="email" value="{{ old('email')}}">
                    </div>
                    <div class="input_field">
                        <p>パスワード</p>
                        <input type="password" name="password">
                    </div>
                </div>
                <div class="infoText">
                    <p>パスワードを忘れた方は<a href="/user/reminder">コチラ</a></p>
                    <p>新規会員登録は<a href="/user/regist">コチラ</a></p>
                </div>
                <div class="commonCenterBtn">
                    <button type="submit">ログイン</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
        <hr class="partitionLine_03 invisible-sp">
        <div class="contentInr">
            <p>ログインすると以下の機能を使用できるようになります。</p>
            <ul>
                <li>・案件へのエントリー</li>
                <li>・会員情報の変更</li>
            </ul>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
