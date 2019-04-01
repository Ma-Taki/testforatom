<!DOCTYPE HTML>
<html ⚡>
<head>
<meta charset="utf-8">
<title><?php wp_title(''); ?></title>
<meta name="description" content=<?php description(); ?> />
<meta name="keywords" content="IT案件,案件情報,求人,案件,仕事,フリーランス,フリーエンジニア,個人事業主,エンジニア,Java,PHP,コラム">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link rel="icon" href="/front/favicon.ico">
<link rel="canonical" href=<?php echo esc_url(get_permalink()); ?>>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Concert+One">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "NewsArticle",
"mainEntityOfPage":{
"@type":"WebPage",
"@id":"<?php the_permalink(); ?>"
},
"headline": "<?php the_title();?>",
"image": {
"@type": "ImageObject",
"url": "<?php
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id, true);
?>
<?php echo $image_url[0]; ?>",
"height": 800,
"width": 800
},
"datePublished": "<?php the_time('Y/m/d') ?>",
"dateModified": "<?php the_modified_date('Y/m/d') ?>",
"author": {
"@type": "Person",
"name": "<?php the_author_meta('nickname'); ?>"
},
"publisher": {
"@type": "Organization",
"name": "<?php bloginfo('name'); ?>",
"logo": {
"@type": "ImageObject",
"url": "<?php bloginfo('template_directory'); ?>/images/logo.png",
"width": 130,
"height": 53
}
},
"description": "<?php echo mb_substr(strip_tags($post-> post_content), 0, 60); ?>…"
}
</script>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<style amp-custom><?php get_template_part('amp-style');?></style>
<script async src="https://cdn.ampproject.org/v0.js"></script>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
<script async custom-element="amp-gist" src="https://cdn.ampproject.org/v0/amp-gist-0.1.js"></script>
<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
</head>
<body <?php body_class(); ?>>
<amp-analytics type="gtag" data-credentials="include">
<script type="application/json">
{
  "vars" : {
    "gtag_id": "UA-2026154-2",
    "config" : {
      "UA-2026154-2": { "groups": "default" }
    }
  }
}
</script>
</amp-analytics>
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
            <form action="/item/keyword" method="get" target="_top">
                <input type="text" name="keyword" placeholder="キーワードを入力" size="40" maxlength="255" class="searchBox">
                <button class="searchBtn" type="submit" />
            </form>
        </div><!-- /.search -->
    </div><!-- /.headerInr -->
</header>
<!-- END HEADER -->
<amp-sidebar id="syncer" layout="nodisplay" side="left">
    <nav class="nav">
        <ul class="nav-active">
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
            <li class="nav-item active">
                <a on="tap:syncer.toggle" style="background: #5e8796;">閉じる</a>
            </li>
        </ul>
    </nav>
</amp-sidebar>
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
    <a class="nav-mobile" on="tap:syncer.toggle"></a>
</nav>
<!-- END NAVI -->
<!-- PC用パンくず -->
<div class="invisible-sp">
<?php breadcrumb(); ?>
</div>
