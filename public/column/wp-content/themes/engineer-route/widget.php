<?php
// ウィジェット
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
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
			$number = 10;
		$r = new WP_Query( apply_filters( 
			'widget_posts_args', 
			array( 
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
function my_recent_widget_registration() {
  unregister_widget('WP_Widget_Recent_Posts');
  register_widget('My_Recent_Posts_Widget');
}
add_action('widgets_init', 'my_recent_widget_registration');
?>