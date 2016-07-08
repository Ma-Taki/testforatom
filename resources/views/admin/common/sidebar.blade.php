<?php
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
?>
@if (!strstr(Request::url(), '/admin/login'))
<div class="col-md-2">
    <div class="sidebar content-box" style="display: block;">
        <ul class="nav">
            <li class="current"><a href="/admin/top"><i class="glyphicon glyphicon-home"></i> トップ</a></li>

@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ITEM_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
            <li class="submenu">
                <a href="#">
                    <i class="glyphicon glyphicon-briefcase"></i> 案件管理
                    <span class="caret pull-right"></span>
                </a>
                <ul>
                    <li><a href="/admin/item/search">案件一覧</a></li>
                    <li><a href="/admin/item/input">案件登録</a></li>
                </ul>
            </li>
@endif

@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MEMBER_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
            <li><a href="/admin/member/search"><i class="glyphicon glyphicon-user"></i> 会員管理</a></li>
@endif

@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ENTRY_READ)
    || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
            <li><a href="/admin/entry/search"><i class="glyphicon glyphicon-file"></i> エントリー管理</a></li>
@endif

@if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
            <li class="submenu">
                <a href="#">
                    <i class="glyphicon glyphicon-wrench"></i> ユーザ管理
                    <span class="caret pull-right"></span>
                </a>
                <ul>
                    <li><a href="/admin/user/list">ユーザ一覧</a></li>
                    <li><a href="/admin/user/input">ユーザ登録</a></li>
                </ul>
            </li>
@endif

        </ul>
    </div>
</div>
@endif
