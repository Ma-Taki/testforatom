<!-- Begin a8js_tag -->
<script src="//statics.a8.net/a8sales/a8sales.js"></script>
<span id="a8sales"></span>
<script src="//statics.a8.net/a8sales/a8sales.js"></script>
<script>
a8sales({
"pid": "s00000019499001",
"order_number": "\""+{{$id}}+"\"",
"currency": "JPY",
"items": [
{
"code": "a8",
"price": 16000,
"quantity": 1
},
],
"total_price": 16000,
});
</script>
<!-- End a8js_tag -->
<!-- Begin INDEED conversion code -->
<script type="text/javascript">
/* <![CDATA[ */
var indeed_conversion_id = '703555693215870';
var indeed_conversion_label = '';
/* ]]> */
</script>
<script type="text/javascript" src="//conv.indeed.com/applyconversion.js">
</script>
<noscript>
<img height=1 width=1 border=0 src="//conv.indeed.com/pagead/conv/703555693215870/?script=0">
</noscript>
<!-- End INDEED conversion code -->
@extends('front.common.layout')
@section('title', '新規会員登録完了 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')
@section('fbq_add', 'fbq(\'track\', \'CompleteRegistration\');')
@section('content')
<div class="wrap">
  <div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a class="hover-thin" itemprop="item" href="/">
        <span itemprop="name">エンジニアルート</span>
      </a>
      <meta itemprop="position" content="1" />
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">新規会員登録</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->
  <div class="main-content user-complete">
    <h1 class="main-content__title">会員登録完了</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <p>この度はエンジニアルートに会員登録をしていただき、誠にありがとうございます。</p>
        <div class="regist-thanks">
          <p>会員IDはご登録いただいたメールアドレスになります。</p>
          <p class="user-id">会員ID：{{  $mail or '*****************' }}</p>
          <p>パスワードと共にお忘れずに管理ください。</p>
          <div class="cmmn-btn">
            <a href="/">トップページへ</a>
          </div>
        </div>
        <div class="support">
          <p class="about-support">サポートについて</p>
          <p>疑問点・ご不明な点などございましたら、お気軽にお問い合わせください。</p>
          <div class="cmmn-btn">
            <a href="/contact">お問い合わせフォーム</a>
          </div>
        </div>
      </div>
    </div>
    <div class="invisible-pc invisible-tab">
        @include('front.common.sideInfo')
    </div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
