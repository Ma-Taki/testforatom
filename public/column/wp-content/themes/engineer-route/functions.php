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

//---------------------------------------//
// 内部リンクのブログカード化（ショートコード）
// ここから
//---------------------------------------//
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
	}
    //抜粋文を取得
	if(empty($excerpt)){
		$excerpt = esc_html(ltl_get_the_excerpt($id));
	}

    //アイキャッチ画像を取得
    if(has_post_thumbnail($id)) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($id),'post-thum');
        $img_tag = "<img src='" . $img[0] . "' alt='{$title}'/>";
    }

	$nlink = '<div class="blog-card">
  <a href="'. $url .'">
      <div class="blog-card-thumbnail">'. $img_tag .'</div>
      <div class="blog-card-content">
          <div class="blog-card-title">'. $title .' </div>
          <div class="blog-card-excerpt">'. $excerpt .'</div>
      </div>
      <div class="clear"></div>
  </a>
</div>';

	return $nlink;
}
add_shortcode("nlink", "nlink_scode");
