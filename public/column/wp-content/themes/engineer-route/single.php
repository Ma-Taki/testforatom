<?php get_header(); ?>
<?php
	global $wp_query;
	$postid = $wp_query->post->ID;
	$tai = get_post_meta($postid, 'singlepostlayout_radio', true);
?>
<?php if ($tai == 'フルサイズ（1カラム）' || $tai == 'バイラル風（1カラム）') : ?>
	<?php get_template_part( 'singleparts_full' ); ?>
<?php else : ?>
	<div id="content">
		<div id="inner-content" class="wrap cf">
		<!-- スマホ用パンくず -->
			<div class="invisible-pc invisible-tab">
				<?php breadcrumb(); ?>
			</div>
			<main id="main" class="m-all t-all d-5of7 cf" role="main">
				<?php while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">
						<?php dynamic_sidebar( 'addbanner-titletop' ); ?>
						<div class="article-header entry-header">
							<p class="byline entry-meta vcard cf">
								<?php
									$cat = get_the_category();
									$cat = $cat[0];
								?>
								<span class="cat-name cat-id-<?php echo $cat->cat_ID;?>">
									<?php echo $cat->name; ?>
								</span>
								<time class="date gf entry-date <?php if ($mtime = !get_mtime('Y.m.d')):?>updated<?php endif;?>">
									<?php the_time('Y.m.d'); ?>
								</time>
								<?php if ($mtime = get_mtime('Y.m.d')) echo '<time class="date gf entry-date undo updated">'. $mtime .'</time>'; ?>
								<span class="writer name author">
									<span class="fn">
										<?php the_author(); ?>
									</span>
								</span>
							</p>
							<h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>
							<?php if ( has_post_thumbnail() && !get_option( 'post_options_eyecatch' ) ) :?>
								<figure class="eyecatch">
									<?php the_post_thumbnail(); ?>
								</figure>
							<?php endif; ?>
							<?php if ( !get_option( 'sns_options_hide' ) ) : ?>
								<?php get_template_part( 'parts_sns_short' ); ?>
							<?php endif; ?>
						</div>
						<section class="entry-content cf">
							<?php the_content();
								wp_link_pages( array(
								'before'      => '<div class="page-links cf"><ul>',
								'after'       => '</ul></div>',
								'link_before' => '<li><span>',
								'link_after'  => '</span></li>',
								'next_or_number'   => 'next',
								'nextpagelink'     => __('次のページへ ≫'),
								'previouspagelink' => __('≪ 前のページへ'),
								) );?>
						</section>
						<div class="article-footer">
						<!-- バナー作成 -->
					<p>
						<a href="https://www.engineer-route.com/user/regist/auth">
						<img src="https://www.engineer-route.com/column/wp-content/uploads/engineer-route_yoko_bnr03-1.png" alt="プロのコーディネーターによる 非公開案件のご紹介 充実してます" width="615" height="219" class="alignnone size-full wp-image-1092" /></a>
					</p>

							<?php echo get_the_category_list(); ?>
							<?php the_tags( '<p class="tags">', '', '</p>' ); ?>
						</div>
						<?php if ( !get_option( 'sns_options_hide' ) ) : ?>
							<div class="sharewrap wow animated fadeIn" data-wow-delay="0.5s">
								<?php get_template_part( 'parts_sns' ); ?>
							</div>
						<?php endif; ?>
						<?php //comments_template(); ?>
					</article>
					<?php //get_template_part( 'parts_singlefoot' ); ?>
				<?php endwhile; ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
<?php endif; wp_reset_query(); //ワンカラム条件分岐END ?>
<?php get_footer(); ?>