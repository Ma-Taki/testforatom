<?php
/*
<!doctype html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
*/?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<title><?php wp_title(''); ?></title>
<meta name="description" content=<?php description(); ?> />
<meta name="keywords" content="IT案件,案件情報,求人,案件,仕事,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP,コラム">
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<?php /*
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<?php if ( get_theme_mod( 'opencage_appleicon' ) ) : ?>
<link rel="apple-touch-icon" href="<?php echo get_theme_mod( 'opencage_appleicon' ); ?>">
<?php else : ?>
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
<?php endif; ?>
<?php if ( get_theme_mod( 'opencage_favicon' ) ) : ?>
<link rel="icon" href="<?php echo get_theme_mod( 'opencage_favicon' ); ?>">
<?php else : ?>
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/library/images/favicon.png">
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

<!--[if IE]>
<?php if ( get_theme_mod( 'opencage_favicon_ie' ) ) : ?>
<link rel="shortcut icon" href="<?php echo get_theme_mod( 'opencage_favicon_ie' ); ?>">
<?php else : ?>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/images/favicon.ico">
<?php endif; ?>
<![endif]-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
*/?>
<?php //get_template_part( 'head' ); ?>

<?php //wp_head(); ?>

<link rel="icon" href="/front/favicon.ico">

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); echo '?' . filemtime( get_stylesheet_directory() . '/style.css'); ?>" />
<link rel="stylesheet" type="text/css" href="/front/css/style.css<?php echo '?' . filemtime(__DIR__.'/../../../../../public/front/css/style.css');?>">

<link rel="canonical" href=<?php getCanonical(); ?>>
<!--
<link rel="stylesheet" type="text/css" href="/front/css/slick.css">
<link rel="stylesheet" type="text/css" href="/front/css/slick-theme.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/front/js/slick.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/front/js/jquery.tile.js"></script>
-->

</head>

<body <?php body_class(); ?>>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2026154-2', 'engineer-route.com');
ga('send', 'pageview');
</script>
<header>
  <div class="headerInr">

    <h1 class="header__text invisible-sp"><?php wp_title(''); ?></h1>

    <div class="user">
      <ul>
      <?php if(frontIsLogin()){ ?>
        <li><a href="/user">マイページ</a></li>
        <li><a href="/logout" class="invisible-sp">ログアウト</a></li>
      <?php }else{ ?>
        <li><a href="/user/regist/auth" class="signin">新規登録</a></li>
        <li><a href="/login">ログイン</a></li>
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
        <li class="nav-item small"><a href="/">HOME</a></li>
        <li class="nav-item"><a href="/about">エンジニアルートとは</a></li>
        <li class="nav-item"><a href="/item/search">案件一覧</a></li>
        <li class="nav-item small"><a href="/question">Q&amp;A</a></li>
        <li class="nav-item"><a href="/flow">ご利用の流れ</a></li>
        <li class="nav-item"><a href="/company">企業の皆様へ</a></li>
        <li class="nav-item active small" style="background: #536C75;"><a href="/column">コラム</a></li>
        <?php if(frontIsLogin()){ ?>
        <li class="invisible-pc invisible-tab nav-item"><a href="/logout">ログアウト</a></li>
        <?php } ?>
    </ul>
</nav>
<!-- END NAVI -->

<!-- PC用パンくず -->
<div class="invisible-sp">
<?php breadcrumb(); ?>
</div>

<?php /*
<div id="container" class="<?php echo esc_html(get_option('post_options_ttl'));?> <?php echo esc_html(get_option('side_options_sidebarlayout'));?> <?php echo esc_html(get_option('post_options_date'));?>">
<?php if(!is_singular( 'post_lp' ) ): ?>

<?php if ( get_option( 'side_options_description' ) ) : ?><p class="site_description"><?php bloginfo('description'); ?></p><?php endif; ?>
<header class="header animated fadeIn <?php echo esc_html(get_option('side_options_headerbg'));?> <?php if ( wp_is_mobile() ) : ?>headercenter<?php else:?><?php echo get_option( 'side_options_headercenter' ); ?><?php endif; ?>" role="banner">
<div id="inner-header" class="wrap cf">
<div id="logo" class="gf <?php echo esc_html(get_option('opencage_logo_size'));?>">
<?php if ( is_home() || is_front_page() ) : ?>
<?php if ( get_theme_mod( 'opencage_logo' ) ) : ?>
<h1 class="h1 img"><a href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo get_theme_mod( 'opencage_logo' ); ?>" alt="<?php bloginfo('name'); ?>"></a></h1>
<?php else : ?>
<h1 class="h1 text"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></h1>
<?php endif; ?>
<?php else: ?>
<?php if ( get_theme_mod( 'opencage_logo' ) ) : ?>
<p class="h1 img"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_theme_mod( 'opencage_logo' ); ?>" alt="<?php bloginfo('name'); ?>"></a></p>
<?php else : ?>
<p class="h1 text"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></p>
<?php endif; ?>
<?php endif; ?>
</div>

<?php if (!is_mobile()):?>
<nav id="g_nav" role="navigation">
<?php if(!get_option('side_options_header_search')):?>
<a href="#searchbox" data-remodal-target="searchbox" class="nav_btn search_btn"><span class="text gf">search</span></a>
<?php endif;?>

<?php wp_nav_menu(array(
     'container' => false,
     'container_class' => 'menu cf',
     'menu' => __( 'グローバルナビ' ),
     'menu_class' => 'nav top-nav cf',
     'theme_location' => 'main-nav',
     'before' => '',
     'after' => '',
     'link_before' => '',
     'link_after' => '',
     'depth' => 0,
     'fallback_cb' => ''
)); ?>
</nav>
<?php elseif(!get_option('side_options_header_search')):?>
<a href="#searchbox" data-remodal-target="searchbox" class="nav_btn search_btn"><span class="text gf">search</span></a>
<?php endif;?>

<a href="#spnavi" data-remodal-target="spnavi" class="nav_btn"><span class="text gf">menu</span></a>


</div>
</header>

<?php if (is_active_sidebar('sidebar-sp')):?>
<div class="remodal" data-remodal-id="spnavi" data-remodal-options="hashTracking:false">
<button data-remodal-action="close" class="remodal-close"><span class="text gf">CLOSE</span></button>
<?php dynamic_sidebar( 'sidebar-sp' ); ?>
<button data-remodal-action="close" class="remodal-close"><span class="text gf">CLOSE</span></button>
</div>

<?php else:?>

<div class="remodal" data-remodal-id="spnavi" data-remodal-options="hashTracking:false">
<button data-remodal-action="close" class="remodal-close"><span class="text gf">CLOSE</span></button>
<?php wp_nav_menu(array(
     'container' => false,
     'container_class' => 'sp_g_nav menu cf',
     'menu' => __( 'グローバルナビ' ),
     'menu_class' => 'sp_g_nav nav top-nav cf',
     'theme_location' => 'main-nav',
     'before' => '',
     'after' => '',
     'link_before' => '',
     'link_after' => '',
     'depth' => 0,
     'fallback_cb' => ''
)); ?>
<button data-remodal-action="close" class="remodal-close"><span class="text gf">CLOSE</span></button>
</div>

<?php endif; ?>


<?php if(!get_option('side_options_header_search')):?>
<div class="remodal searchbox" data-remodal-id="searchbox" data-remodal-options="hashTracking:false">
<div class="search cf"><dl><dt>キーワードで記事を検索</dt><dd><?php get_search_form(); ?></dd></dl></div>
<button data-remodal-action="close" class="remodal-close"><span class="text gf">CLOSE</span></button>
</div>
<?php endif;?>



<?php if(is_mobile()):?>
<div class="g_nav-sp animated fadeIn">
<?php wp_nav_menu(array(
     'container' => 'nav',
     'container_class' => 'menu-sp cf',
     'menu' => __( 'グローバルナビ（スマートフォン）' ),
     'menu_class' => 'top-nav',
     'theme_location' => 'main-nav-sp',
     'before' => '',
     'after' => '',
     'link_before' => '',
     'link_after' => '',
     'depth' => 0,
     'fallback_cb' => ''
)); ?>
</div>
<?php endif;?>

<?php if ( get_option('other_options_headerunderlink') && get_option('other_options_headerundertext') ) : ?>
<div class="header-info <?php echo esc_html(get_option('side_options_headerbg'));?>"><a href="<?php echo esc_html(get_option('other_options_headerunderlink'));?>"><?php echo esc_html(get_option('other_options_headerundertext'));?></a></div>
<?php endif;?>


<?php get_template_part( 'parts_homeheader' ); ?>

<?php breadcrumb(); ?>
<?php endif; ?>
*/?>