<?php

function theme_register_sidebars() {
  register_sidebar(array(
    'id' => 'sidebar1',
    'name' => __( 'PC：メインサイドバー', 'storktheme' ),
    'description' => __( 'メインのサイドバーです。', 'storktheme' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle"><span>',
    'after_title' => '</span></h4>',
  ));
}

// 新着記事のフォーマットを変更
class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {
	// サイドバーでどのように表示するか設定
  	function widget($args, $instance) {
    	extract( $args );

    	$title = apply_filters(
    		'widget_title', 
    		empty($instance['title']) ? __('Recent Posts') : $instance['title'], 
    		$instance, 
    		$this->id_base
    	);

    	if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
      	$number = 10;
    	$r = new WP_Query( apply_filters( 'widget_posts_args', array( 
														        'posts_per_page' => $number, 
														        'no_found_rows' => true,
														        'post_status' => 'publish',
														        'ignore_sticky_posts' => true 
														      )));

	    if( $r->have_posts() ) :
	      	echo $before_widget;
	      	if( $title )
	        	echo $before_title . $title . $after_title; ?>
	      	<ul>
	        	<?php while( $r->have_posts() ) : $r->the_post(); ?>        
		        	<li class="cf">
		          		<a class="cf" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		            		<?php the_title(); ?>
		            		<span class="date gf"><?php the_time('Y.m.d'); ?></span>
		          		</a>
		        	</li>
	        	<?php endwhile; ?>
	      	</ul>
		    <?php
		    	echo $after_widget;
		    	wp_reset_postdata();
		    endif;
	}
}
// 「人気の記事を表示」ウィジェットアイテムを作成
class hot_articles_Widget extends WP_Widget{

	//管理画面:ウィジェットタイトル
    public function __construct() {
        $widget_ops = array(
    		//classname : htmlタグのdiv class名
            'classname' => 'widget_hot_articles',
            'description' => '個別記事:同カテゴリーの人気記事を8つ表示します。個別記事以外:人気記事を8つ表示します。'
        );
        parent::__construct('hot-articles', 'よく読まれている記事を表示', $widget_ops);
    }
 	
 	// サイドバーでどのように表示するか設定
    public function widget($args, $instance) {

    	if(is_single() && !empty(get_the_category())){
    		$cat = get_the_category();
			$cat = $cat[0];
			$title = apply_filters('widget_title', '「'.$cat->name.'」でよく読まれている記事');
			$args_wp = array(
				'category_name' => $cat->name,
				'post__not_in' => array(get_the_ID()),
				'posts_per_page' => 8, 
				'order'=> 'DESC', 
				'meta_key' => 'post_views_count', 
				'orderby' => 'meta_value_num'
			);
    	}else{
    		$title = apply_filters('widget_title', 'よく読まれている記事');
    		$args_wp = array(
				'post__not_in' => array(get_the_ID()),
				'posts_per_page' => 8, 
				'order'=> 'DESC', 
				'meta_key' => 'post_views_count', 
				'orderby' => 'meta_value_num'
			);
    	}
    	$query = new WP_Query($args_wp);
        ?>

		<?php if( $query->have_posts() ) : ?>
			<?php 
				echo $args['before_widget'];
				if( $title )
	        		echo $args['before_title'] . $title . $args['after_title'];
			?>
			<ul>
	        	<?php while( $query->have_posts() ) : $query->the_post(); ?>        
		        	<li class="cf">
		          		<a class="cf" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		            		<?php the_title(); ?>
		            		<span class="date gf"><?php the_time('Y.m.d'); ?></span>
		          		</a>
		        	</li>
	        	<?php endwhile; ?>
	      	</ul>
			<?php 
				echo $args['after_widget'];
				wp_reset_postdata();
			 ?>
	    <?php endif; ?>
	<?php
	}
}

function my_recent_widget_registration() {
	unregister_widget('WP_Widget_Recent_Posts');
	register_widget('My_Recent_Posts_Widget');	
	register_widget('hot_articles_Widget');
}
add_action('widgets_init', 'my_recent_widget_registration');






?>