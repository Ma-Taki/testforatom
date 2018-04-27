@extends('admin.common.layout')
@section('title', 'スキル登録')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">スキル登録</div>
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
                    <div class="col-md-4">スキル情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/skill/input') }}">
                    <fieldset>
                        <div class="form-group">    
                            <label for="inputSkillName" class="col-md-2 control-label">スキル名
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputSkillName" name="skill_name" value="{{ old('skill_name') }}" maxlength="20" placeholder="スキル名">
                                <span>(20文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="selectSkillCategoryName" class="col-md-2 control-label">スキルカテゴリー名
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-sm-8">
                                <span class="selectBox">
                                    <select id="js-slctBx-birth_y" class="form-control selectSkillCategoryName" id="selectSkillCategoryName" name="skill_category_id">
                                        @foreach($skillCategories as $skillCategory)
                                            <option @if(old('skill_category_id') == $skillCategory->id) selected @endif value="{{ $skillCategory->id }}">
                                                {{ $skillCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="selectSkillSort" class="col-md-2 control-label">表示順
                                <font color="#FF0000">*</font>
                            </label>
                            <div class="col-sm-8">
                                <span class="selectBox">
                                    <select id="js-slctBx-birth_y" class="form-control selectSkillSort" id="selectSkillSort" name="sort_order">
                                        @for($sortNum=1; $sortNum<=$sortMax; $sortNum++)
                                            <option @if(old('sort_order') == $sortNum) selected @endif value="{{ $sortNum }}">
                                                {{ $sortNum }}
                                            </option>
                                        @endfor
                                    </select>
                                </span>
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
