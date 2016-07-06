@extends('admin.common.layout')
@section('title', 'ユーザ一覧')
@section('content')
<?php
use App\Libraries\SessionUtility as sesUtil;
 ?>
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
                             <th class="">ID</th>
                             <th class="">管理者名</th>
                             <th class="">ログインID</th>
                             <th class="">登録日</th>
                             <th class="">最終更新日</th>
                             <th class="">ステータス</th>
                             <th class=""><!-- 編集/削除ボタン --></th>
                         </tr>
                     </thead>
                     <tbody>

@foreach($userList as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->admin_name }}</td>
                            <td>{{ $user->login_id }}</td>
                            <td>{{ $user->registration_date }}</td>
                            <td>{{ $user->last_update_date }}</td>
                            <td>{{ $user->delete_flag ? '無効' : '有効' }}</td>
                            <td>
@if(!$user->delete_flag)
                                <a href="/admin/user/modify?id={{ $user->id }}"><button type="button" class="btn btn-warning btn-xs">編集</button></a>
@if($user->id != session(sesUtil::SESSION_KEY_ADMIN_ID))
                                <a href="/admin/user/delete?id={{ $user->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
@endif
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
