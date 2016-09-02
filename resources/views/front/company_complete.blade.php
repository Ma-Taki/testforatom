@extends('front.common.layout')
@section('title', 'お問い合わせ完了 - エンジニアルート')
@section('content')
<div class="wrap">
    <div id="contact" class="content">
        <h1 class="pageTitle">お問い合わせ完了</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">お問い合わせ頂きありがとうございます。</p>
            <p>弊社にてお問い合わせ内容を確認後、エンジニアルートスタッフより、折り返しご連絡差し上げます。</p>
            <p>今後ともエンジニアルートを宜しくお願い致します。</p>
            <div class="commonCenterBtn">
                <a href="{{ url('/') }}"><button>トップページへ</button></a>
            </div>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
