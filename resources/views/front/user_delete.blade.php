@extends('front.common.layout')
@section('title', '退会 - エンジニアルート')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div class="main-content user-delete">
        <h1 class="main-content__title">退会</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">

                <div class="content__info">
                    <p>
                        「退会する」ボタンをクリックで、エンジニアルートから退会を行います。<br>
                        退会を行うことで、案件へのエントリーができなくなります。
                    </p>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">

                    <form method="post" action="{{ url('/user/delete') }}">
                        <div class="commonCenterBtn">
                            <a href="/user"><button type="button">キャンセル</button></a>
                            <button type="submit">退会する</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
