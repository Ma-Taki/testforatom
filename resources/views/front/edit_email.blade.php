@extends('front.common.layout')
@section('title', 'メールアドレス変更 | エンジニアルート')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

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
      <a class="hover-thin" itemprop="item" href="/user">
        <span itemprop="name">マイページ</span>
      </a>
      <meta property="position" content="2">
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">メールアドレス変更</span>
      <meta property="position" content="3">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content user-reminder">
        <h1 class="main-content__title">メールアドレス変更</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">

@include('front.common.validation_error')
                <p class="content__info">
                    新しいメールアドレスを入力し、「送信」ボタンをクリックしてください。<br>
                    新しいメールアドレス宛てにメールを送信させていただきますので、メール本文に記載されたURLより、メールアドレス変更を完了してください。
                </p>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">
                    <form method="post" action="{{ url('/user/edit/email') }}">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>現在のメールアドレス</p>
                            </div>
                            <div class="input_f_value">
                                <p>{{ $user->mail }}</p>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>新しいメールアドレス</p>
                            </div>
                            <div class="input_f_value input_email">
                                <label><input type="text" name="mail" maxlength="256" value="{{ old('mail') }}" placeholder="例）info@solidseed.co.jp">（半角）</label>
                                <label><input type="text" name="mail_confirmation" maxlength="256" value="{{ old('mail_confirmation') }}" placeholder="確認のため、もう一度入力してください。">（半角）</label>
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

                        <div class="cmmn-btn">
                            <button type="submit">送　信</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
