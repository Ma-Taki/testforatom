<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<title><?php wp_title(''); ?></title>
<?php MataTitle(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link rel="icon" href="/front/favicon.ico">
<link rel="canonical" href=<?php getCanonical(); ?>>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); echo '?' . filemtime( get_stylesheet_directory() . '/style.css'); ?>" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo get_template_directory_uri(); ?>/column-scripts.js"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2026154-2', 'engineer-route.com');
ga('send', 'pageview');
</script>
</head>
<body <?php body_class(); ?>>
<header>
    <div class="headerInr">
        <h1 class="header__text invisible-sp"><?php wp_title(''); ?></h1>
        <div class="user">
            <ul>
                <li>
                    <a href="/considers">
                        <span>検討中</span><span><span id="considers_length"><?php echo culcConsiderLength() ?></span><span id="considers_unit"> 件</span></span>
                    </a>
                </li>
                <?php if(frontIsLogin()){ ?>
                    <li>
                        <a href="/user/entry">
                            <span>応募</span><span>履歴</span>
                        </a>
                    </li>
                    <li>
                        <a href="/user">
                            <span>マイ</span><span>ページ</span>
                        </a>
                    </li>
                    <li>
                        <a href="/logout" class="invisible-sp">
                            <span>ログ</span><span>アウト</span>
                        </a>
                    </li>
                <?php }else{ ?>
                    <li id="user-signin">
                        <a href="/user/regist" class="signin">新規登録</a>
                    </li>
                    <li>
                        <a href="/login"><span>ログ</span><span>イン</span></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="header__logo">
            <a href="/" title="エンジニアルート"></a>
        </div>
        <div class="search">
            <form action="/item/keyword" method="get">
                <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                <button class="searchBtn" type="submit" />
            </form>
        </div><!-- /.search -->
    </div><!-- /.headerInr -->
</header>
<!-- END HEADER -->
<nav class="nav">
    <ul class="nav-list">
        <li class="nav-item small">
            <a href="/">HOME</a>
        </li>
        <li class="nav-item">
            <a href="/about">エンジニアルートとは</a>
        </li>
        <li class="nav-item">
            <a href="/item/search">案件一覧</a>
        </li>
        <li class="nav-item small">
            <a href="/question">Q&amp;A</a>
        </li>
        <li class="nav-item">
            <a href="/flow">ご利用の流れ</a>
        </li>
        <li class="nav-item">
            <a href="/company">企業の皆様へ</a>
        </li>
        <li class="nav-item active small" style="background: #536C75;">
            <a href="/column">コラム</a>
        </li>
        <?php if(frontIsLogin()){ ?>
            <li class="invisible-pc invisible-tab nav-item">
                <a href="/logout">ログアウト</a>
            </li>
        <?php } ?>
    </ul>
</nav>
<!-- END NAVI -->
<!-- PC用パンくず -->
<div class="invisible-sp">
<?php breadcrumb(); ?>
</div>
