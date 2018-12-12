@extends('front.common.layout')
@section('title', '新規登録 | エンジニアルート')
@section('description', 'エンジニアルートの新規会員登録ページです。')
@section('canonical', url('/user/regist/auth'))
@section('content')
<?php
  use App\Libraries\HtmlUtility as HtmlUtil;
  use App\Libraries\FrontUtility as FrntUtil;
  use App\Models\Ms_prefectures;
  use App\Libraries\ModelUtility as MdlUtil;
  use Carbon\Carbon;
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
      <span itemprop="name">新規登録</span>
      <meta property="position" content="2">
    </span>
  </div><!-- END .breadcrumbs -->
  <div class="main-content mail-auth">
    <h1 class="main-content__title">新規登録</h1>
    <hr class="hr-2px-solid-5e8796">
    <div class="main-content__body">
      <div class="content__element">
        <div class="alert_design"></div>
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
          {{-- SNS認証用メッセージ --}}
          @if(Session::has('custom_info_messages'))
            <div class="alert alert-info">
              <ul>
                @foreach(Session::get('custom_info_messages') as $message)
                  <li>{!! $message !!}</li>
                @endforeach
              </ul>
            </div>
          @endif
    
        <div class="content__body">
          <div class="sing_up">
            <div class="regist__element">
              <div class="regist__sns">
                <div class="regist__sns-text-contents">
                  <hr class="hr-1px-dashed-333 invisible-pc invisible-tab">
                  <div class="regist-time"><p>簡単<span class="regist-time-num">3</span>分登録</p></div>
                  <div class="regist__sns-text">
                    <h2 class="regist__title">外部アカウントで認証</h2>
                    <p>ユーザーの許可なく投稿することはありません</p>
                  </div>
                </div><!-- END regist__sns-text-contents -->
                <div class="regist__sns-btn-contents">
                  <div class="regist__sns-btn">
                    <a href="/login/sns/github?func=regist" class="regist__github">
                      <i class="fab fa-github"></i>GitHubで登録
                    </a>
                  </div>
                  <div class="regist__sns-btn">
                    <a href="/login/sns/facebook?func=regist" class="regist__facebook">
                      <i class="fab fa-facebook-f"></i>Facebookで登録
                    </a>
                  </div>
                  <div class="regist__sns-btn">
                    <a href="/login/sns/twitter?func=regist" class="regist__twitter">
                      <i class="fab fa-twitter"></i>Twitterで登録
                    </a>
                  </div>
                </div><!-- END regist__sns-btn-contents -->
              </div><!-- END regist__sns -->
              <div class="content__body">
                @include('front.common.user_regist_input')
              </div><!-- END content__body -->
            </div><!-- END regist__element -->
            <p class="regist-flow-title">無料会員サポート登録と今後の流れ</p>
          </div><!-- END sing_up -->
          <div class="counselor">
            <img border="0" src="../../front/images/counselor.jpg" srcset="../../front/images/counselor.jpg 1x,../../front/images/counselor@2x.jpg 2x" alt="counselor">
          </div>
          <div class="regist-flow">
            <img border="0" src="../../front/images/following.jpg"  srcset="../../front/images/following.jpg 1x,../../front/images/following@2x.jpg 2x" alt="following">
            <p>当フォームよりご登録いただいた後、担当者よりご連絡いたします。一対一のカウンセリングに基づき、スキルやキャリアプランなどのご要望をお伺いし、ピッタリの案件をご提案します。ご参画中のご相談・節税対策などサポートも万全です。<br>
              <span style="color:#D46363;font-weight:bold;">案件獲得までには早ければ１〜３日、平均的に２週間以内には複数案件から選べる状況になっています。なお、独立の相談のみでも承っております。</span></p>
          </div>
        </div><!-- END content__body -->
      </div><!-- END content__element -->
    </div><!-- END main-content__body -->
  </div><!-- END main-content mail-auth -->
</div><!-- END WRAP -->
<script type="text/javascript">
  $('form[name="regist-auth"]').submit(function(){
  var year = $('#js-slctBx-birth_y').val();
  var month = $('#js-slctBx-birth_m').val();
  var day = $('#js-slctBx-birth_d').val();
  var $magazine_flag = $('<input />').attr('type', 'hidden').attr('name', 'magazine_flag');
  if ($('input[name="magazine_flag_temp"]').prop('checked')) {
  $magazine_flag.attr('value', 1).appendTo($(this));
  } else {
  $magazine_flag.attr('value', 0).appendTo($(this));
  }
  $('<input />')
  .attr('type', 'hidden')
  .attr('name', 'birth')
  .attr('value', year+'/'+month+'/'+day)
  .appendTo($(this));
  });
  </script>
@endsection
