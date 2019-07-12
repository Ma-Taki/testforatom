@extends('admin.common.layout')
@section('title', '紐付けキーワード編集')
@section('content')
<?php
use App\Libraries\HtmlUtility;
 ?>
<div class="col-md-10">
	<div class="row">
		<div class="content-box-large">
		  	<div class="panel-heading">
		  		<div class="panel-title" style="font-size:20px">記事紐付け情報編集</div>
		  	</div>
		  	<div class="panel-body">
				@if(count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
				        </ul>
				    </div>
				@endif
				{{-- error：customValidation --}}
                @if(Session::has('custom_error_messages'))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach(Session::get('custom_error_messages') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
				<div class="row">
            		<div class="col-md-4">紐付け情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
        		</div>
        		<form class="form-horizontal" role="form" method="POST" name="imageForm" action="{{ url('/admin/column-connect/modify') }}" enctype="multipart/form-data">
					<fieldset>
						<br>
						<div class="form-group">
							<label for="inputConnectID" class="col-md-2 control-label">紐付けID<font color="#FF0000">*</font></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inputConnectID" name="connect_id" maxlength="3" value="{{ HtmlUtility::setTextValueByRequest($connect->connect_id, old('connect_id')) }}" placeholder="紐付けID" >
								<span>(3桁までの数字)</span>
							</div>
			            </div>
						<div class="form-group">
							<label for="inputTitle" class="col-md-2 control-label">タイトル<font color="#FF0000">*</font></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inputTitle" name="title" maxlength="50" value="{{ HtmlUtility::setTextValueByRequest($connect->title, old('title')) }}" placeholder="記事タイトル" >
								<span>(50文字まで)</span>
							</div>
			            </div>
			            <div class="form-group">
							<label for="inputKeyword" class="col-md-2 control-label">キーワード<font color="#FF0000">*</font></label>
							 <div class="col-md-8">
                                <textarea class="form-control" id="inputKeyword" name="keyword"  placeholder="キーワード" rows="8" >{{ HtmlUtility::setTextValueByRequest( $connect->keyword, old('keyword')) }}
                                </textarea>
                                <span>(改行区切り・大文字小文字区別なし・3000文字まで)</span>
                            </div>
			            </div>
			            
			            <div class="col-md-10 text-right">
			             	<button type="submit" class="btn btn-md btn-primary">更新</button>
						    <a href="/admin/column-connect/search">
						    	<button type="button" class="btn btn-default">キャンセル</button>
						    </a>	
			            </div>
            			<input type="hidden" name="id" value="{{ $connect->id }}">
			            {{ csrf_field() }}
			      	</fieldset>
		  		</form>
			</div>
		</div>
	</div>
</div>
@endsection
