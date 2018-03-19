@extends('admin.common.layout')
@section('title', '画像一覧')
@section('content')
<?php
use App\Models\Tr_slide_images;
?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">画像一覧</div>
			</div>
		  	<div class="panel-body">
				{{-- info：custom --}}
				@if(\Session::has('custom_info_messages'))
					<div class="alert alert-info">
					    <ul>
					        <li>{{ \Session::get('custom_info_messages') }}</li>
					    </ul>
					</div>
				@endif
                <legend><div class="panel-title">一覧</div></legend>
		        <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                             <th width="35%">画像</th>
                             <th width="18%">タイトル (alt属性)</th>
                             <th width="5%">リンク先</th>
                             <th width="7%">表示順</th>
                             <th width="8%">登録日</th>
                             <th width="9%">最終更新日</th>
                             <th width="13%">ステータス</th>
                             <th width="5%"><!-- 編集/削除ボタン --></th>
                         </tr>
                    </thead>
                    <tbody>
						@foreach(Tr_slide_images::getSortSlideImage() as $image)
	                        <tr>
	                            <td>
	                            	<p>
		                            	<img src="{{ asset('/front/images/slide/'. $image->id .'.jpg') }}" width="60%" height="60%" >		   
        							</p>
	                            </td>
	                            <td>{{ $image->title }}</td>
	                            <td>{{ $image->link }}</td>
	                            <td>{{ $image->sort_order }}</td>
	                            <td>{{ $image->created_at }}</td>
	                            <td>{{ $image->updated_at }}</td>
	                            <td>{{ $image->delete_flag ? '非表示' : '表示' }}</td>
	                            <td>

	                            <a href="/admin/slide/modify?id={{ $image->id }}">
	                                <button type="button" class="btn btn-warning btn-xs">編集</button>
	                            </a>
									@if(!$image->delete_flag)
	                               		<a href="/admin/slide/delete?id={{ $image->id }}&sort_order={{ $image->sort_order }}" onClick="javascript:return confirm('本当に削除しますか？')">
	                               			<button type="button" class="btn btn-danger btn-xs">削除</button>
	                               		</a>
	                               	@else
										<a href="/admin/slide/insert?id={{ $image->id }}" onClick="javascript:return confirm('本当に復活させますか？')">
	                               			<button type="button" class="btn btn-primary btn-xs">復活</button>
	                               		</a>
									@endif
                            	</td>
                        	</tr>
						@endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection