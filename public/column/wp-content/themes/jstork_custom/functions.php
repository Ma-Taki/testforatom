<?php

// 子テーマのstyle.cssを後から読み込む
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('style')
    );
}


// MOREタグの下に広告を表示
add_filter('the_content', 'adMoreReplace');
function adMoreReplace($contentData) {
if (is_mobile()){
$adTags = <<< EOF

<div class="add more">
<!--ここにスマホ用の広告コードをはりつけてください。-->

</div>

EOF;
} else{
$adTags = <<< EOF

<div class="add more">
<!--ここにPC用・タブレット用の広告コードをはりつけてください。-->

</div>
  
EOF;
}
    $contentData = preg_replace('/<span id="more-[0-9]+"><\/span>/', $adTags, $contentData);
    $contentData = str_replace('<p></p>', '', $contentData);
    $contentData = str_replace('<p><br />', '<p>', $contentData);
    return $contentData;
}