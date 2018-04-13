@extends('admin.common.layout')
@section('title', 'カテゴリー編集')
@section('content')

<?php 
use App\Libraries\HtmlUtility;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">カテゴリー編集</div>
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
                    <div class="col-md-4">カテゴリー情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="categoryForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/category/modify') }}">
                    <fieldset>
                        <div class="form-group">
                            @if(empty($category->parent_id))
                                <!-- 親のときの親カテゴリー名表示 -->
                                <label for="inputParentName" class="col-md-2 control-label">親カテゴリー名
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputParentName" name="name" value="{{ HtmlUtility::setTextValueByRequest($category->name, old('category_name')) }}" maxlength="20" placeholder="親カテゴリー名">
                                    <span>(20文字まで)</span>
                                </div>
                            @else
                                <!-- 子のときの親カテゴリー名セレクトボックス表示 -->
                                <label for="selectParentName" class="col-md-2 control-label">親カテゴリー名
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control selectParentName" id="selectParentName" name="parent_id">
                                            @foreach($parents as $parent)
                                                <option
                                                    @if(old('parent_id',$category->parent_id) == $parent->id) 
                                                        selected  
                                                    @endif 
                                                        value="{{ $parent->id }}">
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if(!empty($category->parent_id))
                            <div class="form-group">
                                <!-- 子のときのカテゴリー名表示 -->
                                <label for="inputChildName" class="col-md-2 control-label">子カテゴリー名        
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputChildName" name="name" value="{{ HtmlUtility::setTextValueByRequest($category->name, old('category_name')) }}" maxlength="20" placeholder="子カテゴリー名">
                                    <span>(20文字まで)</span>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            @if(empty($category->parent_id))
                                <!-- 親のときの表示順 -->
                                <label for="selectParentSort" class="col-md-2 control-label">表示順
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control" id="selectParentSort" name="parent_sort">
                                            @for($sortNum = 1; $sortNum <= $sortMax; $sortNum++)
                                                <option
                                                    @if(old('parent_sort',$category->parent_sort) == $sortNum)
                                                        selected 
                                                    @endif
                                                        value="{{ $sortNum }}">
                                                    {{ $sortNum }}
                                                </option>
                                            @endfor
                                        </select>
                                    </span>
                                </div>
                            @else
                                <!-- 子のときの表示順 -->
                                <label for="selectChildeSort" class="col-md-2 control-label">表示順
                                <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control selectChildeSort" id="selectChildeSort" name="child_sort">
                                            @for($sortNum = 1; $sortNum <= $sortMax; $sortNum++)
                                                <option 
                                                    @if(old('child_sort') == $sortNum)
                                                        selected
                                                    @endif
                                                        value="{{ $sortNum }}">
                                                    {{ $sortNum }}
                                                </option> 
                                            @endfor
                                        </select>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-md-2 control-label">ステータス</label>
                            <div class="col-sm-8">
                                <p style="padding:6px 0 0 0;">{{ $category->delete_flag ? '非表示' : '表示' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageTitle" class="col-md-2 control-label">ページタイトル
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputPageTitle" name="page_title" value="{{ HtmlUtility::setTextValueByRequest($category->page_title, old('page_title')) }}" maxlength="50" placeholder="ページタイトル">
                                <span>(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageKeywords" class="col-md-2 control-label">ページキーワード
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageKeywords" name="page_keywords"  placeholder="ページキーワード" rows="4" >{{ HtmlUtility::setTextValueByRequest($category->page_keywords, old('page_keywords')) }}</textarea>
                                <span>(255文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageDescription" class="col-md-2 control-label">ページディスクリプション
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageDescription" name="page_description"  placeholder="ページディスクリプション" rows="5" >{{ HtmlUtility::setTextValueByRequest($category->page_description, old('page_description')) }}</textarea>
                                <span>(255文字まで)</span>
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-md btn-primary">更新</button>
                            <a href="/admin/category/search">
                                <button type="button" class="btn btn-default">キャンセル</button>
                            </a>    
                        </div>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <input type="hidden" name="delete_flag" value="{{ $category->delete_flag }}">
                        @if(!empty($category->parent_id))
                            <!-- 子のとき -->
                            <input type="hidden" class="parentSort" name="parent_sort" value="{{ $category->parent_sort }}">
                        @endif
                        {{ csrf_field() }}
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection