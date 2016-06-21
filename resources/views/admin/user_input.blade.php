@extends('admin.common.layout')
@section('title', 'ユーザ登録')
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
    // チェックのついた権限配列を取得し、hidden値にセットする
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

<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">ユーザ登録</div>
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
                <form class="form-horizontal" name="userForm" role="form" method="POST" action="{{ url('/admin/user/insert') }}">
                    <fieldset>
                        <legend style="font-size:16px">ログイン情報</legend>
                        <div class="form-group">
                            <label for="inputAdminName" class="col-md-2 control-label">管理者名</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputAdminName" name="inputAdminName" value="{{ old('inputAdminName') }}" placeholder="管理者名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputLoginId" class="col-md-2 control-label">ログインID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputLoginId" name="inputLoginId" value="{{ old('inputLoginId') }}" maxlength="20" placeholder="ログインID">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-md-2 control-label">パスワード</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="inputPassword" name="inputPassword" maxlength="20" placeholder="パスワード">
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
                            <div class="col-md-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_1" value="3" checked='checked' onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_2" value="2">新規登録</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_3" value="4">更新</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="item_4" value="5">削除</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">会員権限</label>
                            <div class="col-md-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_1" value="7" checked="checked" onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="member_2" value="9">削除</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">エントリー権限</label>
                            <div class="col-md-10">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_1" value="11" checked="checked" onclick="checkbox_inspection(this)">検索・照会
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_2" value="13">削除</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="auths[]" id="entry_3" value="14">スキルシートDL</label>
                            </div>
                        </div>
                        <div class="col-md-10 text-right">
                            <button type="submit" class="btn btn-primary">登録</button>
                            <a href="/admin/user/list"><button type="button" class="btn btn-default">キャンセル</button></a>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
