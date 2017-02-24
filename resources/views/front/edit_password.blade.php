@extends('front.common.layout')
@section('title', 'エンジニアルート | パスワード変更')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\FrontUtility as FrntUtil;
?>
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
      <span itemprop="name">パスワード変更</span>
      <meta property="position" content="3">
    </span>
  </div>
  <!-- END .breadcrumbs -->

    <div class="main-content edit-password">
        <h1 class="main-content__title">パスワード変更</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">

                @include('front.common.validation_error')

                <div class="content__info">
                    <p>◆&nbsp;編集後に「パスワードを変更する」ボタンをクリックしてください。</p>
                </div>
                <hr class="hr-1px-dashed-333">

                <div class="content__body">
                    <form method="post" action="{{ url('/user/edit/password') }}">

                        <div class="input_field fs0">
                            <div class="input_f_name">
                                <p>現在のパスワード</p>
                            </div>
                            <div class="input_f_value">
                                <input type="password" name="old_password" maxlength="20" placeholder="現在使用しているパスワードを入力して下さい。">
                            </div>
                        </div>
                        <hr class="hr-1px-dashed-333">

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

                        <div class="cmmn-btn">
                            <button type="submit">パスワードを変更する</button>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
