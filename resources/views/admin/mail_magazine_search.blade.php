@extends('admin.common.layout')
@section('title', 'メルマガ送信履歴')
@section('content')
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">メルマガ送信履歴</div>
			</div>
  	  <div class="panel-body">
@if(\Session::has('custom_info_messages'))
        <div class="alert alert-info">
          <ul>
            <li>{{ \Session::get('custom_info_messages') }}</li>
          </ul>
        </div>
@endif
        <fieldset>
          <legend><div class="panel-title">検索</div></legend>
		  		<form class="form-inline" role="form" method="GET" action="{{url('/admin/mail-magazine/search')}}">
            <table class="table table-bordered">
              <tr>
              </tr>
              <tr>
                <th><label class="control-label">件名</label></th>
                <td><input type="text" class="form-control" name="subject" value="{{ old('subject') }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">本文</label></th>
                <td><input type="text" class="form-control" name="body" value="{{ old('body') }}" /></td>
              </tr>
              <tr>
              </tr>
              <tr>
                <th><label class="control-label">送信フラグ</label></th>
                <td>
                  <input class='checks' type="checkbox" name="send_at0" id="send_at0" value = '0' @if(old('send_at0')==='0') checked @endif />
                  <label for="send_at0"><font style="font-weight:normal;">即時</font></label>
                  <input class='checks' type="checkbox" name="send_at1" id="send_at1" value = '1' @if(old('send_at1')==='1') checked @endif />
                  <label for="send_at1"><font style="font-weight:normal;">時間指定</font></label>
                </td>
              </tr>
              <tr>
                <th><label class="control-label">宛先フラグ</label></th>
                <td>
                  <input class='checks' type="checkbox" name="send_to0" id="send_to0" value = '0' @if(old('send_to0')==='0') checked @endif />
                  <label for="send_to0"><font style="font-weight:normal;">配信希望ユーザのみ</font></label>
                  <input class='checks' type="checkbox" name="send_to1" id="send_to1" value = '1' @if(old('send_to1')==='1') checked @endif />
                  <label for="send_to1"><font style="font-weight:normal;">すべての有効なユーザ</font></label>
                  <input class='checks' type="checkbox" name="send_to2" id="send_to2" value = '2' @if(old('send_to2')==='2') checked @endif />
                  <label for="send_to2"><font style="font-weight:normal;">メールアドレス指定</font></label>
                </td>
              </tr>
              </tr>
              <tr>
                <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
              </tr>
            </table>
            <input type="hidden" name="_token" value="{{ csrf_token() }}"
				</form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">@if(count($itemList)!=0) メルマガ送信履歴一覧： @elseif(count($itemList)==0) ※該当する送信履歴がありませんでした @endif</div></legend>
        <table class="table table-striped table-bordered">
          <thead>
@if(count($itemList)!=0)
            <tr>
              <th>ID</th>
              <th>件名</th>
              <th>送信タイプ</th>
              <th>送信日時</th>
              <th>送信者数</th>
              <th>宛先</th>
              <th>ステータス</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
@endif
@foreach($itemList as $item)
				    <tr>
              <td>{{ $item->id }}</td>
              <td>{{ $item->subject }}</td>
              <td>@if($item->send_flag==0) 即時  @elseif ($item->send_flag==1) 日時指定 @endif</td>
              <td>{{ $item->send_at }}</td>
              <td>{{ $item->users->count() }}</td>
              <td>@if($item->send_to==0) 配信希望者  @elseif ($item->send_to==1) ユーザ全員　@else 個別指定　@endif</td>
              <td>@if($item->status==2)<span style="color:#007bbb;">送信済</span>@endif @if($item->delete_flag==1)<span style="color:red;">（配信停止中）</span>@endif</td>
              <td nowrap>
                <a class="getbody" name="{{ $item->id }}" ><button type="button" class="btn btn-xs mailmagazine-detail-btn">詳細</button></a>
@if($item->status==0)
  @if($item->send_at > date("Y-m-d H:i:s",strtotime("-1 hour")))
              <a href="/admin/mail-magazine/?type=edit&id={{ $item->id }}"><button type="button" class="btn btn-info btn-xs">編集</button></a>
  @endif
@endif
@if($item->delete_flag == 0)
                <a href="/admin/mail-magazine/search/stop?id={{ $item->id }}"><button type="button" class="btn btn-danger btn-xs">配信停止</button></a>
@else
                <a href="/admin/mail-magazine/search/start?id={{ $item->id }}"><button type="button" class="btn btn-info btn-xs" style="background:green;border-color:green;">停止解除</button></a>
@endif
              </td>
				    </tr>
            <tr>
              <td colspan="8" style="background:#ebf6f7;padding:0px;">
                <div class="detail-{{$item->id}}">
                  <p>【To】</p>
                  <p>
@foreach($item->mailaddresses as $address)
                    <span>{{ $address->mail_address}} , </span>
@endforeach
                  </p><br>
                  <p>【Cc】</p>
                  <p>{{$item->cc}}</p><br>
                  <p>【Bcc】</p>
                  <p>{{$item->bcc}}</p><br>
                  <p>【本文】</p>
                  <div>{{$item->body}}</div>
                </div>
              </td>
            <tr>
@endforeach
          </tbody>
        </table>
        <dev class="pull-right">{!! $itemList->appends([
          'subject' => $data_query['subject'],
          'body'  => $data_query['body'],
          'send_at0'  => $data_query['send_at0'],
          'send_at1'  => $data_query['send_at1'],
          'send_to0'  => $data_query['send_to0'],
          'send_to1'  => $data_query['send_to1'],
          'send_to2'  => $data_query['send_to2']
         ])->render() !!}</div>
      </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ url('/admin/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script>
$('.getbody').on('click',function(){
  $(".detail-"+$(this).attr('name')).slideToggle('fast');
});
</script>
@endsection
