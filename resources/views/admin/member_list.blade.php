@extends('admin.common.layout')
@section('title', '会員一覧')
@section('content')
<?php
    use App\Libraries\OrderUtility as OdrUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<style>
    #impression-excellent,
    #impression-normal,
    #impression-notgood,
    #impression-black,
    #inputEnabledOnly {
        margin-top: 0px;
        margin-right: 6px;
        vertical-align: middle;
    }

    .user-state td label,
    .user-impression td label {
        font-weight: normal;
        white-space: nowrap;
    }
</style>
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

        <fieldset>
          <legend><div class="panel-title">検索</div></legend>
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/member/search') }}">
            <table class="table table-bordered">
              <tr>
                <th class="col-md-3"><label class="control-label">会員ID（メールアドレス）</label></th>
                <td class="col-md-9"><input class="form-control input-sm" type="text" name="member_mail" value="{{ old('member_mail', $data_query['member_mail']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">氏名</label></th>
                <td><input type="text" class="form-control input-sm" name="member_name" value="{{ old('member_name', $data_query['member_name']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">氏名(かな)</label></th>
                <td><input type="text" class="form-control input-sm" name="member_name_kana" value="{{ old('member_name_kana', $data_query['member_name_kana']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">フリーワード</label></th>
                <td><input type="text" class="form-control input-sm" name="freeword" value="{{ old('freeword', $data_query['freeword']) }}" /></td>
              </tr>
              <tr class="user-state">
                <th><label class="control-label">ステータス</label></th>
                <td><div class="col-md-3">
                  <!-- 未チェックでも状態を送信する　 -->
                  <input type="hidden"   name="enabledOnly" value="off">
                  <input type="checkbox" name="enabledOnly" value="on" id="inputEnabledOnly" {{ old('enabledOnly', $data_query['enabledOnly']) == 'on' ? 'checked' : '' }} />
                  <label for="inputEnabledOnly"><font style="font-weight:normal;">有効な会員のみ</font></label>
                </div></td>
              </tr>
              <tr class="user-impression">
                <th><label class="control-label">評価</label></th>
                <td>
                  <!-- 未チェックでも状態を送信する　 -->
                  <div class="col-md-2">
                    <label for="impression-excellent">
                      <input type="hidden"   name="impression[0]" value="off">
                      <input type="checkbox" name="impression[0]" id="impression-excellent" value="{{ MdlUtil::USER_IMPRESSION_EXCELLENT }}" {{ in_array(MdlUtil::USER_IMPRESSION_EXCELLENT, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      優良
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-normal">
                      <input type="hidden"   name="impression[1]" value="off">
                      <input type="checkbox" name="impression[1]" id="impression-normal" value="{{ MdlUtil::USER_IMPRESSION_NORMAL }}" {{ in_array(MdlUtil::USER_IMPRESSION_NORMAL, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      普通
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-notgood">
                      <input type="hidden"   name="impression[2]" value="off">
                      <input type="checkbox" name="impression[2]" id="impression-notgood" value="{{ MdlUtil::USER_IMPRESSION_NOTGOOD }}" {{ in_array(MdlUtil::USER_IMPRESSION_NOTGOOD, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      いまいち
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-black">
                      <input type="hidden"   name="impression[3]" value="off">
                      <input type="checkbox" name="impression[3]" id="impression-black" value="{{ MdlUtil::USER_IMPRESSION_BLACK }}" {{ in_array(MdlUtil::USER_IMPRESSION_BLACK, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      ブラック
                    </label>
                  </div>
                </td>
              </tr>
              <tr>
                <th><label class="control-label" for="select-2">表示順序</label></th>
								<td>
                  <select class="form-control" id="select-2" name="sort_id">

@foreach(OdrUtil::MemberOrder as $memberOrder)
                    <option value="{{ $memberOrder['sortId'] }}" {{ old("sort_id", $data_query['sort_id']) ==  $memberOrder['sortId'] ? "selected" : "" }}>{{ $memberOrder['sortName'] }}</option>
@endforeach

                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
              </tr>
            </table>
					</form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">一覧</div></legend>
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
        <dev class="pull-right">
          {!! $memberList->appends([
            'member_mail'      => $data_query['member_mail'],
            'member_name'      => $data_query['member_name'],
            'member_name_kana' => $data_query['member_name_kana'],
            'freeword'         => $data_query['freeword'],
            'enabledOnly'      => $data_query['enabledOnly'],
            'impression'       => $data_query['impression'],
            'sort_id'          => $data_query['sort_id'],
          ])->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
