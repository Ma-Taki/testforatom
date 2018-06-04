@extends('admin.common.layout')
@section('title', 'お知らせ編集(管理画面)')
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
<?php 
use App\Libraries\HtmlUtility;
?>

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">お知らせ編集(管理画面)</div>
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
                <form class="form-horizontal" name="adminNewsForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/admin-news/modify') }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputDate" class="col-md-2 control-label">日付
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="datepicker" name="release_date" value="{{ HtmlUtility::setTextValueByRequest($news->release_date->format('Y/m/d'), old('release_date')) }}" maxlength="10" readonly="readonly"/>
                                <span>(YYYY/MM/DD形式)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputTitle" class="col-md-2 control-label">タイトル
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputTitle" name="title" value="{{ HtmlUtility::setTextValueByRequest($news->title, old('tilte')) }}" maxlength="200" placeholder="タイトル">
                                <span>(200文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputContents" class="col-md-2 control-label">内容
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageKeywords" name="contents"  placeholder="内容" rows="30" >{{ HtmlUtility::setTextValueByRequest($news->contents, old('contents')) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-md-2 control-label">ステータス</label>
                            <div class="col-sm-8">
                                <p style="padding:6px 0 0 0;">{{ $news->delete_flag ? '非表示' : '表示' }}</p>
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-md btn-primary">更新</button>
                            <a href="/admin/admin-news/search">
                                <button type="button" class="btn btn-default">キャンセル</button>
                            </a>    
                        </div>
                        <input type="hidden" name="id" value="{{ $news->id }}">
                        {{ csrf_field() }}
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection