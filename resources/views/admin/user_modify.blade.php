@extends('admin.common.layout')
@section('title', 'ユーザ情報')
@section('content')
<script type="text/javascript">
function checkbox_inspection(element) {

    if("item_1" == element.id) {
         document.getElementById("item_2").disabled = !document.getElementById("item_1").checked;
         document.getElementById("item_3").disabled = !document.getElementById("item_1").checked;
         document.getElementById("item_4").disabled = !document.getElementById("item_1").checked;
    } else if ("member_1" == element.id) {
        document.getElementById("member_2").disabled = !document.getElementById("member_1").checked;
    } else if ("entry_1" == element.id) {
        document.getElementById("entry_2").disabled = !document.getElementById("entry_1").checked;
        document.getElementById("entry_3").disabled = !document.getElementById("entry_1").checked;
    }
}

function checkbox_copy()
{
    // チェックのついた権限配列を取得し、hiddenにセットする
    value_array = new Array();
    for (i=0; i<document.userForm.elements['auths[]'].length; i++ ){
        if(document.userForm.elements['auths[]'][i].checked == true){
            value_array.push(document.userForm.elements['auths[]'][i].value);
        }
    }
    document.userForm.elements['postAuths'].value = value_array;

    return true;
}
</script>
<?php
use App\Libraries\HtmlAttributeUtility;
use App\Libraries\SessionUtility;
use App\Libraries\UserUtility;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">ユーザ情報</div>
			</div>
		    <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" name="userForm" action="{{ url('/admin/user/update') }}">
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
                                <input type="text" class="form-control" id="inputAdminName" name="admin_name" value="{{ HtmlAttributeUtility::setTextValueByRequest($user->admin_name, old('admin_name')) }}" {{ session('user_session_key_master_flg') == 0 ? "readonly='readonly'" : "placeholder=ログインID" }}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLoginId" class="col-md-2 control-label">ログインID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputLoginId" name="login_id" value="{{ HtmlAttributeUtility::setTextValueByRequest($user->login_id, old('login_id')) }}" maxlength="20" {{ session('user_session_key_master_flg') == 0 ? "readonly='readonly'" : "placeholder=ログインID" }}>
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
@if(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                            <div class="alert alert-warning">
                                <ul>
                                    <li>検索・照会はデフォルトチェックです。</br>
                                        チェックを外すと各機能はサイドバーに表示されなくなり、ユーザーは一切の権限を持たなくなります。
                                    </li>
                                </ul>
                            </div>
@endif
                        <div class="form-group">
                            <label class="col-md-2 control-label">案件権限</label>
                            <div class="col-md-10">

@if(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 1)
                                <!-- 編集者：マスター管理者　編集対象：マスター管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>検索・照会</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>新規登録</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>更新</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>削除</label>
@elseif(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：マスター管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_1" value="3" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 3) }} onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_2" value="2" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 2) }}>新規登録</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_3" value="4" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 4) }}>更新</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_4" value="5" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 5) }}>削除</label>
@elseif(!session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：一般管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_1" value="3" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 3) }}>検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_2" value="2" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 2) }}>新規登録</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_3" value="4" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 4) }}>更新</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_4" value="5" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 5) }}>削除</label>
@endif

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">会員権限</label>
                            <div class="col-md-10">

@if(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 1)
                                <!-- 編集者：マスター管理者　編集対象：マスター管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>検索・照会</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>削除</label>
@elseif(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：マスター管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_1" value="7" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 7) }} onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_2" value="9" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 9) }}>削除</label>
@elseif(!session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：一般管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_1" value="7" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 7) }}>検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_2" value="9" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 9) }}>削除</label>
@endif

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">エントリー権限</label>
                            <div class="col-md-10">

@if(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 1)
                                <!-- 編集者：マスター管理者　編集対象：マスター管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>検索・照会</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>削除</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled="disabled" checked='checked'>スキルシートDL</label>
@elseif(session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：マスター管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_1" value="11" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 11) }} onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_2" value="13" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 13) }}>削除</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_3" value="14" {{ HtmlAttributeUtility::isCheckedOldRequest($authList, old('auths'), 14) }}>スキルシートDL</label>
@elseif(!session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0)
                                <!-- 編集者：一般管理者　編集対象：一般管理者 -->
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_1" value="11" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 11) }}>検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_2" value="13" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 13) }}>削除</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_3" value="14" disabled="disabled" {{ HtmlAttributeUtility::isChecked($authList, 14) }}>スキルシートDL</label>
@endif

                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-primary" onclick="checkbox_copy()">更新</button>
                            <a href="/admin/user/list"><button type="button" class="btn btn-default">キャンセル</button></a>
                        </div>
                        <input type="hidden" name="postAuths" value="">
                        <input type="hidden" name="master_flg" value="{{ $master_flg }}">
                        <input type="hidden" name="isAuthsCheck" value="{{ UserUtility::isValidationAuths($master_flg, session(SessionUtility::SESSION_KEY_MASTER_FLG)) }}">
                        <input type="hidden" name="admin_id" value="{{ $user->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@if(!(!session(SessionUtility::SESSION_KEY_MASTER_FLG) && $master_flg == 0))
<script type="text/javascript">
    checkbox_inspection(document.getElementById("item_1"));
    checkbox_inspection(document.getElementById("member_1"));
    checkbox_inspection(document.getElementById("entry_1"));
</script>
@endif
@endsection
