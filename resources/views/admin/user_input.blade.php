@extends('admin.common.layout')
@section('title', 'ユーザ登録')
@section('content')
<style>
    .panel-title {
        font-size: 20px;
    }
</style>
<?php
use App\Libraries\HtmlUtility as HtmlUtil;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title">ユーザ登録</div>
			</div>
		    <div class="panel-body">
@if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
@foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
@endforeach
                    </ul>
                </div>
@endif
                <form class="form-horizontal" name="userForm" role="form" method="POST" action="{{ url('/admin/user/input') }}">
                    <fieldset>
                        <legend style="font-size:16px">ユーザ情報</legend>
                        <div class="form-group">
                            <label for="inputAdminName" class="col-md-2 control-label">管理者名</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputAdminName" name="admin_name" value="{{ old('admin_name') }}" placeholder="管理者名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLoginId" class="col-md-2 control-label">ログインID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputLoginId" name="login_id" value="{{ old('login_id') }}" maxlength="20" placeholder="ログインID">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-md-2 control-label">パスワード</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="inputPassword" name="password" maxlength="20" placeholder="パスワード">
                            </div>
                        </div>
                        </br>
                        <legend style="font-size:16px">権限情報</legend>
                            <div class="alert alert-warning">
                                <ul>
                                    <li>検索・照会はデフォルトチェックです。</br>
                                        チェックを外すと各機能はサイドバーに表示されなくなり、ユーザーは一切の権限を持たなくなります。
                                    </li>
                                </ul>
                            </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">案件権限</label>
                            <div class="col-md-10 management">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="3" {{ HtmlUtil::isChecked(old('auths'), 3) }} @if(old('auths') == null) checked @endif class="js__default-check">検索・照会（タグ一覧を含む）
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="2" {{ HtmlUtil::isChecked(old('auths'), 2) }}>新規登録
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="4" {{ HtmlUtil::isChecked(old('auths'), 4) }}>更新
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="5" {{ HtmlUtil::isChecked(old('auths'), 5) }}>削除
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">会員権限</label>
                            <div class="col-md-10 management">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="7" {{ HtmlUtil::isChecked(old('auths'), 7) }} @if(old('auths') === null) checked @endif class="js__default-check">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="9" {{ HtmlUtil::isChecked(old('auths'), 9) }}>削除</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">エントリー権限</label>
                            <div class="col-md-10 management">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="11" {{ HtmlUtil::isChecked(old('auths'), 11) }} @if(old('auths') == null) checked @endif class="js__default-check">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="13" {{ HtmlUtil::isChecked(old('auths'), 13) }}>削除</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="14" {{ HtmlUtil::isChecked(old('auths'), 14) }}>スキルシートDL</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">メルマガ権限</label>
                            <div class="col-md-10 management">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" value="15" {{ HtmlUtil::isChecked(old('auths'), 15) }}>メルマガ配信
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-primary">登録</button>
                            <a href="/admin/user/list"><button type="button" class="btn btn-default">キャンセル</button></a>
                        </div>
                        {{ csrf_field() }}
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    // init
    var $d_check = $('.js__default-check');
    for (var i = 0; i < $d_check.length; i++) {
        var $child = $d_check.eq(i).parents('.management').find('input:not(:first)');
        if ($d_check.eq(i).prop('checked')) {
            $child.prop('disabled','');
        } else {
            $child.prop('disabled','disabled');
        }
    }

    $('.js__default-check').change(function() {
        var $input = $(this).parents('.management').find('input:not(:first)');
        if ($(this).is(':checked')) {
            $input.removeAttr('disabled');
        } else {
            $input.attr('disabled', 'disabled');
        }
    });

</script>
@endsection
