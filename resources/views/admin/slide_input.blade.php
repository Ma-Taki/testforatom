@extends('admin.common.layout')
@section('title', '画像登録')
@section('isSimpleFooter', 'true')
@section('noindex', 'true')

@section('content')
<?php
use App\Models\Tr_slide_images;
?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title">画像登録</div>
			</div>
		    <div class="panel-body">
                {{-- error：validation --}}
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
                <form class="form-horizontal" method="POST" action="{{ url('/admin/slide/input') }}" enctype="multipart/form-data">
			  		<fieldset>
						<legend style="font-size:16px">画像情報</legend>
                        <div class="form-group">
                            <label for="inputTitle" class="col-md-2 control-label">タイトル (alt属性)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputTitle" name="image_title" value="{{ old('image_title') }}" maxlength="20" placeholder="タイトル (alt属性)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLink" class="col-md-2 control-label">リンク</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputLink" name="image_link" value="{{ old('image_link') }}" maxlength="20" placeholder="リンク">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLink" class="col-md-2 control-label">表示順</label>
                            <div class="col-sm-8">
                                <span class="selectBox">
                                    <select id="js-slctBx-birth_y" class="form-control" name="image_sort">
                                        @for($sortNum = 1; $sortNum <= $maxValidSort; $sortNum++)
                                            <option @if(old('image_sort',$maxValidSort) == $sortNum) selected @endif value="{{ $sortNum }}">
                                                {{ $sortNum }}
                                            </option>
                                        
                                        @endfor
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLink" class="col-md-2 control-label">画像</label>
                            <div class="col-sm-8">
                                <input type="file" name="image_file"><br>
                            </div>
                        </div>
						<div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-primary">登録</button>
                            <a href="/admin/slide/list">
                            	<button type="button" class="btn btn-default">キャンセル</button>
                            </a>
                        </div>
                        {{ csrf_field() }}
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
