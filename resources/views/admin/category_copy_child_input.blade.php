@extends('admin.common.layout')
@section('title', '子カテゴリーをコピーして登録')
@section('content')
<?php 
use App\Libraries\HtmlUtility;

//コピーをする子供の情報があるとき
if(Session::has('copysChild')){
    $copysChild = Session::get('copysChild');
}
//子のコピー数があるとき
if(Session::has('copysChildNum')){
    $copysChildNum = Session::get('copysChildNum');
}
//最大表示順があるとき
if(Session::has('sortMax')){
    $sortMax = Session::get('sortMax');
}
//コピーする親の情報・子コピー設置先の親があるとき
if(Session::has('parents')){
    $parents = Session::get('parents');
}
//入力した親の名前があるとき
if(Session::has('parentName')){
    $parentName = Session::get('parentName');
}
?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
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

                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/category/copy-child-input') }}">
                    @foreach($copysChild as $copyCount => $copyChild)
                        <div>
                            <div style="font-size:20px;padding-bottom:20px;">
                                子カテゴリー「<span style="font-weight: bold;">{{ $copyChild->name }}</span>」をコピーして登録
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                子カテゴリー情報入力 
                                （<font color="#FF0000">*</font>は入力必須項目）
                            </div>
                        </div>
                        </br>
                        <fieldset>
                            <div class="form-group">
                                @if($copysChildNum > 0)
                                    <label for="selectParentName" class="col-md-2 control-label">親カテゴリー名</label>
                                    <div class="col-sm-8" style="padding-top:5px;">{{ $parentName }}</div>
                                @else
                                    <label for="selectParentName" class="col-md-2 control-label">親カテゴリー名</label>
                                    <div class="col-sm-8">
                                        <span class="selectBox">
                                            <select id="js-slctBx-birth_y" class="form-control selectParentName" id="selectParentName" name="parent_id[]">
                                                @foreach($parents as $parent)
                                                    <option @if(old('parent_id.'.$copyCount) == $parent->id) selected @endif value="{{ $parent->id }}">
                                                        {{ $parent->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="inputChildName" class="col-md-2 control-label">子カテゴリー名        
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputChildName" name="category_name[]" value="{{ old('category_name.'.$copyCount) }}" maxlength="20" placeholder="子カテゴリー名">
                                    <span>(20文字まで)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="selectChildeSort" class="col-md-2 control-label">表示順
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control selectChildeSort" id="selectChildeSort" name="child_sort[]">
                                            @for($sortNum=1; $sortNum<=$sortMax; $sortNum++)
                                                <option @if(old('child_sort.'.$copyCount) == $sortNum) selected @endif value="{{ $sortNum }}">{{ $sortNum }}</option>
                                            @endfor
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPageTitle" class="col-md-2 control-label">ページタイトル
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputPageTitle" name="page_title[]" value="{{ old('page_title.'.$copyCount) }}" maxlength="50" placeholder="ページタイトル">
                                    <span>(50文字まで)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPageKeywords" class="col-md-2 control-label">ページキーワード
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="inputPageKeywords" name="page_keywords[]" placeholder="ページキーワード" rows="4" >{{ old('page_keywords.'.$copyCount) }}</textarea>
                                    <span>(255文字まで)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPageDescription" class="col-md-2 control-label">ページディスクリプション
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="inputPageDescription" name="page_description[]" placeholder="ページディスクリプション" rows="5" >{{ old('page_description.'.$copyCount) }}</textarea>
                                    <span>(255文字まで)</span>
                                </div>
                            </div>
                            </br>
                            </br>
                            {{ csrf_field() }}
                            @if($copysChildNum > 0)
                                @if($copyCount == $copysChildNum - 1)
                                    <div class="col-md-10">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-md btn-primary">
                                                &nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-md-10">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-md btn-primary">
                                            &nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" class="parentSort" name="parent_sort[]" value="1">
                            <input type="hidden" name="id[]" value="{{ $copyChild->id }}">
                        </fieldset>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
@endsection