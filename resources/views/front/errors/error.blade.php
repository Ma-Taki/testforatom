@extends('front.common.layout')
@section('title', 'エンジニアルート | エラーが発生しました')

@section('content')
<div class="wrap">
    <div class="content">
        <p>{{ $message }}</p>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
