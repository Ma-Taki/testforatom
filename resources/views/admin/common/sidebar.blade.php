@if (!strstr(Request::url(), '/admin/login'))
<div class="col-md-2">
    <div class="sidebar content-box" style="display: block;">
        <ul class="nav">
            <li class="current"><a href="#"><i class="glyphicon glyphicon-home"></i> メイン</a></li>
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
            <li><a href="/admin/member/list"><i class="glyphicon glyphicon-user"></i> 会員管理</a></li>
            <li><a href="/admin/entry/list"><i class="glyphicon glyphicon-file"></i> エントリー管理</a></li>

@if(session('user_session_key_master_flg') === '1')
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
