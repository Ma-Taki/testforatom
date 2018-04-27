@extends('admin.common.layout')
@section('title', 'スキルカテゴリー編集')
@section('content')
<?php 
use App\Libraries\HtmlUtility;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">スキルカテゴリー編集</div>
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
                    <div class="col-md-4">スキルカテゴリー情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="categoryForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/skill-category/modify') }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputSkillCategoryName" class="col-md-2 control-label">スキルカテゴリー名
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputSkillCategoryName" name="skillCategory_name" value="{{ HtmlUtility::setTextValueByRequest($skillCategory->name, old('skillCategory_name')) }}" maxlength="20" placeholder="スキルカテゴリー名">
                                <span>(20文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="selectSkillCategorySort" class="col-md-2 control-label">表示順
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-sm-8">
                                <span class="selectBox">
                                    <select id="js-slctBx-birth_y" class="form-control" id="selectSkillCategorySort" name="sort_order">
                                        @for($sortNum = 1; $sortNum <= $sortMax; $sortNum++)
                                            <option
                                                @if(old('sort_order',$skillCategory->sort_order) == $sortNum)
                                                    selected 
                                                @endif
                                                    value="{{ $sortNum }}">
                                                {{ $sortNum }}
                                            </option>
                                        @endfor
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-md-2 control-label">ステータス</label>
                            <div class="col-sm-8">
                                <p style="padding:6px 0 0 0;">{{ $skillCategory->delete_flag ? '非表示' : '表示' }}</p>
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-md btn-primary">更新</button>
                            <a href="/admin/skill-category/search">
                                <button type="button" class="btn btn-default">キャンセル</button>
                            </a>    
                        </div>
                        <input type="hidden" name="id" value="{{ $skillCategory->id }}">
                        {{ csrf_field() }}
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection