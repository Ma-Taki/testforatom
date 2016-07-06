<div class="header">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-offset-2 col-md-5">
                <div class="logo">
                    <h1><a href="/admin/top">Engineer-Route 管理画面</a></h1>
                </div>
            </div>
@if (!strstr(Request::url(), '/admin/login'))
            <div class="col-md-offset-3 col-md-2">
               <div class="navbar navbar-inverse" role="banner">
                   <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                       <ul class="nav navbar-nav">
                           <li class="dropdown">
                               <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ session('user_session_key_admin_name') }}<b class="caret"></b></a>
                               <ul class="dropdown-menu animated fadeInUp">
                                   <li><a href="/admin/user/modify?id={{ session('user_session_key_admin_id') }}">ユーザ情報</a></li>
                                   <li><a href="/admin/logout">ログアウト</a></li>
                               </ul>
                           </li>
                       </ul>
                   </nav>
               </div>
           </div>
@endif
        </div>
    </div>
</div>
