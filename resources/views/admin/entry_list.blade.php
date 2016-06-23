@extends('admin.common.layout')
@section('title', 'エントリー一覧')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">エントリー一覧</div>
			</div>
  			<div class="panel-body">
@if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
@foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
@endforeach
                    </ul>
                </div>
@endif
{{-- error：不正な日付 --}}
@if(isset($c_error_date))
                <div class="alert alert-danger">
                    <ul>
                        <li>不正な日付が設定されています。</li>
                    </ul>
                </div>
@endif
                <fieldset>
                    <legend><div class="panel-title">検索</div></legend>
			  		    <form class="form-inline" role="form" method="POST" action="{{ url('/admin/entry/search') }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th>エントリーID</th>
                                    <td>EN<input type="text" class="" name="entry_id" value="{{ old('entry_id') }}" maxlength="6" /> (エントリーIDを指定した場合、他の検索条件は無視されます)</td>
                            	</tr>
                        		<tr>
                        			<th><label class="control-label">エントリー日付</label></th>
                                    <td><input type="text" class="datepicker" name="entry_date_from" value="{{ old('entry_date_from') }}" maxlength="10" readonly="readonly"/> ～ <input type="text" class="datepicker" name="entry_date_to" value="{{ old('entry_date_to') }}"  maxlength="10" readonly="readonly"/> (YYYY/MM/DD形式)</td>
                            	</tr>
                            	<tr>
                        			<th><label class="control-label">ステータス</label></th>
                                    <td><input type="checkbox" name="enabledOnly" id="eo_label"　@if(old('enabledOnly')) checked @endif /><label for="eo_label"><font style="font-weight:normal;">有効なエントリーのみ</font></label></td>
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
  			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="entryList">
                 <thead>
                    <tr>
                        <th>エントリーID</th>
                        <th>氏名/氏名(かな)</th>
                        <th>案件名</th>
                        <th>エントリー日付</th>
                        <th>スキルシート</th>
                        <th><!-- レイアウト用Blank --></th>
                    </tr>
                </thead>
                <tbody>

@foreach($entryList as $entry)
                    <tr>
						<td>{{ $entry->id }}</td>
						<td>{{ $entry->user->first_name or '山田' }} {{ $entry->user->last_name or '太郎' }} ({{ $entry->user->first_name_kana or 'やまだ' }} {{ $entry->user->last_name_kana or 'たろう' }})</td>
						<td>{{ $entry->item->name}}</td>
                        <td>{{ $entry->entry_date->format('Y年n月j日 G時i分') }}</td>
						<td>{!! $entry->skillsheet_upload ? "<a href='/admin/entry/download?id=$entry->id'>アップロード済み</a>" : '未アップロード' !!}</td>
                        <td align="center" nowrap>
                            <a href="/admin/entry/detail?id={{ $entry->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>
                            <a href="/admin/entry/delete?id={{ $entry->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
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
