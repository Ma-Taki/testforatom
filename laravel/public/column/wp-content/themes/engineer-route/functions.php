<?php
/**
 * include function file
 **/
require_once( 'widget.php' );

require __DIR__.'/../../../../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

/* 案件詳細ページで固定ページを表示するとき以外に処理する */
if (!isset($_GET['file_get_contents'])) {
	$response = $kernel->handle(
    	$request = Illuminate\Http\Request::capture()
	);
}

use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\ConsiderUtility as CnsUtil;

function frontIsLogin(){
	return FrntUtil::isLogin();
}

function culcConsiderLength(){
	return CnsUtil::culcConsiderLength();
}

//アイキャッチ画像
add_theme_support('post-thumbnails');

/**
 * 正規化のためのURLを返却する
 **/
function getCanonical() {
    echo '"'. (empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]. '"';
}

//---------------------------------------
// 無効化・非表示
//---------------------------------------
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head','rest_output_link_wp_head' );
remove_action( 'wp_head','wp_oembed_add_discovery_links' );
remove_action( 'wp_head','wp_oembed_add_host_js' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
add_filter( 'emoji_svg_url', '__return_false' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

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
        // 固定ページ以外の記事のページ
        if(!is_page()){
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
		}
		
        // 記事
        $addNext($breadcrumbs);
        $addElement($queried_obj->post_title);

    } else if (!empty($queried_obj) && get_class($queried_obj) == "WP_Term") {
        // カテゴリーのアーカイブページ
        if ($queried_obj->taxonomy == 'category') {
            if (!empty($queried_obj->category_parent)) {
                // 親カテゴリ
                $addNext();
                $addElement(get_cat_name($queried_obj->category_parent), '/column/?cat='.$queried_obj->category_parent);
            }
            // カテゴリ、または子カテゴリ
            $addNext($breadcrumbs);
            $addElement($queried_obj->cat_name);

        // タグのアーカイブページ
        } else if ($queried_obj->taxonomy == 'post_tag') {
            $addNext();
            $addElement($queried_obj->name);
        }
    }
    $createBreadcrumbsEnd();
    echo $breadcrumbs;
}

/**
 * description
 **/
// function description() {
//     $description = '';
//     if (is_home() || is_front_page()) {
//         // トップページ
//         $description = get_bloginfo('description');
//     } else if (get_queried_object()) {
//         if (is_category()) {
//             // カテゴリページ
//             $queried_obj = get_queried_object();
//             $description = $queried_obj->cat_name. 'についてのコラム一覧ページです。';
//             $description.= 'エンジニアルートでは、フリーランスエンジニアとして成功するための知識や、IT業界事情など、幅広い情報をお届けしています。';
//         } else {
//             // 記事ページ
//             $queried_obj = get_queried_object();
//             // htmlタグをすべて削除
//             $description = strip_tags($queried_obj->post_content);
//             // 先頭から100文字
//             $description = mb_substr($description, 0, 100) . '...';
//             // 改行を半角スペースに変換
//             $description =  str_replace(PHP_EOL, ' ', $description);
//         }
//     }
//     echo '"'. $description. '"';
// }

/**
 * titleタグを最適化（ | でつなぐ）
 **/
if (!function_exists('rw_title')) {
	function rw_title( $title, $sep, $seplocation ) {
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

/**
 * パンくずナビ
 **/
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

/**
 * ページネーション
 **/
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
		$form = 
			'<form role="search" method="get" id="searchform" class="searchform cf" action="' . home_url( '/' ) . '" >
				<input type="search" placeholder="検索する" value="' . get_search_query() . '" name="s" id="s" />
				<button type="submit" id="searchsubmit" >
					<i class="fa fa-search">
					</i>
				</button>
			</form>';
		return $form;
	}
	add_filter( 'get_search_form', 'my_search_form' );
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

//...表示
if (!function_exists('opencage_excerpt_more')) {
	function opencage_excerpt_more($more) {
		global $post;
		return '...';
	}
}

// is_mobile追加
function is_mobile(){
	if(empty($_SERVER['HTTP_USER_AGENT'])) {
	    return array(
	        'name' => 'unrecognized',
	        'version' => 'unknown',
	        'platform' => 'unrecognized',
	        'userAgent' => ''
	    );
	}
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

/**
 * THEME SUPPORT
 **/
function opencage_ahoy() {
	add_filter( 'wp_title', 'rw_title', 10, 3 );
	add_action( 'widgets_init', 'theme_register_sidebars' );
}
add_action( 'after_setup_theme', 'opencage_ahoy' );



//post・page保存時に実行するアクションフックを作成
add_action('save_post', 'my_save_post', 10, 3);
/**
 * @param $post_id: 保存された投稿のID
 * @param $post: 保存された投稿のオブジェクト
 */
function my_save_post($post_id, $post){
	//固定ページで新規作成以外のとき
	if($post->post_type === 'page' && !empty($post->post_name) && !empty(get_the_title()) && !empty($post->post_content)){
		//ファイル名(タイトル名, 内容名)
		$files = array($post->post_name.'_title.php', $post->post_name.'_content.php');
		//記事のタイトル
		$sWriteTitle = get_the_title();
		//記事の内容
		$sWriteContent = "";
		if(mb_strlen($post->post_content, 'UTF-8')>150){
			//最初の150文字を挿入
			$sWriteContent = mb_substr($post->post_content, 0, 150, 'UTF-8')."…";
		}else{
			$sWriteContent = $post->post_content;
		}

		foreach ($files as $valu => $file) {
			//ファイルパス
			$sPath = './../../../storage/app/public/'.$file;

			//新規作成のとき
			if(!file_exists($sPath)){
				//ファイルを作成
				if(!touch($sPath)){
					echo 'ファイルの作成を失敗しました。<br/>';
				    exit;
				}
			}
			//ファイルをオープン
			$filepoint = fopen($sPath,"w");
			//ファイルのロック
			if(!flock($filepoint, LOCK_EX)){
				echo 'ファイルのロックを失敗しました。<br/>';
			    exit;
			}
			if($valu == 0){
				//ファイルへタイトル書き込み
				if(!fwrite($filepoint,$sWriteTitle)){
					echo 'タイトルの書き込みを失敗しました。<br/>';
				    exit;
				}
			}elseif ($valu == 1) {
				//ファイルへ内容書き込み
				if(!fwrite($filepoint,$sWriteContent)){
					echo '内容の書き込みを失敗しました。<br/>';
				    exit;
				}
			}
			//ファイルのロック解除
			if(!flock($filepoint, LOCK_UN)){
				echo 'ロック解除を失敗しました。<br/>';
			    exit;
			}
			//ファイルを閉じる
			if(!fclose($filepoint)){
				echo 'ファイルを閉じることに失敗しました。<br/>';
			    exit;
			}

		}
	}
}

function my_search_form( $form ) {
 
    $form = '<form role="search" method="get" id="searchform" action="'.home_url( '/' ).'" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';
 
    return $form;
}
 
add_filter( 'get_search_form', 'my_search_form' );

//---------------------------------------
// 内部リンクのブログカード化（ショートコード）
//---------------------------------------
// 記事IDを指定して抜粋文を取得する
function ltl_get_the_excerpt($post_id){
  global $post;
  $post_bu = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt();
  $post = $post_bu;
  return $output;
}

//内部リンクをはてなカード風にするショートコード
function nlink_scode($atts) {
	extract(shortcode_atts(array(
		'url'=>"",
		'title'=>"",
		'excerpt'=>""
	),$atts));

	$id = url_to_postid($url);//URLから投稿IDを取得

	//タイトルを取得
	if(empty($title)){
		$title = esc_html(get_the_title($id));
		if(mb_strlen($title, 'UTF-8')>45){
			$title = mb_substr($title, 0, 45, 'UTF-8')."…";
		}
	}
    //本文を取得
	if(empty($excerpt)){
		$post = get_post($id);
		if(mb_strlen($post->post_content, 'UTF-8')>80){
			$excerpt = mb_substr($post->post_content, 0, 80, 'UTF-8')."…";
		}else{
			$excerpt = $post->post_content;
		}
		$excerpt = wp_strip_all_tags($excerpt);
	}

    //アイキャッチ画像を取得
    if(has_post_thumbnail($id)) {
        $img_tag = get_the_post_thumbnail( $id, 'large', array('alt' => $title));
    }

	$nlink = '<div class="blog-card">
  <a class="no-icon" href="'. $url .'" target="_blank">
      <div class="blog-card-thumbnail">'. $img_tag .'</div>
      <div class="blog-card-content">
          <p class="blog-card-title">'. $title .' </p>
          <p class="blog-card-discription">'. $excerpt .'</p>
      </div>
      <div class="clear"></div>
  </a>
</div>';

	return $nlink;
}
add_shortcode("nlink", "nlink_scode");

//---------------------------------------
//Jetpack facebook自動投稿文字数制限
//---------------------------------------
function publicize_message_rewrite($post_id){
	$num = 150; //ここに字数制限を指定
	if(class_exists('Publicize') && class_exists('Publicize_Base') && isset($_POST['content'])){
		$str = preg_replace('/( |　| )/', '', strip_tags($_POST['content']));
		$str = (mb_strlen($str) > $num) ? mb_substr($str, 0, $num - 2, get_bloginfo('charset')).'...' : $str;
		update_post_meta($post_id, '_wpas_mess', $str);
		unset($str);
	}
	unset($num);
}
add_action('save_post', 'publicize_message_rewrite', 999, 1);


//---------------------------------------
//人気記事出力
//---------------------------------------
//書き出し用
function getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count ==''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return "0 View";
	}
	return $count.' Views';
}
//カウントアップ用
function setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count ==''){
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
	}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
	}
}
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

//---------------------------------------
//投稿一覧画面にカスタムフィールドの表示カラムを追加
//---------------------------------------
function my_posts_columns($columns){
    $new_columns = array();
    foreach ($columns as $name => $val){
    	if ( 'categories' == $name ) {
            $new_columns['date'] = '日付';
        }
        if('tags' == $name){
            $new_columns['カウント'] = 'カウント';
        }
        $new_columns[ $name ] = $val;
    }
    return $new_columns;
}

add_filter( 'manage_posts_columns', 'my_posts_columns' );

function my_posts_custom_column($column, $post_id){
    switch($column){
        case 'カウント':
            $post_meta=get_post_meta($post_id,'post_views_count', true);
            if($post_meta){
				echo $post_meta;
            }else{
                echo '';
            }
            break;
    }
}
add_action( 'manage_posts_custom_column' , 'my_posts_custom_column', 10, 2 );

//---------------------------------------
//よく読まれている記事
// $exclude : 現在表示中の記事番号(除外)
// $posts_per_page : 表示したい記事数
// $category : 表示したいカテゴリー番号(ない場合は0)
//---------------------------------------
?>
<?php 
function hot_articles( $id, $posts_per_page, $category_id = 0 ) {
	if($category_id > 0){
		$args = array(
			'post__not_in' => array($id), 
			'posts_per_page' => $posts_per_page, 
			'order'=> 'DESC', 
			'meta_key' => 'post_views_count', 
			'orderby' => 'meta_value_num', 
			'cat' => $category_id
		);
	}else{
		$args = array(
			'post__not_in' => array($id),
			'posts_per_page' => $posts_per_page, 
			'order'=> 'DESC', 
			'meta_key' => 'post_views_count', 
			'orderby' => 'meta_value_num'
		);
	}

	$query = new WP_Query($args);
?>

	<?php if( $query->have_posts() ) : ?>
		<?php while ($query->have_posts()) : $query->the_post();
			$cat = get_the_category(get_the_ID());
			$cat = $cat[0];

			//アイキャッチ画像を取得
		    if(has_post_thumbnail(get_the_ID())){
		    	$tag_img = get_the_post_thumbnail( get_the_ID(), 'large', array('alt' => get_the_title()));
		    }
		?>
			<li class="popular-contents">
				<a class="no-icon" href="<?php the_permalink(); ?>">
					<figure class="popular-eyecatch">
						<?php echo $tag_img; ?>
						<span class="cat-name cat-id-<?php echo $cat->cat_ID;?>">
							<?php echo $cat->name; ?>
						</span>
					</figure>
					<div class="popular-title">
						<p class="byline entry-meta vcard">
							<span class="date gf updated"><?php the_time('Y.m.d'); ?></span>
							<span class="writer name author"><span class="fn"><?php the_author(); ?></span></span>
						</p>
						<p class="title">
								<?php
									if(mb_strlen(get_the_title(), 'UTF-8') > 34){
										$title= mb_substr(get_the_title(), 0, 34, 'UTF-8');
										echo $title.'…';
									}else{
										echo get_the_title();
									}
								?>
						</p>
					</div>
				</a>
			</li>
		<?php endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>
<?php } ?>
<?php 
//---------------------------------------
//トップページ　続きを読む
//---------------------------------------
function new_excerpt_more($more) {
	return '…';
}
add_filter('excerpt_more', 'new_excerpt_more');

//---------------------------------------
//記事詳細テンプレートをAMP専用テンプレートに切り替え
//---------------------------------------
function change_amp_template($single_template) {
    $change_template = $single_template;

    if(isset($_GET['amp']) && $_GET['amp'] == 1){
        $amp_template = locate_template('amp.php');

        if(!empty($amp_template)){
            $change_template = $amp_template;
        }

        //アイキャッチ画像の<img>を<amp-img>へ書き換え
		function otherImg( $html ){
		    $html = preg_replace('/<img/', '<amp-img layout="responsive"', $html);
		    $html = $html.'</amp-img>';
		    return $html;
		}
		add_filter( 'post_thumbnail_html', 'otherImg' );

        function the_content_filter( $content ) {
        	//<amp-img>へ書き換え
			$content = preg_replace('/<img (.*?)>/i', '<amp-img layout="responsive" $1></amp-img>', $content);
			$content = preg_replace('/<img (.*?) \/>/i', '<amp-img layout="responsive" layout="responsive"$1></amp-img>', $content);

			 //gist
            $content = preg_replace('/<script src="https:\/\/gist.github\.com\/.*\/(.*)\.js"><\/script>/', '<amp-gist data-gistid="$1" layout="fixed-height" height="241"></amp-gist>', $content);

            //スクリプトを除去
            $pattern = '/<script.+?<\/script>/is';
            $append = '';
            $content = preg_replace($pattern, $append, $content);

            return $content;
        }
        add_filter( 'the_content', 'the_content_filter', 12 );

    }
    return $change_template;
}
add_filter('single_template', 'change_amp_template');

//---------------------------------------
// linkタグにAMP用URLを記載
//---------------------------------------
function amp_link_tag(){
    if(is_singular('post')){
        echo '<link rel="amphtml" href="'.esc_url(get_permalink()).'?amp=1">'."\n";
    }
}
add_action('wp_head', 'amp_link_tag');

//---------------------------------------
// meta情報　keywords/description
//---------------------------------------
// 記事ページと固定ページでカスタムフィールドを表示
function add_seo_custom_fields() {
	$screen = array('page' , 'post');
	add_meta_box( 'seo_setting', 'SEO', 'seo_custom_fields', $screen );
}

function seo_custom_fields() {
	global $post;
	$meta_keywords = get_post_meta($post->ID,'meta_keywords',true);
	$noindex = get_post_meta($post->ID,'noindex',true);
	echo '<p>keywords（キーワードを半角カンマ区切りで5個記入する　例:Java,php,swift,C#,html）<br />';
	echo '<input type="text" name="meta_keywords" value="'.esc_html($meta_keywords).'" size="80" /></p>';
}

// カスタムフィールドの値を保存
function save_seo_custom_fields( $post_id ) {
	if(!empty($_POST['meta_keywords'])){
		update_post_meta($post_id, 'meta_keywords', $_POST['meta_keywords'] );
	}else{
		delete_post_meta($post_id, 'meta_keywords');
	}

	if(!empty($_POST['noindex'])){
		update_post_meta($post_id, 'noindex', $_POST['noindex'] );
	}else{
		delete_post_meta($post_id, 'noindex');
	}
}
add_action('admin_menu', 'add_seo_custom_fields');
add_action('save_post', 'save_seo_custom_fields');

function MataTitle() {
	// カスタムフィールドの値を読み込む
	$custom = get_post_custom();
	if(!empty( $custom['meta_keywords'][0])) {
		$keywords = $custom['meta_keywords'][0];
	}

	if(is_home()){
		// トップページ
		echo '<meta name="keywords" content="エンジニアルート,コラム,フリーエンジニア,エンジニア,フリーランス">';
		echo '<meta name="description" content="エンジニアルートのコラムは、フリーエンジニアをサポートする記事を公開しています！">';
	}elseif(is_single()){
		// 記事ページ
		if(empty($keywords)) {
			$keywords = 'IT案件,案件情報,求人,案件,仕事';
		}
		$description = strip_tags(get_the_excerpt());
		if(empty($description)) {
			$queried_obj = get_queried_object();
            // htmlタグをすべて削除
            $description = strip_tags($queried_obj->post_content);
            // 先頭から100文字
            $description = mb_substr($description, 0, 100) . '...';
            // 改行を半角スペースに変換
            $description =  str_replace(PHP_EOL, ' ', $description);
		}
		echo '<meta name="keywords" content="'.$keywords.'">';
		echo '<meta name="description" content="'.$description.'">';
	}elseif(is_page()){
		// 固定ページ
		if(empty($keywords)) {
			$keywords = 'エンジニア,案件情報,求人,案件,仕事';
		}
		$description = strip_tags(get_the_excerpt());
		if(empty($description)) {
			$queried_obj = get_queried_object();
            // htmlタグをすべて削除
            $description = strip_tags($queried_obj->post_content);
            // 先頭から100文字
            $description = mb_substr($description, 0, 100) . '...';
            // 改行を半角スペースに変換
            $description =  str_replace(PHP_EOL, ' ', $description);
		}
		echo '<meta name="keywords" content="'.$keywords.'">';
		echo '<meta name="description" content="'.$description.'">';
	}elseif(is_archive()){
		if(is_category()){
			// カテゴリーページ
			echo '<meta name="keywords" content="'.single_cat_title('', false).',エンジニアルート,コラム,">';
			echo '<meta name="description" content="'.single_cat_title('', false).'の記事一覧">';
		}
		if(is_tag()){
			// タグページ
			$meta = str_replace('#', '', single_tag_title('', false));
			echo '<meta name="keywords" content="'.$meta.',エンジニアルート,コラム,">';
			echo '<meta name="description" content="'.$meta.'の記事一覧">';
		}
	}elseif(is_404()){
		// 404ページ
		echo '<meta name="robots" content="noindex, follow">';
		echo '<title>404:お探しのページが見つかりませんでした</title>';
	}else{
		// その他ページ
		echo '<meta name="robots" content="noindex, follow">';
	};
}

// 固定ページで抜粋欄を表示する
add_post_type_support( 'page', 'excerpt' );

?>
