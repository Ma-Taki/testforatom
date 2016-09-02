@extends('front.common.layout')
@section('title', '退会 - エンジニアルート')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
<div class="wrap">
    <div id="user_delete" class="content">
        <h1 class="pageTitle">退会</h1>
        <hr class="partitionLine_02">
        <div class="user_delete">
            <p class="pageInfo">
                「退会する」ボタンをクリックで、エンジニアルートから退会を行います。<br>
                退会を行うことで、案件へのエントリーができなくなります。
            </p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/user/delete') }}">
                <div class="commonCenterBtn">
                    <a href="/user"><button type="button">キャンセル</button></a>
                    <button type="submit">退会する</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
