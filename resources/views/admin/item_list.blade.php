@extends('admin.common.layout')
@section('title', '案件一覧')
@section('content')
<?php
use App\Libraries\HtmlUtility;
use App\Libraries\OrderUtility as OdrUtil;
use App\Models\Tr_tag_infos;
?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">案件一覧</div>
			</div>
  	  <div class="panel-body">
        <p class="alert-member" style="font-weight: bold;padding-bottom: 1.6%;">案件総数: {{$allItems}}件　募集中: {{$wantedItems}}件</p>
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
		  		<form class="form-inline" role="form" method="GET" action="{{ url('/admin/item/search') }}">
            <table class="table table-bordered">
              <tr>
                <th class="col-md-2">
                  <label class="control-label">案件ID</label>
                </th>
                <td class="col-md-10">
                  <div class="input-group">
                    <span class="input-group-addon">AN</span>
                    <input class="form-control" name="id" value="{{ $data_query['id'] }}" maxlength="6" type="text">
                  </div>
                  (案件IDを指定した場合、他の検索条件は無視されます)
                </td>
              </tr>
              <tr>
                <th><label class="control-label">案件名</label></th>
                <td><input type="text" class="form-control" name="name" value="{{ $data_query['name'] }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">フリーワード</label></th>
                <td><input type="text" class="form-control" name="freeword" value="{{ $data_query['freeword'] }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label" for="select-1">スペシャルタグ</label></th>
								<td>
                  <select class="form-control" id="select-1" name="tag_id">
                    <option value="" selected>未選択</option>

@foreach(Tr_tag_infos::getPickupTagInfo() as $tagInfo)
										<option value="{{ $tagInfo->tag->id }}" {{ $tagInfo->tag->id == $data_query['tag_id'] ? "selected" : "" }}>{{ $tagInfo->tag->term }}</option>
@endforeach

@foreach(Tr_tag_infos::getFeatureTagInfo() as $tagInfo)
										<option value="{{ $tagInfo->tag->id }}" {{ $tagInfo->tag->id == $data_query['tag_id'] ? "selected" : ""}}>{{ $tagInfo->tag->term }}</option>
@endforeach

									</select>
                </td>
              </tr>
              <tr>
                <th><label class="control-label">ステータス</label></th>
                <td><input type="checkbox" name="enabled" id="eo_label" {{ empty($data_query['enabled']) ?: "checked" }} /><label for="eo_label"><font style="font-weight:normal;">受付中のみ</font></label></td>
              </tr>
              <tr>
                <th><label class="control-label" for="select-2">表示順序</label></th>
								<td>
                  <select class="form-control" id="select-2" name="sort_id">

@foreach(OdrUtil::ItemOrder as $itemOder)
                    <option value="{{ $itemOder['sortId'] }}" {{ $itemOder['sortId'] == $data_query['sort_id'] ? "selected" : "" }}>{{ $itemOder['sortName'] }}</option>
@endforeach
									</select>
                </td>
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
        <legend><div class="panel-title">一覧</div></legend>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>案件ID</th>
              <th>案件名</th>
              <th>業種</th>
              <th>エリア</th>
              <th>報酬</th>
              <th>エントリー受付期間</th>
              <th>ステータス</th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

@foreach($itemList as $item)
				    <tr>
              <td>AN{{ $item->id }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->bizCategorie->name }}</td>
              <td>{{ $item->area_detail }}</td>
              <td>{{ $item->rate_detail }}</td>
              <td>{{ $item->service_start_date->format('Y年n月j日') }} 〜 {{ $item->service_end_date->format('Y年n月j日') }}</td>
              <td>@if($item->delete_flag) 削除済み @else 受付{{ (HtmlUtility::isTodayInPeriod($item->service_start_date, $item->service_end_date)) }} @endif</td>
              <td>@if($item->note) ◯ @else -　@endif</td>
              <td nowrap>
                <a href="/admin/item/detail?id={{ $item->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>

@if(!$item->delete_flag)
                <a href="/admin/item/modify?id={{ $item->id }}"><button type="button" class="btn btn-warning btn-xs">編集</button></a>
                <a href="/admin/item/delete?id={{ $item->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
@endif

              </td>
				    </tr>
@endforeach
          </tbody>
        </table>
        <dev class="pull-right">{!! $itemList->appends([
          'id'       => $data_query['id'],
          'name'     => $data_query['name'],
          'freeword' => $data_query['freeword'],
          'tag_id'   => $data_query['tag_id'],
          'enabled'  => $data_query['enabled'],
          'sort_id'  => $data_query['sort_id']
         ])->render() !!}</div>
      </div>
    </div>
  </div>
</div>
@endsection
