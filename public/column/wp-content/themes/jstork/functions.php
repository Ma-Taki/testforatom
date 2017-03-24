<?php

/*********************
include function file
*********************/
require_once( 'library/customizer.php' );
require_once( 'library/shortcode.php' );
require_once( 'library/widget.php' );
require_once( 'library/custom-post-type.php' );
require_once( 'library/admin.php' );

require __DIR__.'/../../../../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
use App\Libraries\FrontUtility;

function frontIsLogin(){
    return FrontUtility::isLogin();
}

/**
 * 正規化のためのURLを返却する
 **/
function getCanonical() {
    echo '"'. (empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]. '"';
}

/**
 * パンくず
 **/
function breadcrumb() {

    $breadcrumbs = '';

    $content_no = 0;

    $createBreadcrumbsBegin = function () use (&$breadcrumbs) {
        $breadcrumbs .= '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">' .PHP_EOL;
    };

    $addElement = function ($name, $url = '') use (&$breadcrumbs, &$content_no) {
        $breadcrumbs .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' .PHP_EOL;
        $breadcrumbs .= empty($url) ? '' : '<a class="hover-thin" itemprop="item" href="' .$url .'">' .PHP_EOL;
        $breadcrumbs .= '<span itemprop="name">' .$name .'</span>' .PHP_EOL;
        $breadcrumbs .= empty($url) ? '' : '</a>' .PHP_EOL;
        $breadcrumbs .= '<meta itemprop="position" content="' .++$content_no .'" /></span>' .PHP_EOL;
    };

    $addNext = function () use (&$breadcrumbs) {
        $breadcrumbs .= '<span class="next">></span>' .PHP_EOL;
    };

    $createBreadcrumbsEnd = function () use (&$breadcrumbs) {
        $breadcrumbs .= '</div><!-- END .breadcrumbs -->' .PHP_EOL;
    };

    $createBreadcrumbsBegin();

    $addElement('エンジニアルート', '/');
    $addNext($breadcrumbs);

    // 照会中のオブジェクトを取得する
    $queried_obj = get_queried_object();

    // コラムトップページ以外では"コラム"にリンクを設定する
    $column_url = empty($queried_obj) ? '' : '/column';
    $addElement('コラム', $column_url);

    // 表示中のページによって処理を分岐させる
    if (!empty($queried_obj) && get_class($queried_obj) == 'WP_Post') {
        // 記事のページ
        // 複数のカテゴリには属さない想定
        $post_cate = get_the_category($queried_obj->ID)[0];
        if (!empty($post_cate->category_parent)) {
            // 親カテゴリ
            $addNext();
            $addElement(get_cat_name($post_cate->category_parent), '/column/?cat='.$post_cate->category_parent);
        }

        // カテゴリ、または子カテゴリ
        $addNext($breadcrumbs);
        $addElement($post_cate->cat_name, '/column/?cat='.$post_cate->cat_ID);

        // 記事
        $addNext($breadcrumbs);
        $addElement($queried_obj->post_title);

    } else if (!empty($queried_obj) && get_class($queried_obj) == "WP_Term") {
        // カテゴリーのアーカイブページ
        if (!empty($queried_obj->category_parent)) {
            // 親カテゴリ
            $addNext();
            $addElement(get_cat_name($queried_obj->category_parent), '/column/?cat='.$queried_obj->category_parent);
        }
        // カテゴリ、または子カテゴリ
        $addNext($breadcrumbs);
        $addElement($queried_obj->cat_name);
    }

    $createBreadcrumbsEnd();

    echo $breadcrumbs;
}

/**
 * description
 **/
function description() {

    $description = '';

    if (is_home() || is_front_page()) {
        // トップページ
        $description = get_bloginfo('description');

    } else if (get_queried_object()) {
        if (is_category()) {
            // カテゴリページ
            $queried_obj = get_queried_object();
            $description = $queried_obj->cat_name. 'についてのコラム一覧ページです。';
            $description.= 'エンジニアルートでは、フリーランスエンジニアとして成功するための知識や、IT業界事情など、幅広い情報をお届けしています。';
        } else {
            // 記事ページ
            $queried_obj = get_queried_object();
            // htmlタグをすべて削除
            $description = strip_tags($queried_obj->post_content);
            // 先頭から100文字
            $description = mb_substr($description, 0, 100) . '...';
            // 改行を半角スペースに変換
            $description =  str_replace(PHP_EOL, ' ', $description);
        }
    }
    echo '"'. $description. '"';
}

/*********************
titleタグを最適化（ | でつなぐ）
*********************/
if (!function_exists('rw_title')) {
	function rw_title( $title, $sep, $seplocation ) {

    /*
	  global $page, $paged;

	  if ( is_feed() ) return $title;

	  $sep = " | ";
	  if ( 'right' == $seplocation ) {
	    $title .= get_bloginfo( 'name' );
	  } elseif ( is_home() || is_front_page() ){
		$title = $title . get_bloginfo( 'name' );
	  } else {
	    $title = $title . "{$sep}" . get_bloginfo( 'name' );
	  }
	  $site_description = get_bloginfo( 'description', 'display' );
	  if ( $site_description && ( is_home() || is_front_page() ) ) {
	    $title .= "{$sep}{$site_description}";
	  }
	  if ( $paged >= 2 || $page >= 2 ) {
	    $title .= " {$sep} " . sprintf( __( '%sページ目', 'dbt' ), max( $paged, $page ) );
	  }
    */

    // "ページ名 | エンジニアルート" の形式
    $site_name = 'エンジニアルート';
    $sep = ' | ';
    $page_name = '';
    if (is_home() || is_front_page()) {
        $page_name = get_bloginfo('name');
    } else {
        $page_name = $title;
    }
    return $page_name. $sep .$site_name;
	}
}

function opencage_rss_version() { return ''; }

function opencage_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

function opencage_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

function opencage_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}


/*********************
SCRIPTS読み込み
*********************/
if (!is_admin()) {
	function register_script(){
	//IE判定
	$ieua = $_SERVER['HTTP_USER_AGENT'];
		wp_deregister_script( 'jquery' );
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js', array(), '1.12.2' );
		wp_register_script( 'css-modernizr', get_bloginfo('template_directory'). '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', true );
		wp_register_script( 'slick', get_bloginfo('template_directory'). '/library/js/libs/slick.min.js', array('jquery'), '1.5.9', true );
		wp_register_script( 'remodal', get_bloginfo('template_directory'). '/library/js/libs/remodal.js', array('jquery'), '1.0.0', true );
		wp_register_script( 'masonry.pkgd.min', get_bloginfo('template_directory'). '/library/js/libs/masonry.pkgd.min.js', array('jquery'), '4.0.0', true );
		wp_register_script( 'imagesloaded', get_bloginfo('template_directory'). '/library/js/libs/imagesloaded.js', array('jquery'), '4.1.0', true );
		if(!wp_is_mobile() && !strstr($ieua, 'Trident') && !strstr($ieua, 'MSIE') && (get_option('side_options_animatenone') == "ani_on")){
			wp_register_script( 'wow', get_bloginfo('template_directory'). '/library/js/libs/wow.min.js', array('jquery'), '', true );
		}
		wp_register_script( 'main-js', get_bloginfo('template_directory'). '/library/js/scripts.js', array( 'jquery' ), '', true );
	}
	function add_script() {
		register_script();
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'wow' );
			wp_enqueue_script( 'slick' );
			wp_enqueue_script( 'remodal' );
			wp_enqueue_script( 'masonry.pkgd.min' );
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'main-js' );
			wp_enqueue_script( 'css-modernizr' );
		if(is_front_page() || is_home()) {
		}
		else {
		}
	}
	add_action('wp_print_scripts', 'add_script');
}

/*********************
CSS読み込み
*********************/
function register_style() {
	wp_register_style('style', get_bloginfo('template_directory').'/style.css');
	wp_register_style('shortcode', get_bloginfo('template_directory').'/library/css/shortcode.css');
	wp_register_style('slick', get_bloginfo('template_directory').'/library/css/slick.css');
	wp_register_style('remodal', get_bloginfo('template_directory').'/library/css/remodal.css');
	if((get_option('side_options_animatenone') == "ani_on")){
		wp_register_style('animate', get_bloginfo('template_directory').'/library/css/animate.min.css');
	}
	wp_register_style('lp_css', get_bloginfo('template_directory').'/library/css/lp.css');
}
	function add_stylesheet() {
		register_style();
			wp_enqueue_style('style');
			wp_enqueue_style('slick');
			wp_enqueue_style('animate');
			wp_enqueue_style('shortcode');
			wp_enqueue_style('remodal');
		if(is_singular( 'post_lp' )) {
			wp_enqueue_style('lp_css');
		}
		elseif (is_home() || is_front_page()) {
		}
	}
add_action('wp_print_styles', 'add_stylesheet');

/*********************
パンくずナビ
*********************/
if (!function_exists('breadcrumb')) {
	function breadcrumb($divOption = array("id" => "breadcrumb", "class" => "breadcrumb inner wrap cf")){
	    global $post;
	    $str ='';
	    if(!get_option('side_options_pannavi')){
		    if(!is_home()&&!is_front_page()&&!is_admin() ){
		        $tagAttribute = '';
		        foreach($divOption as $attrName => $attrValue){
		            $tagAttribute .= sprintf(' %s="%s"', $attrName, $attrValue);
		        }
		        $str.= '<div'. $tagAttribute .'>';
		        $str.= '<ul itemscope itemtype="//data-vocabulary.org/Breadcrumb">';
		        $str.= '<li><a href="'. home_url() .'/" itemprop="url"><i class="fa fa-home"></i><span itemprop="title"> HOME</span></a></li>';

		        if(is_category()) {
		            $cat = get_queried_object();
		            if($cat -> parent != 0){
		                $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
		                foreach($ancestors as $ancestor){
		                    $str.='<li><a href="'. get_category_link($ancestor) .'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor) .'</span></a></li>';
		                }
		            }
		            $str.='<li><span itemprop="title">'. $cat -> name . '</span></li>';
		        } elseif(is_single()){
		            $categories = get_the_category($post->ID);
		            $cat = $categories[0];
		            if($cat -> parent != 0){
		                $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
		                foreach($ancestors as $ancestor){
		                    $str.='<li><a href="'. get_category_link($ancestor).'" itemprop="url"><span itemprop="title">'. get_cat_name($ancestor). '</span></a></li>';
		                }
		            }
		            $str.='<li><a href="'. get_category_link($cat -> term_id). '" itemprop="url"><span itemprop="title">'. $cat-> cat_name . '</span></a></li>';
		            $str.= '<li>'. $post -> post_title .'</li>';
		        } elseif(is_page()){
		            if($post -> post_parent != 0 ){
		                $ancestors = array_reverse(get_post_ancestors( $post->ID ));
		                foreach($ancestors as $ancestor){
		                    $str.='<li><a href="'. get_permalink($ancestor).'" itemprop="url"><span itemprop="title">'. get_the_title($ancestor) .'</span></a></li>';
		                }
		            }
		            $str.= '<li><span itemprop="title">'. $post -> post_title .'</span></li>';
		        } elseif(is_date()){
					if( is_year() ){
						$str.= '<li>' . get_the_time('Y') . '年</li>';
					} else if( is_month() ){
						$str.= '<li><a href="' . get_year_link(get_the_time('Y')) .'">' . get_the_time('Y') . '年</a></li>';
						$str.= '<li>' . get_the_time('n') . '月</li>';
					} else if( is_day() ){
						$str.= '<li><a href="' . get_year_link(get_the_time('Y')) .'">' . get_the_time('Y') . '年</a></li>';
						$str.= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('n') . '月</a></li>';
						$str.= '<li>' . get_the_time('j') . '日</li>';
					}
					if(is_year() && is_month() && is_day() ){
						$str.= '<li>' . wp_title('', false) . '</li>';
					}
		        } elseif(is_search()) {
		            $str.='<li><span itemprop="title">「'. get_search_query() .'」で検索した結果</span></li>';
		        } elseif(is_author()){
		            $str .='<li><span itemprop="title">投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'</span></li>';
		        } elseif(is_tag()){
		            $str.='<li><span itemprop="title">タグ : '. single_tag_title( '' , false ). '</span></li>';
		        } elseif(is_attachment()){
		            $str.= '<li><span itemprop="title">'. $post -> post_title .'</span></li>';
		        } elseif(is_404()){
		            $str.='<li>ページがみつかりません。</li>';
		        } else{
		            $str.='<li>'. wp_title('', true) .'</li>';
		        }
		        $str.='</ul>';
		        $str.='</div>';
		    }
		}
	    echo $str;
	}
}

/*********************
ページネーション
*********************/
if (!function_exists('pagination')) {
	function pagination($pages = '', $range = 2){
	     global $wp_query, $paged;

		$big = 999999999;

		echo "<div class=\"pagination cf\">\n";
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'current' => max( 1, get_query_var('paged') ),
			'prev_text'    => __('prev'),
			'next_text'    => __('next'),
			'type'    => 'list',
			'total' => $wp_query->max_num_pages
		) );
		echo "</div>\n";
	}
}

// サーチフォームのソースコード
if (!function_exists('my_search_form')) {
	function my_search_form( $form ) {
		$form = '<form role="search" method="get" id="searchform" class="searchform cf" action="' . home_url( '/' ) . '" >
		<input type="search" placeholder="検索する" value="' . get_search_query() . '" name="s" id="s" />
		<button type="submit" id="searchsubmit" ><i class="fa fa-search"></i></button>
		</form>';
		return $form;
	}
	add_filter( 'get_search_form', 'my_search_form' );
}


// 独自アイキャッチ画像
if (!function_exists('add_mythumbnail_size')) {
	function add_mythumbnail_size() {
	add_theme_support('post-thumbnails');
	add_image_size( 'home-thum', 486, 290, true );
	add_image_size( 'post-thum', 300, 200, true );
	}
	add_action( 'after_setup_theme', 'add_mythumbnail_size' );
}

// 固定ページでタグを使用可能にする
function add_tag_to_page() {
 register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'add_tag_to_page');

//カテゴリー説明文でHTMLタグを使う
remove_filter( 'pre_term_description', 'wp_filter_kses' );

// 更新日を表示する
function get_mtime($format) {
    $mtime = get_the_modified_time('Ymd');
    $ptime = get_the_time('Ymd');
    if ($ptime > $mtime) {
        return get_the_time($format);
    } elseif ($ptime === $mtime) {
        return null;
    } else {
        return get_the_modified_time($format);
    }
}


// 埋め込みコンテンツサイズ
if ( ! isset( $content_width ) ) {
	$content_width = 728;
}

//iframeのレスポンシブ対応
function wrap_iframe_in_div($the_content) {
if ( is_singular() ) {
//YouTube
$the_content = preg_replace('/<iframe[^>]+?youtube\.com[^<]+?<\/iframe>/is', '<div class="youtube-container">${0}</div>', $the_content);
}
return $the_content;
}
add_filter('the_content','wrap_iframe_in_div');


//サイト内検索で固定ページを省く
function SearchFilter($query) {
if ($query->is_search && !is_admin()) {
$query->set('post_type', 'post');
}
return $query;
}
add_filter('pre_get_posts','SearchFilter');

//ユーザーページでHTMLを保存可能にする
remove_filter('pre_user_description', 'wp_filter_kses');

//ユーザー項目の追加と削除
if (!function_exists('update_profile_fields')) {
	function update_profile_fields( $contactmethods ) {
	    //項目の削除
	    unset($contactmethods['aim']);
	    unset($contactmethods['jabber']);
	    unset($contactmethods['yim']);
	    //項目の追加
	    $contactmethods['twitter'] = 'Twitter';
	    $contactmethods['facebook'] = 'Facebook';
	    $contactmethods['googleplus'] = 'Google+';

	    return $contactmethods;
	}
	add_filter('user_contactmethods','update_profile_fields',10,1);
}

//セルフピンバック禁止
function no_self_pingst( &$links ) {
    $home = home_url();
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}
add_action( 'pre_ping', 'no_self_pingst' );


// 一覧ページの抜粋のPを削除
function opencage_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
// more
if (!function_exists('opencage_excerpt_more')) {
	function opencage_excerpt_more($more) {
		global $post;
		return '...';
	}
}

// is_mobile追加
function is_mobile(){
$useragents = array(
'iPhone', // iPhone
'iPod', // iPod touch
'Android.*Mobile', // 1.5+ Android *** Only mobile
'Windows.*Phone', // *** Windows Phone
'dream', // Pre 1.5 Android
'CUPCAKE', // 1.5+ Android
'blackberry9500', // Storm
'blackberry9530', // Storm
'blackberry9520', // Storm v2
'blackberry9550', // Storm v2
'blackberry9800', // Torch
'webOS', // Palm Pre Experimental
'incognito', // Other iPhone browser
'webmate' // Other iPhone browser
);
$pattern = '/'.implode('|', $useragents).'/i';
return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}


//カスタムメニューに「説明」を追加（ナビゲーションの英語テキストに使用）
add_filter('walker_nav_menu_start_el', 'description_in_nav_menu', 10, 4);
function description_in_nav_menu($item_output, $item){
	return preg_replace('/(<a.*?>[^<]*?)</', '$1' . "<span class=\"gf\">{$item->description}</span><", $item_output);
}



/*********************
THEME SUPPORT
*********************/

//UPDATE CHECK
require 'library/theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
'jstork',
'http://open-cage.com/theme-update/stork/update-info.json'
);


function opencage_ahoy() {

// THEME CSS EDITOR INCLUDE
add_editor_style( get_bloginfo('template_url') . '/library/css/editor-style.css' );

  add_filter( 'wp_title', 'rw_title', 10, 3 );
  add_filter( 'the_generator', 'opencage_rss_version' );
  add_filter( 'wp_head', 'opencage_remove_wp_widget_recent_comments_style', 1 );
  add_action( 'wp_head', 'opencage_remove_recent_comments_style', 1 );

  // launching this stuff after theme setup
  opencage_theme_support();

  add_action( 'widgets_init', 'theme_register_sidebars' );
  add_filter( 'the_content', 'opencage_filter_ptags_on_images' );
  add_filter( 'excerpt_more', 'opencage_excerpt_more' );
}
add_action( 'after_setup_theme', 'opencage_ahoy' );