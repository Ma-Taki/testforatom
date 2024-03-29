<?php
use App\Libraries\FrontUtility as FrntUtil;
?>
<nav class="nav">
    <ul class="nav-list">
        <li class="nav-item small"><a href="{{ url('/') }}">HOME</a></li>
        <li class="nav-item"><a href="{{ url('/about') }}">エンジニアルートとは</a></li>
        <li class="nav-item"><a href="{{ url('/item/search') }}">案件一覧</a></li>
        <li class="nav-item small"><a href="{{ url('/question') }}">Q&amp;A</a></li>
        <li class="nav-item"><a href="{{ url('/flow') }}">ご利用の流れ</a></li>
        <li class="nav-item"><a href="{{ url('/company') }}">企業の皆様へ</a></li>
        <li class="nav-item small"><a href="{{ url('/column') }}">コラム</a></li>
@if(FrntUtil::isLogin())
        <li class="invisible-pc invisible-tab nav-item"><a href="{{ url('/logout') }}">ログアウト</a></li>
@endif
    </ul>
</nav>
