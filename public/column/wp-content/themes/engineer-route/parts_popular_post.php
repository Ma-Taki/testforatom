<div class="popular-post">
	<h2 class="widgettitle"><span>人気のコラム記事</span></h2>
	<?php setPostViews( get_the_ID() ); //記事のPV情報を取得 ?>
	<ul class="popular-list clearfix">
		<?php query_posts(array('meta_key' => 'post_views_count', 'orderby' => 'meta_value_num', 'post__not_in' => array(get_the_ID()),'posts_per_page' => 4, 'order' => 'DESC')); while(have_posts()) : the_post(); ?>
			<?php
				$cat = get_the_category(get_the_ID());
				$cat = $cat[0];
			?>
			<li class="popular-contents">
				<figure class="popular-eyecatch">
					<?php the_post_thumbnail('home-thum'); ?>
					<span class="cat-name cat-id-<?php echo $cat->cat_ID;?>">
						<?php echo $cat->name; ?>
					</span>
				</figure>
				<div class="popular-title">
					<p class="byline entry-meta vcard">
						<span class="date gf updated"><?php the_time('Y.m.d'); ?></span>

						<span class="writer name author"><span class="fn"><?php the_author(); ?></span></span>
					</p>
					<h3 class="title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>