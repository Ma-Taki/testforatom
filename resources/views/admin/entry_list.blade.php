@extends('admin.common.layout')
@section('title', 'エントリー一覧')
@section('content')
<?php
use App\Libraries\OrderUtility as OdrUtil;
?>
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
			  		    <form class="form-inline" role="form" method="POST" action="{{ url('/admin/entry/search') }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="col-md-2">
                                        <label class="control-label">エントリーID</label>
                                    </th>
                                    <td class="col-md-10">
                                        <div class="input-group">
											<span class="input-group-addon">EN</span>
											<input name="entry_id" value="{{ isset($entry_id) ? $entry_id : old('entry_id')  }}" maxlength="6"class="form-control" type="text">
										</div>
                                        (エントリーIDを指定した場合、他の検索条件は無視されます)
                                    </td>
                            	</tr>
                        		<tr>
                        			<th><label class="control-label">エントリー日付</label></th>
                                    <td><input type="text" class="datepicker" name="entry_date_from" value="{{ isset($entry_date_from) ? $entry_date_from : old('entry_date_from')  }}" maxlength="10" readonly="readonly"/> ～ <input type="text" class="datepicker" name="entry_date_to" value="{{ isset($entry_date_to) ? $entry_date_to : old('entry_date_to') }}"  maxlength="10" readonly="readonly"/> (YYYY/MM/DD形式)</td>
                            	</tr>
                            	<tr>
                        			<th><label class="control-label">ステータス</label></th>
@if(isset($enabledOnly))
                                    <td><input type="checkbox" name="enabledOnly" id="eo_label"　@if($enabledOnly) checked @endif /><label for="eo_label"><font style="font-weight:normal;">有効なエントリーのみ</font></label></td>
@else
                                    <td><input type="checkbox" name="enabledOnly" id="eo_label"　@if(old('enabledOnly')) checked @endif /><label for="eo_label"><font style="font-weight:normal;">有効なエントリーのみ</font></label></td>
@endif
                                </tr>
                                <tr>
                                    <th><label class="control-label" for="select-2">表示順序</label></th>
									<td><select class="form-control" id="select-2" name="sort_id">
@foreach(OdrUtil::EntryOrder as $entryOrder)
@if(old('sort_id'))
                                            <option value="{{ $entryOrder['sortId'] }}" {{ old('sort_id') ===  $entryOrder['sortId'] ? "selected" : "" }}>{{ $entryOrder['sortName'] }}</option>
@else
                                            <option value="{{ $entryOrder['sortId'] }}" {{ $sort_id ===  $entryOrder['sortId'] ? "selected" : "" }}>{{ $entryOrder['sortName'] }}</option>
@endif
@endforeach
										</select>
                                    </td>
                            	</tr>
                                <tr>
                                    <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
                                </tr>
                            </table>
                            {{ csrf_field() }}
						</form>
                </fieldset>
                </br>
                </br>
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

@foreach($entryList as $entry)
                    <tr>
						<td>{{ $entry->id }}</td>
						<td>{{ $entry->user->last_name or '山田' }} {{ $entry->user->first_name or '太郎' }} ({{ $entry->user->last_name_kana or 'たろう' }} {{ $entry->user->first_name_kana or 'やまだ' }})</td>
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
                <dev class="pull-right">{!! $entryList->render() !!}</div>
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
