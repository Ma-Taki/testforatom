@extends('admin.common.layout')
@section('title', '特集記事紐付け一覧')
@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">記事紐付け一覧</div>
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
                             <th>紐付けID</th>
                             <th>記事タイトル</th>
                             <th>キーワード</th>
                             <th>ステータス</th>
                             <th><!-- 編集/削除ボタン --></th>
                         </tr>
                    </thead>
                    <tbody>
						@foreach($connectsList as $connect)
	                        <tr>
	                            <td>{{ $connect->connect_id }}</td>
	                            <td>{{ $connect->title }}</td>
	                            <td>{!! $connect->keyword !!}</td>
	                            <td>{{ $connect->delete_flag ? '無効' : '有効' }}</td>
	                            <td>
		                            <a href="/admin/column-connect/modify?id={{ $connect->id }}">
		                                <button type="button" class="btn btn-warning btn-xs">編集</button>
		                            </a>
									@if(!$connect->delete_flag)
	                               		<a href="/admin/column-connect/delete?id={{ $connect->id }}&sort_order={{ $connect->sort_order }}" onClick="javascript:return confirm('本当に削除しますか？')">
	                               			<button type="button" class="btn btn-danger btn-xs">削除</button>
	                               		</a>
	                               	@else
										<a href="/admin/column-connect/insert?id={{ $connect->id }}" onClick="javascript:return confirm('本当に復活させますか？')">
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