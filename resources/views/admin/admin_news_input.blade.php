@extends('admin.common.layout')
@section('title', 'お知らせ登録(管理画面)')
@section('content')
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
      todayHighlight: true,
  });
});
</script>
{{-- 相互反映用js --}}
<script src="{{ url('/admin/js/item.js') }}"></script>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">お知らせ登録(管理画面)</div>
            </div>
            <div class="panel-body">
                {{-- error：validation --}}
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
                            @foreach(Session::get('custom_error_messages') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4">お知らせ情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="newsForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/admin-news/input') }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputDate" class="col-md-2 control-label">日付
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">

                                <input type="text" class="datepicker" name="release_date" value="{{ old('release_date') }}" maxlength="10" readonly="readonly"/>
                                <span>(YYYY/MM/DD形式)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputTitle" class="col-md-2 control-label">タイトル
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputTitle" name="title" value="{{ old('tilte') }}" maxlength="200" placeholder="タイトル">
                                <span>(200文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputContents" class="col-md-2 control-label">内容
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageKeywords" name="contents"  placeholder="内容" rows="30" >{{ old('contents') }}</textarea>
                            </div>
                        </div>
                        </br>
                        </br>
                        {{ csrf_field() }}
                        <div class="col-md-10">
                            <div class="text-right">
                                <button type="submit" class="btn btn-md btn-primary">
                                    &nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection