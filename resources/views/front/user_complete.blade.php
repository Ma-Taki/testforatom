@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div id="user_complete" class="content">
        <h1 class="pageTitle">会員登録完了</h1>
        <hr class="partitionLine_02">
        <div class="contentInr alignCenter">
            <p>この度はエンジニアルートに会員登録をしていただき、誠にありがとうございます。</p>
            <div class="regist_thanks">
                <p>会員IDはご登録いただいたメールアドレスになります。</p>
                <p class="user_id">会員ID：{{  $email or 'hogehoge@solidseed.co.jp' }}</p>
                <p>パスワードと共にお忘れずに管理ください。</p>
                <div class="commonCenterBtn">
                    <a href="/"><button>トップページへ</button></a>
                </div>
            </div>
            <div class="support">
                <p class="about_support">サポートについて</p>
                <p>疑問点・ご不明な点などございましたら、お気軽にお問い合わせください。
                <div class="commonCenterBtn">
                    <a href="/contact"><button>お問い合わせフォーム</button></a>
                </div>
            </div>
        </div>
        <div class="invisible-pc invisible-tab">
            @include('front.common.sideInfo')
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
