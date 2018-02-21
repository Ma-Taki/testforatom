
<?php if (isset($_GET['file_get_contents'])) :?>
	<?php /* 案件詳細ページのとき */
		switch ($_GET['file_get_contents']) {
		    case 0:
		    	/* ページタイトル */
		    	echo get_the_title(get_the_ID());
		        break;

		    case 1:
		    	/* ページ内容 */
		        $contents = get_post_field('post_content',get_the_ID());
		        $count = mb_strlen($contents,"UTF-8");
				if($count > 170){
		        	$contents = mb_substr($contents,0,170,"UTF-8").'･･･';
				}
				echo $contents;
		        break;
		}
	?>	
<?php else : ?>
	<?php get_header(); ?>
	<div id="content">
		<div id="inner-content" class="wrap cf">
			<!-- スマホ用パンくず -->
			<div class="invisible-pc invisible-tab">
				<?php breadcrumb(); ?>
			</div>
			<main id="main" class="m-all t-all d-5of7 cf" role="main">
				<?php get_template_part( 'parts_add_top' ); ?>
				<?php while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article">
						<div class="article-header entry-header">
							<p class="byline entry-meta vcard cf">
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
							<h1 class="entry-title page-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>
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
							<?php the_content(); ?>
						</section>
						<!-- タグ表示 -->
						<div class="article-footer">
							<?php echo get_the_category_list(); ?>
							<?php the_tags( '<p class="tags">', '', '</p>' ); ?>
						</div>
						<!-- SNSボタン表示 -->
						<?php if ( !get_option( 'sns_options_hide' ) ) : ?>
							<div class="sharewrap wow animated fadeIn" data-wow-delay="0.5s">
								<?php get_template_part( 'parts_sns' ); ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
				<?php get_template_part( 'parts_add_bottom' ); ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
<?php endif; ?>
