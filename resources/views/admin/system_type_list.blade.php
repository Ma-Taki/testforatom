@extends('admin.common.layout')
@section('title', 'システム種別一覧')
@section('content')
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">システム種別一覧</div>
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
          <legend>
            <div class="panel-title">検索</div>
          </legend>
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/system-type/search') }}">
            <table class="table table-bordered">
              <tr>
                <th><label class="control-label">システム種別名</label></th>
                <td><input type="text" class="form-control" name="name" value="{{ $data_query['name'] }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">ステータス</label></th>
                <td>
                  <input type="checkbox" name="delete_flag" id="eo_label" {{ empty($data_query['delete_flag']) ?: "checked" }} />
                  <label for="eo_label">
                    <font style="font-weight:normal;">表示のみ</font>
                  </label>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button>
                </td>
              </tr>
            </table>
          </form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">一覧</div></legend>
        <table class="table table-striped table-bordered">
          <thead>
            @if(count($sysTypes)!=0)
              <tr>
                <th width="70%">システム種別名</th>
                <th width="10%">表示順</th>
                <th width="10%">ステータス</th>
                <th width="10%"><!-- 詳細/編集/削除ボタン --></th>
              </tr>
            @endif
          </thead>
          <tbody>
            @foreach($sysTypes as $sysType)
              <tr>
                <!-- システム種別名 -->
                <td>{{ $sysType->name }}</td>
                <!-- 表示順 -->
                <td>{{ $sysType->sort_order }}</td>
                <!-- ステータス -->
                <td>{{ $sysType->delete_flag ? '非表示' : '表示' }}</td>
                <td nowrap>
                  <!-- 編集/削除/復活ボタン -->
                  <a href="/admin/system-type/modify?id={{ $sysType->id }}">
                    <button type="button" class="btn btn-warning btn-xs">編集</button>
                  </a>
                  @if(!$sysType->delete_flag)
                    <a href="/admin/system-type/delete?id={{ $sysType->id }}&sort_order={{ $sysType->sort_order }}" onClick="javascript:return confirm('本当に削除しますか？')">
                      <button type="button" class="btn btn-danger btn-xs">削除</button>
                    </a>
                  @else
                    <a href="/admin/system-type/insert?id={{ $sysType->id }}" onClick="javascript:return confirm('本当に復活させますか？')">
                      <button type="button" class="btn btn-primary btn-xs">復活</button>
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="pull-right">
          {!! $sysTypes->appends([
            'name'    => $data_query['name'],
            'enabled' => $data_query['delete_flag'],
          ])->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
