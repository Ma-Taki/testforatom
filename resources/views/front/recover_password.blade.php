@extends('front.common.layout')
@section('title', 'エンジニアルート | パスワード再設定')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div id="recover_password" class="content">
        <h1 class="pageTitle">パスワード再設定</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="recover_password">
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
            <p class="pageInfo">◆&nbsp;下記より新しいパスワードを設定してください。</p>
            <hr class="hr-1px-dashed-333">

            <form method="post" action="{{ url('/user/recovery') }}">

                <div class="input_field fs0">
                    <div class="input_f_name">
                        <p>新しいパスワード</p>
                    </div>
                    <div class="input_f_value input_password">
                        <label><input type="password" name="new_password" maxlength="20" placeholder="*6~20文字以内の半角英数字記号"></label>
                        <label><input type="password" name="new_password_confirmation" maxlength="20" placeholder="確認のため、もう一度入力してください。"></label>
                    </div>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="commonCenterBtn">
                    <button type="submit">パスワードを変更する</button>
                </div>
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="ticket" value="{{ $ticket }}">
                {{ csrf_field() }}
            </form>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
