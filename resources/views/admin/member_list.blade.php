@extends('admin.common.layout')
@section('title', '会員一覧')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">会員一覧</div>
			</div>
  			<div class="panel-body">
                <fieldset>
                    <legend><div class="panel-title">検索</div></legend>
			  		    <form class="form-inline" role="form" method="POST" action="{{ url('/admin/member/search') }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="col-md-3"><label class="control-label">会員ID（メールアドレス）</label></th>
                            		<td class="col-xs-9"><input class="col-xs-6" type="text" name="member_id" value="" /></td>
                            	</tr>
                        		<tr>
                        			<th><label class="control-label">氏名</label></th>
                            		<td><input type="text" name="member_name" value="" /></td>
                            	</tr>
                            	<tr>
                        			<th><label class="control-label">氏名(かな)</label></th>
                        			<td><input type="text" name="member_name_kana" value="" /></td>
                                </tr>
                                <tr>
                                    <th><label class="control-label">ステータス</label></th>
                        			<td><input type="checkbox"  name="enabledOnly" value="true" />有効なエントリーのみ</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
                                </tr>
                            </table>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
						</form>
                    <legend></legend>
                </fieldset>
                </br>
                </br>
  			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="memberList">
                 <thead>
                    <tr>
                        <th>会員ID</th>
                        <th>氏名/氏名(かな)</th>
                        <th>年齢</th>
                        <th>性別</th>
                        <th>都道府県</th>
                        <th>ステータス</th>
                        <th><!-- レイアウト用Blank --></th>
                    </tr>
                </thead>
                <tbody>

@foreach($memberList as $member)
                    <tr>
						<td>{{ $member->mail }}</td>
						<td>{{ $member->first_name }} {{ $member->last_name }} ({{ $member->first_name_kana }} {{ $member->last_name_kana }})</td>
						<td>{{ $member->birth_date->age }}</td>
                        <td>{{ $member->sex === 'Male' ? '男性' : '女性' }}</td>
						<td>{{ $member->prefecture->name }}</td>
                        <td>{{ $member->delete_flag > 0 ? '無効' : '有効' }}</td>
                        <td nowrap>
                            <a href="/admin/member/detail?id={{ $member->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>
@if(!$member->delete_flag)
                            <a href="/admin/member/delete?id={{ $member->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
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
<link href="{{ url('/admin/vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" media="screen">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="{{ url('/admin/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/admin/vendors/datatables/dataTables.bootstrap.js') }}"></script>
<script src="{{ url('/admin/js/tables.js') }}"></script>
@endsection
