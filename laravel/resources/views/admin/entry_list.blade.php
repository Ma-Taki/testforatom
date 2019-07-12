@extends('admin.common.layout')
@section('title', 'エントリー一覧')
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
        <div class="panel-title" style="font-size:20px">エントリー一覧</div>
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
            <li>{{ Session::get('custom_error_messages') }}</li>
          </ul>
        </div>
@endif
        <fieldset>
          <legend><div class="panel-title">検索</div></legend>
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/entry/search') }}">
            <table class="table table-bordered">
              <tr>
                <th class="col-md-2">
                  <label class="control-label">エントリーID</label>
                </th>
                <td class="col-md-10">
                  <div class="input-group">
                    <span class="input-group-addon">EN</span>
                    <input name="entry_id" value="{{ old('entry_id', $data_query['entry_id']) }}" maxlength="6"class="form-control" type="text">
									</div>
                  (エントリーIDを指定した場合、他の検索条件は無視されます)
                </td>
              </tr>
              <tr>
                <th><label class="control-label">エントリー日付</label></th>
                <td><input type="text" class="datepicker" name="entry_date_from" value="{{ old('entry_date_from', $data_query['entry_date_from']) }}" maxlength="10" readonly="readonly"/> ～ <input type="text" class="datepicker" name="entry_date_to" value="{{ old('entry_date_to', $data_query['entry_date_to']) }}"  maxlength="10" readonly="readonly"/> (YYYY/MM/DD形式)</td>
              </tr>
              <tr>
                <th><label class="control-label">ステータス</label></th>
                <td>
                  <div class="col-md-3">
                    <!-- 未チェックでも状態を送信する　 -->
                    <input type="hidden"   name="enabledOnly" value="off">
                    <input type="checkbox" name="enabledOnly" value="on" id="eo_label" {{ old('enabledOnly', $data_query['enabledOnly']) == 'on' ? 'checked' : '' }} />
                    <label for="eo_label"><font style="font-weight:normal;">有効なエントリーのみ</font></label>
                  </div>
                </td>
              </tr>
              <tr class="user-impression">
                <th><label class="control-label">ユーザの評価</label></th>
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

@foreach(OdrUtil::EntryOrder as $entryOrder)
                    <option value="{{ $entryOrder['sortId'] }}" {{ old('sort_id', $data_query['sort_id']) ==  $entryOrder['sortId'] ? "selected" : "" }}>{{ $entryOrder['sortName'] }}</option>
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
  			<table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>エントリーID</th>
              <th>氏名/氏名(かな)</th>
              <th>案件名</th>
              <th>エントリー日付</th>
              <th>スキルシート</th>
              <th>ステータス</th>
              <th><!-- レイアウト用Blank --></th>
            </tr>
          </thead>
          <tbody>

@foreach($entry_list as $entry)
            <tr>
              <td>{{ $entry->id }}</td>
              <td>
                @if($entry->user->last_name)
                  {{ $entry->user->last_name }} 
                @else
                  山田 
                @endif
                @if($entry->user->first_name)
                  {{ $entry->user->first_name }} 
                @else
                  太郎　
                @endif                
                @if($entry->user->last_name_kana)
                  ({{ $entry->user->last_name_kana }} 
                @else
                  (たろう 
                @endif
                @if($entry->user->first_name_kana)
                  {{ $entry->user->first_name_kana }})
                @else
                  やまだ)
                @endif  
              </td>
              <td>{{ $entry->item->name}}</td>
              <td>{{ $entry->entry_date->format('Y年n月j日 G時i分') }}</td>
              <td>{!! $entry->skillsheet_upload ? "<a href='/admin/entry/download?id=$entry->id'>アップロード済み</a>" : '未アップロード' !!}</td>
              <td>{{ $entry->delete_flag > 0 ? '無効' : '有効' }}</td>
              <td nowrap>
                <a href="/admin/entry/detail?id={{ $entry->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>
@if(!$entry->delete_flag > 0)
                <a href="/admin/entry/delete?id={{ $entry->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
@endif
              </td>
            </tr>
@endforeach

          </tbody>
        </table>
        <dev class="pull-right">
          {!! $entry_list->appends([
            'entry_id'        => $data_query['entry_id'],
            'entry_date_from' => $data_query['entry_date_from'],
            'entry_date_to'   => $data_query['entry_date_to'],
            'impression'      => $data_query['impression'],
            'enabledOnly'     => $data_query['enabledOnly'],
            'sort_id'         => $data_query['sort_id'],
          ])->render() !!}</div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="{{ url('/admin/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js') }}"></script>
<script type="text/javascript">
$(function() {
    $('.datepicker').datepicker({
      format: 'yyyy/mm/dd',
      language: 'ja',
      autoclose: true,
      clearBtn: true,
  });
});
</script>
@endsection
