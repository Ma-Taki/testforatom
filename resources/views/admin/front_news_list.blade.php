@extends('admin.common.layout')
@section('title', 'お知らせ一覧(フロント画面)')
@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">お知らせ一覧(フロント画面)</div>
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
				<fieldset>
			        <legend>
			        	<div class="panel-title">検索</div>
			        </legend>
			        <form class="form-inline" role="form" method="GET" action="{{ url('/admin/front-news/search') }}">
			        	<table class="table table-bordered">
			            	<tr>
			                	<th><label class="control-label">タイトル</label></th>
			                	<td><input type="text" class="form-control" name="title" value="{{ $data_query['title'] }}" /></td>
			              	</tr>
			              	<tr>
				                <th><label class="control-label">ステータス</label></th>
				                <td>
				                	<input type="checkbox" name="delete_flag" id="eo_label" {{ empty($data_query['delete_flag']) ?: "checked" }} />
				                	<label for="eo_label">
				                    	<font style="font-weight:normal;">表示のみ</font>
				                  	</label>
				                </td>
				            </tr>
			              	<tr>
				                <td colspan="2">
				                  <button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button>
				                </td>
			              	</tr>
			            </table>
			        </form>
		        </fieldset>
                <legend><div class="panel-title">一覧</div></legend>
		        <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                             <th width="10%">日付</th>
                             <th width="24%">タイトル</th>
                             <th width="45%">内容</th>
                             <th width="10%">ステータス</th>
                             <th width="10%"><!-- 編集/削除ボタン --></th>
                         </tr>
                    </thead>
                    <tbody>
						<tbody>
                    	@if(!empty($newsList))
							@foreach($newsList as $news)
		                        <tr>
		                            <td>{{ $news->release_date->format('Y/m/d') }}</td>
		                            <td>{{ $news->title }}</td>
									<td>{{ str_limit($news->contents, 150) }}</td>
		                            <td>{{ $news->delete_flag ? '無効' : '有効' }}</td>
		                            <td>
			                            <a href="/admin/front-news/modify?id={{ $news->id }}">
			                                <button type="button" class="btn btn-warning btn-xs">編集</button>
			                            </a>
										@if(!$news->delete_flag)
		                               		<a href="/admin/front-news/delete?id={{ $news->id }}" onClick="javascript:return confirm('本当に削除しますか？')">
		                               			<button type="button" class="btn btn-danger btn-xs">削除</button>
		                               		</a>
		                               	@else
											<a href="/admin/front-news/insert?id={{ $news->id }}" onClick="javascript:return confirm('本当に復活させますか？')">
		                               			<button type="button" class="btn btn-primary btn-xs">復活</button>
		                               		</a>
										@endif
		                        	</td>
		                    	</tr>
							@endforeach
						@endif
                    </tbody>
                </table>
                <div class="pull-right">
	          		{!! $newsList->appends([
			            'title'    => $data_query['title'],
			            'enabled' => $data_query['delete_flag'],
			          ])->render() !!}
		        </div>
            </div>
        </div>
    </div>
</div>
@endsection