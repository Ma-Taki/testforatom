@extends('admin.common.layout')
@section('title', 'タグ一覧')
@section('content')
<?php
use App\Libraries\HtmlUtility;
use App\Libraries\OrderUtility as OdrUtil;
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Tr_tag_infos;
?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">タグ一覧</div>
			</div>
  	  <div class="panel-body">

{{-- info message --}}
@if(\Session::has('custom_info_messages'))
        <div class="alert alert-info">
          <ul>
            <li>{{ \Session::get('custom_info_messages') }}</li>
          </ul>
        </div>
@endif
        <fieldset>
          <legend><div class="panel-title">検索</div></legend>
		  		<form class="form-inline" role="form" method="GET" action="{{url('/admin/item/tags/search')}}">
            <table class="table table-bordered">
              <tr>
              </tr>
              <tr>
                <th><label class="control-label">タグ名</label></th>
                <td><input type="text" class="form-control" name="freeword" value="{{ old('freeword') }}" /></td>
              </tr>
              <tr>
              </tr>
              <tr>
                <th><label class="control-label">表示順</label></th>
                <td>
                  <input class='checks' type="checkbox" name="enabled" id="eo_label2" value = 'tags.id-asc' @if(old('enabled')==='tags.id-asc') checked @endif />
                  <label for="eo_label2"><font style="font-weight:normal;">タグID順</font></label>
                  <input class='checks' type="checkbox" name="enabled" id="eo_label1" value = 'total_cnt-asc' @if(old('enabled')==='total_cnt-asc') checked @endif />
                  <label for="eo_label1"><font style="font-weight:normal;">使用数が少ない順</font></label>
                  <input class='checks' type="checkbox" name="enabled" id="eo_label3" value = 'total_cnt-desc' @if(old('enabled')==='total_cnt-desc') checked @endif />
                  <label for="eo_label3"><font style="font-weight:normal;">使用数が多い順</font></label>
                </td>
              </tr>
              <tr>
              </tr>
              <tr>
                <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
              </tr>
            </table>
            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
					</form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">@if(count($itemList)!=0) タグ一覧： @elseif(count($itemList)==0) ※該当するタグがありませんでした @endif</div></legend>
        <table class="table table-striped table-bordered">
          <thead>
@if(count($itemList)!=0)
            <tr>
              <th>ID</th>
              <th>名前</th>
              <th>掲載中</th>
              <th>掲載終了</th>
              <th>合計</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
@endif
@foreach($itemList as $item)
				    <tr>
              <td>{{ $item->id }}</td>
              <td>{{ $item->term }}</td>
              <td>{{ $item->in_cnt }}</td>
              <td>{{ $item->out_cnt }}</td>
              <td>{{ $item->total_cnt }}</td>
              <td nowrap>
                <a href="/item/tag/{{ $item->id }}" target="_blank"><button type="button" class="btn btn-info btn-xs">一覧</button></a>
@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                <a href="/admin/item/tags/delete?id={{ $item->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
@endif
              </td>
				    </tr>
@endforeach
          </tbody>
        </table>
        <dev class="pull-right">{!! $itemList->render() !!}</div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ url('/admin/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js') }}"></script>
<script type="text/javascript">
$(function(){
  $('.checks').on('click', function() {
    if ($(this).prop('checked')){
        $('.checks').prop('checked', false);
        $(this).prop('checked', true);
    }
  }).css('margin-left',10);
});
</script>
@endsection
