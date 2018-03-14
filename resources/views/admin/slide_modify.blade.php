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
		  		<div class="panel-title" style="font-size:20px">画像情報</div>
		  	</div>
		  	<div class="panel-body">
		  		<form class="form-horizontal" role="form" method="POST" name="imageForm" action="{{ url('/admin/slide/modify') }}">
					@if(count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
					        </ul>
					    </div>
					@endif
					<fieldset>
						<legend style="font-size:16px">画像情報</legend>
			            <div class="form-group">
							<label class="col-md-2 control-label">画像</label>
							<div class="col-sm-8">
								<p>
			        				<img src="{{ asset('/front/images/slide/'. $image->id .'.jpg') }}" width="80%" height="80%" >		   
								</p>
							</div>
			            </div>
			            <div class="form-group">
							<label for="inputTitle" class="col-md-2 control-label">タイトル (alt属性)</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inputTitle" name="image_title" maxlength="30" value="{{ HtmlUtility::setTextValueByRequest($image->title, old('image_title')) }}" placeholder="タイトル (alt属性)" >
							</div>
			            </div>
			            <div class="form-group">
			            	<label for="inputLink" class="col-md-2 control-label">リンク</label>
			            	<div class="col-sm-8">
			                	<input type="text" class="form-control" id="inputLink" name="image_link" maxlength="30" value="{{ HtmlUtility::setTextValueByRequest($image->link, old('image_link')) }}" placeholder="リンク" >
			              	</div>
			            </div>
			            <div class="form-group">
			            	<label for="inputLink" class="col-md-2 control-label">表示順</label>
			            	<div class="col-sm-8">
                  				<span class="selectBox">
			                		<select id="js-slctBx-birth_y" class="form-control" name="image_sort">
										@for($sortNum = 1; $sortNum <= $maxValidSort; $sortNum++)
                    						<option @if(old('image_sort',$image->sort_order) == $sortNum) selected @endif value="{{ $sortNum }}">
                    							{{ $sortNum }}
                    						</option>
                    					
										@endfor
                   					</select>
                   				</span>
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
