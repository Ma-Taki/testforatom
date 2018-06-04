@extends('admin.common.layout')
@section('title', 'お知らせ詳細')
@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">お知らせ詳細</div>
			</div>
		  	<div class="panel-body">
				@foreach($detail as $news)
	                <legend>
	                	<div class="panel-title">{{ $news->release_date->format('Y/m/d') }}　{{ $news->title }}</div>
	                </legend>
             		<div>
             			<p>{{ $news->contents }}</p>
             		</div>
		       	@endforeach
		    </div>
        </div>
    </div>
</div>
@endsection