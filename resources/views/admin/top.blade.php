@extends('admin.common.layout')
@section('title', 'トップ')
@section('content')
<?php
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
?>
<div class="col-md-10">
{{-- info message --}}
@if(\Session::has('custom_info_messages'))
    <div class="alert alert-info">
        <ul>
            <li>{{ \Session::get('custom_info_messages') }}</li>
        </ul>
    </div>
@endif

@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ITEM_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
    <div class="row">
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title"><a href="/admin/item/search">案件管理</a></div>
            </div>
            <div class="content-box-large box-with-header">
                案件情報の照会、登録、修正、削除を行います。
                <br /><br />
            </div>
        </div>
    </div>
@endif
@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MEMBER_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
    <div class="row">
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title"><a href="/admin/member/search">会員管理</a></div>
            </div>
            <div class="content-box-large box-with-header">
                会員情報の照会、削除を行います。
                <br /><br />
            </div>
        </div>
    </div>
@endif
@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ENTRY_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
    <div class="row">
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title"><a href="/admin/entry/search">エントリー管理</a></div>
            </div>
            <div class="content-box-large box-with-header">
            	エントリー情報の照会、削除、スキルシートのダウンロードを行います。
                <br /><br />
            </div>
        </div>
    </div>
@endif
@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MAIL_MAGAZINE)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
    <div class="row">
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title"><a href="/admin/mail-magazine">メルマガ管理</a></div>
            </div>
            <div class="content-box-large box-with-header">
                メールマガジンの配信を行います。
                <br /><br />
            </div>
        </div>
    </div>
@endif
@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
    <div class="row">
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title"><a href="/admin/user/list">ユーザ管理</a></div>
            </div>
            <div class="content-box-large box-with-header">
                管理ユーザ情報の照会、登録、修正、削除を行います。
                <br /><br />
            </div>
        </div>
    </div>
@endif
</div>
@endsection
