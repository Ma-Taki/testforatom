@extends('admin.common.layout')
@section('title', '画像編集')
@section('content')
<?php
use App\Libraries\HtmlUtility;
use App\Models\Tr_slide_images;
 ?>
<div class="col-md-10">
	<div class="row">
		<div class="content-box-large">
		  	<div class="panel-heading">
		  		<div class="panel-title" style="font-size:20px">画像編集</div>
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
            		<div class="col-md-4">画像情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
        		</div>
        		<form class="form-horizontal" role="form" method="POST" name="imageForm" action="{{ url('/admin/slide/modify') }}" enctype="multipart/form-data">
					<fieldset>
						<br>
			            <div class="form-group">
							<label class="col-md-2 control-label">現在の画像</label>
							<div class="col-sm-8">
								<p>
			        				<img src="{{ asset('/front/images/slide/'. $image->id .'.jpg') }}?<?php echo date("YmdHis");?>" width="80%" height="80%" >
								</p>
							</div>
			            </div>
			            <div class="form-group">
							<label for="inputTitle" class="col-md-2 control-label">タイトル (alt属性)<font color="#FF0000">*</font></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inputTitle" name="image_title" maxlength="30" value="{{ HtmlUtility::setTextValueByRequest($image->title, old('image_title')) }}" placeholder="タイトル (alt属性)" >
							</div>
			            </div>
			            <div class="form-group">
			            	<label for="inputLink" class="col-md-2 control-label">リンク<font color="#FF0000">*</font></label>
			            	<div class="col-sm-8">
			                	<input type="text" class="form-control" id="inputLink" name="image_link" maxlength="200" value="{{ HtmlUtility::setTextValueByRequest($image->link, old('image_link')) }}" placeholder="リンク" >
			              	</div>
			            </div>
			            <div class="form-group">
			            	<label for="inputSort" class="col-md-2 control-label">表示順<font color="#FF0000">*</font></label>
			            	<div class="col-sm-8">
                  				<span class="selectBox">
			                		<select id="js-slctBx-birth_y" class="form-control" name="image_sort">
										@for($sortNum = 1; $sortNum <= $sortMax; $sortNum++)
                    						<option @if(old('image_sort',$image->sort_order) == $sortNum) selected @endif value="{{ $sortNum }}">
                    							{{ $sortNum }}
                    						</option>
										@endfor
                   					</select>
                   				</span>
                   			</div>
			            </div>
			            <div class="form-group">
                            <label for="exampleInputFile" class="col-md-2 control-label">変更後の画像</label>
                            <div class="col-sm-8">
                                <input type="file" name="image_file" id="exampleInputFile">
                                <p style="">拡張子: jpg　大きさ: 1000 × 320</p>
                            </div>
                        </div>
			            <div class="form-group">
							<label for="inputStatus" class="col-md-2 control-label">ステータス</label>
							<div class="col-sm-8">
								<p style="padding:6px 0 0 0;">{{ $image->delete_flag ? '非表示' : '表示' }}</p>
							</div>
			            </div>
			            <div class="col-md-10 text-right">
			             	<button type="submit" class="btn btn-md btn-primary">更新</button>
						    <a href="/admin/slide/list">
						    	<button type="button" class="btn btn-default">キャンセル</button>
						    </a>	
			            </div>
            			<input type="hidden" name="id" value="{{ $image->id }}">
			            {{ csrf_field() }}
			      	</fieldset>
		  		</form>
			</div>
		</div>
	</div>
</div>
@endsection
