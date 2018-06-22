@extends('admin.common.layout')
@section('title', '親カテゴリーをコピーして登録')
@section('content')
<?php 
use App\Libraries\HtmlUtility;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">
                    親カテゴリー「<span style="font-weight: bold;">{{ $copyParent->name }}</span>」をコピーして登録
                </div>
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
                    <div class="col-md-4">
                        親カテゴリー情報入力
                        （<font color="#FF0000">*</font>は入力必須項目）
                    </div>
                </div>
                </br>
                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/category/copy-child-input') }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputParentName" class="col-md-2 control-label">親カテゴリー名
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputParentName" name="category_name[]" value="{{ old('category_name.0') }}" maxlength="20" placeholder="親カテゴリー名">
                                <span>(20文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">                 
                            <label for="selectParentSort" class="col-md-2 control-label">表示順
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-sm-8">
                                <span class="selectBox">
                                    <select id="js-slctBx-birth_y" class="form-control" name="parent_sort[]">
                                        @for($sortNum=1; $sortNum<=$sortMax; $sortNum++)
                                            <option @if(old('parent_sort.0') == $sortNum) selected @endif value="{{ $sortNum }}">{{ $sortNum }}</option>
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
                                <input type="text" class="form-control" id="inputPageTitle" name="page_title[]" value="{{ old('page_title.0') }}" maxlength="50" placeholder="ページタイトル">
                                <span>(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageKeywords" class="col-md-2 control-label">ページキーワード
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageKeywords" name="page_keywords[]"  placeholder="ページキーワード" rows="4" >{{ old('page_keywords.0') }}</textarea>
                                <span>(255文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageDescription" class="col-md-2 control-label">ページディスクリプション
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageDescription" name="page_description[]"  placeholder="ページディスクリプション" rows="5" >{{ old('page_description.0') }}</textarea>
                                <span>(255文字まで)</span>
                            </div>
                        </div>
                        @if(count($copysChild) > 0)
                            <div class="form-group">
                                <label for="inputArea" class="col-md-2 control-label">コピーして登録する子カテゴリー</label>
                                <div class="col-md-8">
                                    @foreach($copysChild as $child)
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="copyChildren" name="children[]" value="{{ $child->id }}" {{ HtmlUtility::isChecked(old('children.0'), $child->id) }} >{{ $child->name }}
                                        </label>
                                    @endforeach
                                    </br>
                                </div>
                            </div>
                        @endif
                        </br>
                        </br>
                        {{ csrf_field() }}
                        <div class="col-md-10">
                            <div class="text-right">
                                <button type="submit" class="btn btn-md btn-primary">
                                    <span class="submitBtn">&nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;</span>
                                    <span class="nextBtn">&nbsp;&nbsp;&nbsp;次へ&nbsp;&nbsp;&nbsp;</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ $copyParent->id }}">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
