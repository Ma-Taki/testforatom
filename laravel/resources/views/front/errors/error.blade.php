@extends('front.common.layout')
@section('title', 'エラーが発生しました | エンジニアルート')
@section('noindex', 'true')

@section('content')
<div class="wrap">
  <div class="main-content error">
    <div class="main-content__body">
      <div class="content__element">
        <div class="content__body">
          <p>{{ $message }}</p>
        </div>
      </div>
    </div>
  </div><!-- END .main-content -->
</div><!-- END .wrap -->
@endsection
