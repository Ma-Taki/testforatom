@extends('admin.common.layout')
@section('title', '会員一覧')
@section('content')
<?php
use App\Libraries\OrderUtility as OdrUtil;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">会員一覧</div>
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
                    <legend><div class="panel-title">検索</div></legend>
			  		    <form class="form-inline" role="form" method="POST" action="{{ url('/admin/member/search') }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="col-md-3"><label class="control-label">会員ID（メールアドレス）</label></th>
                                    <td class="col-md-9"><input class="form-control input-sm" type="text" name="member_mail" value="{{ old('member_mail') }}" /></td>
                            	</tr>
                                <tr>
                                    <th><label class="control-label">氏名</label></th>
                                    <td><input type="text" class="form-control input-sm" name="member_name" value="{{ old('member_name') }}" /></td>
                            	</tr>
                            	<tr>
                                    <th><label class="control-label">氏名(かな)</label></th>
                                    <td><input type="text" class="form-control input-sm" name="member_name_kana" value="{{ old('member_name_kana') }}" /></td>
                                </tr>
                                <tr>
                                    <th><label class="control-label">ステータス</label></th>
                                    <td><input type="checkbox" name="enabledOnly" id="inputEnabledOnly" @if(old('enabledOnly')) checked @endif /><label for="inputEnabledOnly"><font style="font-weight:normal;">有効なエントリーのみ</font></label></td>
                                </tr>
                                <tr>
                                    <th><label class="control-label" for="select-2">表示順序</label></th>
									<td><select class="form-control" id="select-2" name="sort_id">
@foreach(OdrUtil::MemberOrder as $memberOrder)
                                            <option value="{{ $memberOrder['sortId'] }}" {{ $sort_id ===  $memberOrder['sortId'] ? "selected" : "" }}>{{ $memberOrder['sortName'] }}</option>
@endforeach
										</select>
                                    </td>
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
						<td>{{ $member->last_name }} {{ $member->first_name }} ({{ $member->last_name_kana }} {{ $member->first_name_kana }})</td>
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
                <dev class="pull-right">{!! $memberList->render() !!}</div>
            </div>
        </div>
    </div>
</div>
@endsection
