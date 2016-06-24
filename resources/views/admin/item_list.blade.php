@extends('admin.common.layout')
@section('title', '案件一覧')
@section('content')
<?php
use App\Libraries\HtmlUtility;
use App\Libraries\OrderUtility as OdrUtil;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">案件一覧</div>
			</div>
  			<div class="panel-body">
                <fieldset>
                    <legend><div class="panel-title">検索</div></legend>
			  		    <form class="form-inline" role="form" method="POST" action="{{ url('/admin/item/search') }}">
                            <table class="table table-bordered">
                                <tr>
                                    <th>案件ID</th>
                                    <td>AN<input type="text" class="" name="item_id" value="{{ old('item_id') }}" maxlength="6" /> (案件IDを指定した場合、他の検索条件は無視されます)</td>
                            	</tr>
                                <tr>
                                    <th>案件名</th>
                                    <td><input type="text" class="" name="item_name" value="{{ old('item_name') }}" /></td>
                            	</tr>
                                <tr>
                                    <th><label class="control-label" for="select-1">スペシャルタグ</label></th>
									<td><select class="form-control" id="select-1" name="special_tag">
										    <option value="" selected>未選択</option>

@foreach($pickupTagInfos as $tagInfo)
											<option value="{{ $tagInfo->tag->id }}" {{ old('special_tag') == $tagInfo->tag->id ? "selected" : "" }}>{{ $tagInfo->tag->term }}</option>
@endforeach

@foreach($featureTagInfos as $tagInfo)
											<option value="{{ $tagInfo->tag->id }}" {{ old('special_tag') == $tagInfo->tag->id ? "selected" : "" }}>{{ $tagInfo->tag->term }}</option>
@endforeach

										</select>
                                    </td>
                            	</tr>
                            	<tr>
                        			<th><label class="control-label">ステータス</label></th>
                                    <td><input type="checkbox" name="enabled_only" id="eo_label"　@if($enabled_only) checked @endif /><label for="eo_label"><font style="font-weight:normal;">受付中のみ</font></label></td>
                                </tr>
                                <tr>
                                    <th><label class="control-label" for="select-2">表示順序</label></th>
									<td><select class="form-control" id="select-2" name="sort_id">
@foreach(OdrUtil::ItemOrder as $itemOder)
                                            <option value="{{ $itemOder['sortId'] }}" {{ $sort_id ===  $itemOder['sortId'] ? "selected" : "" }}>{{ $itemOder['sortName'] }}</option>
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
                <table class="table table-bordered">
				    <thead>
			            <tr>
                            <th>案件ID</th>
                            <th>案件名</th>
                            <th>業種</th>
				            <th>エリア</th>
                            <th>報酬</th>
                            <th>エントリー受付期間</th>
                            <th>ステータス</th>
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
                            <td nowrap>
                                <a href="/admin/item/detail?id={{ $item->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>
                                <a href="/admin/item/delete?id={{ $item->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
                            </td>
				        </tr>
@endforeach

				    </tbody>
				</table>
                <dev class="">{!! $itemList->appends(['sort_id' => $sort_id, 'enabled_only' => $enabled_only])->render() !!}</div>
            </div>
        </div>
    </div>
</div>
@endsection
