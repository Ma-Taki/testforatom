@extends('front.common.layout')
@section('title', 'エラーが発生しました - エンジニアルート')

@section('content')
<div class="wrap">
    <div class="content">
        <p>{{ $message }}</p>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
