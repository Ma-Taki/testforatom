@extends('admin.common.layout')
@section('title', '特集記事紐付け登録')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">記事紐付け登録</div>
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
                    <div class="col-md-4">紐付け情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/column-connect/input') }}">
                    <fieldset>
                        <div class="form-group">    
                            <label for="inputConnectID" class="col-md-2 control-label">紐付けID
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputConnectID" name="connect_id" value="{{ old('connect_id') }}" maxlength="3" placeholder="紐付けID">
                                <span>(3文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">    
                            <label for="inputTitle" class="col-md-2 control-label">記事タイトル
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputTitle" name="title" value="{{ old('title') }}" maxlength="50" placeholder="記事タイトル">
                                <span>(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">    
                            <label for="inputKeyword" class="col-md-2 control-label">キーワード
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputKeyword" name="keyword"  placeholder="キーワード" rows="8" >{{ old('keyword') }}</textarea>
                                <span>(改行区切り・大文字小文字区別なし・3000文字まで)</span>
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