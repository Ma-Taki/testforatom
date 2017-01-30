<?php


function shortcode_empty_paragraph_fix($content) {
    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );
 
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'shortcode_empty_paragraph_fix');



// 関連記事を呼び出す
function kanrenFunc($atts) {
	extract(shortcode_atts(array(
		'mode' => null,'type' => null,'id' => null,
		'y' => null,'m' => null,'d' => null,
		'numberposts' => 5,'offset' => null,'order' => 'DESC','orderby' => 'post_date','meta_key' => null,
		'postid' => null,'exclude' => null,
		'head' => null,'tail' => null,
	),$atts));
 
	if($mode != null) $mode = '&'.$mode.'='.$id;
	$post = get_posts('post_status=publish&numberposts='.$numberposts.'&offset='.$offset.'&order='.$order.'&orderby='.$orderby.'&include='.$postid.'&year='.$y.'&monthnum='.$m.'&day='.$d.'&exclude='.get_the_ID().','.$exclude.'&meta_key='.$meta_key.$mode);
 
	foreach ($post as $item){
		$im = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'home-thum',false);
		$date = date('Y.m.d',strtotime(get_post($item->ID)->post_date));
		$update = date('Y.m.d',strtotime(get_post($item->ID)->post_modified));
		$echo .= '<div class="related_article cf"><a href="'.get_permalink($item->ID).'" class="cf"><figure class="thum"><img src="'.$im[0].'" /></figure><div class="meta inbox"><p class="ttl">'.$item->post_title.'</p><span class="date gf">'.$date.'</span></div></a></div>';
	}
 
	return $echo;
}
add_shortcode('kanren','kanrenFunc');


// 関連記事を呼び出す（ラベル無し）
function kanren2Func($atts) {
	extract(shortcode_atts(array(
		'mode' => null,'type' => null,'id' => null,
		'y' => null,'m' => null,'d' => null,
		'numberposts' => 5,'offset' => null,'order' => 'DESC','orderby' => 'post_date','meta_key' => null,
		'postid' => null,'exclude' => null,
		'head' => null,'tail' => null,
	),$atts));
 
	if($mode != null) $mode = '&'.$mode.'='.$id;
	$post = get_posts('post_status=publish&numberposts='.$numberposts.'&offset='.$offset.'&order='.$order.'&orderby='.$orderby.'&include='.$postid.'&year='.$y.'&monthnum='.$m.'&day='.$d.'&exclude='.get_the_ID().','.$exclude.'&meta_key='.$meta_key.$mode);
 
	foreach ($post as $item){
		$im = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'home-thum',false);
		$date = date('Y.m.d',strtotime(get_post($item->ID)->post_date));
		$update = date('Y.m.d',strtotime(get_post($item->ID)->post_modified));
		$echo .= '<div class="related_article labelnone cf"><a href="'.get_permalink($item->ID).'" class="cf"><figure class="thum"><img src="'.$im[0].'" /></figure><div class="meta inbox"><p class="ttl">'.$item->post_title.'</p><span class="date gf">'.$date.'</span></div></a></div>';
	}
 
	return $echo;
}
add_shortcode('kanren2','kanren2Func');



//グリッド　wrap
function colwrapFunc( $atts, $content = null ) {
	extract( shortcode_atts( array(
        'class' => '',
    ), $atts ) );
    
    return '<div class="column-wrap cf ' . $class . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('colwrap', 'colwrapFunc');

//グリッド　デスクトップ・ダブレット2カラム 以下1カラム
function col2Func( $atts, $content = null ) {
	extract( shortcode_atts( array(
        'class' => '',
    ), $atts ) );
    
    return '<div class="d-1of2 t-1of2 m-all ' . $class . '">' . do_shortcode($content) . '</div>';
}
add_shortcode('col2', 'col2Func');

//グリッド　デスクトップ・タブレット3カラム 以下1カラム
function col3Func( $atts, $content = null ) {
    return '<div class="d-1of3 t-1of3 m-all">' . do_shortcode($content) . '</div>';
}
add_shortcode('col3', 'col3Func');

// CTA
function ctainnerFunc( $atts, $content = null ) {
    return '<div class="cta-inner cf">' . do_shortcode($content) . '</div>';
}
add_shortcode('cta_in', 'ctainnerFunc');

//CTA COPYWRITING
function ctacopyFunc( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'class' => '',
    ), $atts ) );
     
    return '<h2 class="cta_copy"><span>' . $content . '</span></h2>';
}
add_shortcode('cta_ttl', 'ctacopyFunc');


// CTAボタン
function ctabtnFunc( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'link' => '',
    ), $atts ) );
     
    return '<div class="btn-wrap aligncenter big lightning cta_btn"><a href="' . $link . '">' . $content . '</a></div>';
}
add_shortcode('cta_btn', 'ctabtnFunc');

// 補足説明・注意
function asideFunc( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'type' => '',
    ), $atts ) );
     
    return '<div class="supplement '. $type . '">' . $content . '</div>';
}
add_shortcode('aside', 'asideFunc');


// ボタン
function btnFunc( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'class' => '',
    ), $atts ) );
     
    return '<div class="btn-wrap aligncenter ' . $class. '">' . $content . '</div>';
}
add_shortcode('btn', 'btnFunc');

//吹き出し
function voiceFunc( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'icon' => '',
        'type' => '',
        'name' => '',
    ), $atts ) );
     
    return '<div class="voice cf ' .$type. '"><figure class="icon"><img src="' . $icon . '"><figcaption class="name">' . $name . '</figcaption></figure><div class="voicecomment">' . do_shortcode($content) . '</div></div>';
}
add_shortcode('voice', 'voiceFunc');

?>