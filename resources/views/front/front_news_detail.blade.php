@extends('front.common.layout')
@section('title', 'エンジニアルートとは | エンジニアルート')
@section('description', 'Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、 それぞれの方に合った案件検索から、カウンセリング、プロジェクトの終了まで 全てのプロセスを徹底的にサポート致します。')
@section('canonical', url('/front-news/detail'))

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
            <span itemprop="name">お知らせ</span>
            <meta property="position" content="2">
        </span>
    </div>
  <!-- END .breadcrumbs -->

    <div class="main-content about">
        <div class="main-content-left">
            <h2 class="main-content__title">お知らせ</h2>
            <hr class="hr-2px-solid-5e8796">
            <div class="main-content__body">
                <div class="content__element">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="content-box-large">
                    		  	<div class="panel-body">
                    				@foreach($detail as $news)
                    	                <legend>
                    	                	<h3 class="news-title">{{ $news->release_date->format('Y/m/d') }}　{{ $news->title }}</h3>
                    	                </legend>
                                 		<div>
                                 			<p>{!! nl2br(e($news->contents)) !!}</p>
                                 		</div>
                    		       	@endforeach
                        		</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- END main-content-left -->

    <div class="main-content-right">
      @include('front.common.sideInfo')
    </div><!-- END main-content-right -->
    <div class="clear"></div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection