@extends('admin.common.layout')
@section('title', 'ユーザ編集')

@section('content')
<?php
use App\Libraries\HtmlUtility;
use App\Libraries\SessionUtility as ssnUtil;
use App\Libraries\AdminUtility as admnUtil;
 ?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">ユーザ情報</div>
	  </div>
	  <div class="panel-body">
        <form class="form-horizontal" role="form" method="POST" name="userForm" action="{{ url('/admin/user/modify') }}">
@if(count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
@foreach($errors->all() as $error)
              <li>{{ $error }}</li>
@endforeach
            </ul>
          </div>
@endif
          <fieldset>
            <legend style="font-size:16px">ログイン情報</legend>
            <div class="form-group">
              <label for="inputAdminName" class="col-md-2 control-label">管理者名</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="inputAdminName" name="admin_name" maxlength="30" value="{{ HtmlUtility::setTextValueByRequest($user->admin_name, old('admin_name')) }}" {{ session('user_session_key_master_flg') == 0 ? "readonly='readonly'" : "placeholder=管理者名" }}>
              </div>
            </div>
            <div class="form-group">
              <label for="inputLoginId" class="col-md-2 control-label">ログインID</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="inputLoginId" name="login_id" value="{{ HtmlUtility::setTextValueByRequest($user->login_id, old('login_id')) }}" maxlength="20" {{ session('user_session_key_master_flg') == 0 ? "readonly='readonly'" : "placeholder=ログインID" }}>
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

@if(session(ssnUtil::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
              <!-- 編集者：マスター管理者　編集対象：一般管理者 のみ表示 -->
            <div class="alert alert-warning">
              <ul>
                <li>
                  検索・照会はデフォルトチェックです。</br>
                  チェックを外すと各機能はサイドバーに表示されなくなり、ユーザーは一切の権限を持たなくなります。
                </li>
              </ul>
            </div>
@endif

            <div class="form-group management">
              <label class="col-md-2 control-label">案件権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="3" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 3) }} class="js__default-check">検索・照会</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="2" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 2) }}>新規登録</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="4" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 4) }}>更新</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="5" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 5) }}>削除</label>
              </div>
            </div>

            <div class="form-group management">
              <label class="col-md-2 control-label">会員権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="7" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 7) }} class="js__default-check">検索・照会</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="9" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 9) }}>削除</label>
              </div>
            </div>

            <div class="form-group management">
              <label class="col-md-2 control-label">エントリー権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="11" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 11) }} class="js__default-check">検索・照会</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="13" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 13) }}>削除</label>
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="14" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 14) }}>スキルシートDL</label>
              </div>
            </div>

            <div class="form-group management">
              <label class="col-md-2 control-label">メルマガ権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="15" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 15) }} class="js__default-check">検索・照会</label>
              </div>
            </div>

            <div class="form-group management">
              <label class="col-md-2 control-label">スライド画像権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="16" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 16) }} class="js__default-check">スライド画像権限</label>
              </div>
            </div>

            <div class="form-group management">
              <label class="col-md-2 control-label">特集記事紐付け権限</label>
              <div class="col-md-10">
                <label class="checkbox-inline">
                  <input type="checkbox" name="auths[]" value="17" {{ HtmlUtility::isCheckedOldRequest($authList, old('auths'), 17) }} class="js__default-check">特集記事紐付け権限</label>
              </div>
            </div>

            <div class="col-md-10 text-right">
              <button type="submit" class="btn btn-primary" onclick="checkbox_copy()">更新</button>
@if(!session(ssnUtil::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
              <a href="/admin/top"><button type="button" class="btn btn-default">キャンセル</button></a>
@else
              <a href="/admin/user/list"><button type="button" class="btn btn-default">キャンセル</button></a>
@endif
            </div>
            <input type="hidden" name="master_flg" value="{{ $master_flg }}">
            <input type="hidden" name="isAuthsCheck" value="{{ !$master_flg && session(ssnUtil::SESSION_KEY_MASTER_FLG) }}">
            <input type="hidden" name="id" value="{{ $user->id }}">
            {{ csrf_field() }}
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
{
    var master_flg = JSON.parse('<?=json_encode(session(ssnUtil::SESSION_KEY_MASTER_FLG));?>');
    var edit_master_flg = JSON.parse('<?=json_encode($master_flg);?>');

    // マスター -> マスター
    if (master_flg && edit_master_flg) {
        $('.management input[type="checkbox"]').each(function (){
            $(this).attr('checked', 'checked');
            $(this).attr('disabled', 'disabled');
        });
    // マスター -> 一般
    } else if (master_flg && !edit_master_flg) {
        // init
        var $d_check = $('.js__default-check');
        for (var i = 0; i < $d_check.length; i++) {
            var $child = $d_check.eq(i).parents('.management').find('input:not(:first)');
            if ($d_check.eq(i).is(':checked')) {
                $child.removeAttr('disabled');
            } else {
                $child.attr('disabled','disabled');
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
    // 一般 -> 一般
    } else if (!master_flg && !edit_master_flg) {
        $('.management input[type="checkbox"]').each(function (){
            $(this).attr('disabled', 'disabled');
        });
    }
}
</script>
@endsection
