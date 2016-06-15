@extends('admin.common.layout')
@section('title', 'ユーザ一覧')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">ユーザ一覧</div>
				<div class="panel-options">
                    <a href="#" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a>
                    <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
				</div>
			</div>
		  	<div class="panel-body">
		        <table class="table">
                    <thead>
                        <tr>
                             <th class="col-md-1">ID</th>
                             <th class="col-md-2">管理者名</th>
                             <th class="col-md-2">ログインID</th>
                             <th class="col-md-2">登録日</th>
                             <th class="col-md-2">最終更新日</th>
                             <th class="col-md-1"><!-- レイアウト用ブランク --></th>
                             <th class="col-md-2"><!-- 編集/削除ボタン --></th>
                         </tr>
                     </thead>
                     <tbody>

@foreach($userList as $user)
@if (session('user_session_key_admin_id') === $user->id)
<!-- 現状ユーザ管理にはマスター管理者しか行えないためこの判定 -->
                        <tr class="success">
@elseif (!$user->delete_flag)
                        <tr class="active">
@else
                        <tr class="danger">
@endif
                            <th>{{ $user->id }}</th>
                            <th>{{ $user->admin_name }}</th>
                            <th>{{ $user->login_id }}</th>
                            <th>{{ $user->registration_date }}</th>
                            <th>{{ $user->last_update_date }}</th>
                            <th></th>
@if (!$user->delete_flag)
                            <th>
                                <a href="/admin/user/modify?id={{ $user->id }}"><button type="button" class="btn btn-warning btn-xs">編集</button></a>
@if (session('user_session_key_admin_id') !== $user->id)
                                <a href="/admin/user/delete?id={{ $user->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
@endif
                            </th>
@else
                            <th></th>
@endif
                        </tr>
@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
