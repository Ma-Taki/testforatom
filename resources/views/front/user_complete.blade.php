@extends('front.common.layout')
@section('content')
<?php
    use App\Models\Ms_contract_types;
    use App\Models\Ms_prefectures;
    use App\Libraries\ModelUtility as MdlUtil;
    use Carbon\Carbon;
 ?>
<div class="wrap">
    <div id="user_complete" class="content">
        <h1 class="pageTitle">会員登録完了</h1>
        <hr class="partitionLine_02">
        <div class="contentInr alignCenter">
            <p>この度はエンジニアルートに会員登録をしていただき、誠にありがとうございます。</p>
            <p>会員IDはご登録いただいたメールアドレスになります。
            <div class="user_id">
                <p>会員ID：y.suzuki@solidseed.co.jp</p>
            </div>
            <p>パスワードと共にお忘れずに管理ください。</p>
            <div class="commonCenterBtn">
                <a href="/"><button>トップページへ</button></a>

            <div class="support">
                <p>サポートについて</p>
                <p>疑問点・ご不明な点などございましたら、お気軽にお問い合わせください。
                <div class="commonCenterBtn">
                    <a href="/contact"><button>お問い合わせフォーム</button></a>
            </div>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
