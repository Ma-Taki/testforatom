<?php
use App\Libraries\SessionUtility;
use App\Libraries\UserUtility;
?>
@if (!strstr(Request::url(), '/admin/login'))
<div class="col-md-2">
    <div class="sidebar content-box" style="display: block;">
        <ul class="nav">
            <li class="current"><a href="/admin/top"><i class="glyphicon glyphicon-home"></i> メイン</a></li>
@if(UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_ITEM_READ)
    || UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_MASTER))
            <li class="submenu">
                <a href="#">
                    <i class="glyphicon glyphicon-briefcase"></i> 案件管理
                    <span class="caret pull-right"></span>
                </a>
                <ul>
                    <li><a href="#">案件一覧</a></li>
                    <li><a href="#">案件登録</a></li>
                </ul>
            </li>
@endif
@if(UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_MEMBER_READ)
    || UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_MASTER))
            <li><a href="/admin/member/list"><i class="glyphicon glyphicon-user"></i> 会員管理</a></li>
@endif
@if(UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_ENTRY_READ)
    || UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_MASTER))
            <li><a href="/admin/entry/search"><i class="glyphicon glyphicon-file"></i> エントリー管理</a></li>
@endif
@if(UserUtility::isDisplaySubMenu(UserUtility::AUTH_TYPE_MASTER))
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
