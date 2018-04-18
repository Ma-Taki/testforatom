<?php
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
use Carbon\Carbon;
?>
@if (!strstr(Request::url(), '/admin/login'))
    <div class="col-md-2">
        <div class="sidebar content-box" style="display: block;">
            <ul class="nav">
                <li class="current">
                    <a href="/admin/top">
                        <i class="glyphicon glyphicon-home"></i> トップ
                    </a>
                </li>
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ITEM_READ) || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li class="submenu">
                        <a href="#">
                            <i class="glyphicon glyphicon-briefcase"></i> 案件管理
                            <span class="caret pull-right"></span>
                        </a>
                        <ul>
                            <li><a href="/admin/item/search">案件一覧</a></li>
                            <li><a href="/admin/item/input">案件登録</a></li>
                            <li><a href="/admin/item/tags">タグ一覧</a></li>
                            <li class="undermenu">
                            <a href="#">
                                <i class="glyphicon glyphicon-list-alt"></i> カテゴリー
                                <span class="caret pull-right"></span>
                            </a>
                                <ul style="display: none;">
                                    <li><a href="/admin/category/search">カテゴリー一覧</a></li>
                                    <li><a href="/admin/category/input?type=parent">親カテゴリー登録</a></li>
                                    <li><a href="/admin/category/input?type=child">子カテゴリー登録</a></li>
                                    <li><a href="/admin/category/list">トップページ表示管理</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MEMBER_READ) || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li>
                        <a href="/admin/member/search">
                            <i class="glyphicon glyphicon-user"></i> 会員管理
                        </a>
                    </li>
                @endif
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ENTRY_READ) || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li>
                        <a href="/admin/entry/search">
                            <i class="glyphicon glyphicon-file"></i> エントリー管理
                        </a>
                    </li>
                @endif
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MAIL_MAGAZINE) || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li class="submenu">
                        <a href="#">
                            <i class="glyphicon glyphicon-briefcase"></i> メルマガ管理
                            <span class="caret pull-right"></span>
                        </a>
                        <ul>
                            <li><a href="/admin/mail-magazine?type=new">新規作成</a></li>
                            <li><a href="/admin/mail-magazine/search">送信履歴</a></li>
                        </ul>
                    </li>
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
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li>
                        <a href="/admin/programming-lang-ranking">
                            <i class="glyphicon glyphicon-wrench"></i>人気言語ランキング
                        </a>
                    </li>
                @endif
                @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_SLIDE_IMAGE) || admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER))
                    <li class="submenu">
                        <a href="#">
                            <i class="glyphicon glyphicon-picture"></i> スライド画像管理
                            <span class="caret pull-right"></span>
                        </a>
                        <ul>
                            <li><a href="/admin/slide/list">画像一覧</a></li>
                            <li><a href="/admin/slide/input">画像登録</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endif
