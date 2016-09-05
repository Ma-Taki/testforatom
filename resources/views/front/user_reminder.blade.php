@extends('front.common.layout')
@section('title', 'パスワード再設定URLの通知 - エンジニアルート')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div id="reminder" class="content">
        <h1 class="pageTitle">パスワード再設定URLの通知</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="user_reminder">
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
            <p class="pageInfo">
                登録メールアドレスを入力し、「送信」ボタンをクリックしてください。<br>
                登録メールアドレス宛に、パスワード再設定URLを記載したメールを送信します。
            </p>
            <hr class="hr-1px-dashed-333">

            <form method="post" action="{{ url('/user/reminder') }}">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>メールアドレス</p>
                    </div>
                    <div class="input_f_value">
                        <input type="text" name="mail" maxlength="256" value="{{ old('mail') }}" placeholder="">
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="commonCenterBtn">
                    <button type="submit">送信</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
