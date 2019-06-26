@extends('admin.common.layout')
@if($_GET["type"]=='parent')
    @section('title', '親カテゴリー登録')
@elseif($_GET["type"]=='child')
    @section('title', '子カテゴリー登録')
@endif
@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">
                    @if($_GET["type"]=='parent')
                        親カテゴリー登録
                    @elseif($_GET["type"]=='child')
                        子カテゴリー登録
                    @endif
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
                        @if($_GET["type"]=='parent')
                            親カテゴリー情報入力
                        @elseif($_GET["type"]=='child')
                            子カテゴリー情報入力
                        @endif
                        （<font color="#FF0000">*</font>は入力必須項目）
                    </div>
                </div>
                </br>
                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/category/input') }}">
                    <fieldset>
                        <div class="form-group">
                            @if($_GET["type"]=='parent')
                                <!-- 親のときの親カテゴリー名入力欄 -->
                                <label for="inputParentName" class="col-md-2 control-label">親カテゴリー名
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputParentName" name="category_name" value="{{ old('category_name') }}" maxlength="20" placeholder="親カテゴリー名">
                                    <span>(20文字まで)</span>
                                </div>
                            @elseif($_GET["type"]=='child')
                                <!-- 子のときの親カテゴリー名セレクトボックス表示 -->
                                <label for="selectParentName" class="col-md-2 control-label">親カテゴリー名
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control selectParentName" id="selectParentName" name="parent_id">
                                            @foreach($parents as $parent)
                                                <option @if(old('parent_id') == $parent->id) selected @endif value="{{ $parent->id }}">
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if($_GET["type"]=='child')
                            <div class="form-group">
                                <!-- 子のときのカテゴリー名表示 -->
                                <label for="inputChildName" class="col-md-2 control-label">子カテゴリー名        
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="inputChildName" name="category_name" value="{{ old('category_name') }}" maxlength="20" placeholder="子カテゴリー名">
                                    <span>(20文字まで)</span>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            @if($_GET["type"]=='parent')
                                <!-- 親のときの表示順 -->
                                <label for="selectParentSort" class="col-md-2 control-label">表示順
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control" id="selectParentSort" name="parent_sort">
                                            @for($sortNum=1; $sortNum<=$sortMax; $sortNum++)
                                                <option @if(old('parent_sort') == $sortNum) selected @endif value="{{ $sortNum }}">
                                                    {{ $sortNum }}
                                                </option>
                                            @endfor
                                        </select>
                                    </span>
                                </div>
                            @elseif($_GET["type"]=='child')
                                <!-- 子のときの表示順 -->
                                <label for="selectChildeSort" class="col-md-2 control-label">表示順
                                    <font color="#FF0000">*</font>
                                </label>
                                <div class="col-sm-8">
                                    <span class="selectBox">
                                        <select id="js-slctBx-birth_y" class="form-control selectChildeSort" id="selectChildeSort" name="child_sort"> 
                                            @for($sortNum=1; $sortNum<=$sortMax; $sortNum++)
                                                <option @if(old('child_sort') == $sortNum) selected @endif value="{{ $sortNum }}">
                                                    {{ $sortNum }}
                                                </option>
                                            @endfor
                                        </select>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="inputPageTitle" class="col-md-2 control-label">ページタイトル
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputPageTitle" name="page_title" value="{{ old('page_title') }}" maxlength="50" placeholder="ページタイトル">
                                <span>(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageKeywords" class="col-md-2 control-label">ページキーワード
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageKeywords" name="page_keywords"  placeholder="ページキーワード" rows="4" >{{ old('page_keywords') }}</textarea>
                                <span>(255文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPageDescription" class="col-md-2 control-label">ページディスクリプション
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="inputPageDescription" name="page_description"  placeholder="ページディスクリプション" rows="5" >{{ old('page_description') }}</textarea>
                                <span>(255文字まで)</span>
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
                        @if($_GET["type"]=='child')
                            <!-- 子のとき -->
                            <input type="hidden" class="parentSort" name="parent_sort" value="1">
                        @endif
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
