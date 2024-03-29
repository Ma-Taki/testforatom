<?php
	global $wp_query;
	$cat = get_the_category();
	$cat = $cat[0];
	$site_title_encode=urlencode(get_home_url());
	$fdly_url = array("http%3A%2F%2F"=>"","https%3A%2F%2F"=>"");
	$plainurl= strtr( $site_title_encode, $fdly_url );
?>
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
							<?php get_template_part( 'amp-parts_sns_short' ); ?>
						<?php endif; ?>
					</div>
					<section class="entry-content cf">
						<?php the_content(); ?>
					</section>
					<div class="article-footer">
						<!-- バナー -->
						<p>
							<a href="https://www.engineer-route.com/user/regist">
								<amp-img layout="responsive" src="https://www.engineer-route.com/column/wp-content/uploads/engineer-route_yoko_bnr03-1.png" alt="プロのコーディネーターによる 非公開案件のご紹介 充実してます" width="615" height="219" class="alignnone size-full wp-image-1092" /></amp-img>
							</a>
						</p>
						<?php echo get_the_category_list(); ?>
						<?php the_tags( '<p class="tags">', '', '</p>' ); ?>
					</div>
					<?php if ( !get_option( 'sns_options_hide' ) ) : ?>
						<div class="sharewrap wow animated fadeIn" data-wow-delay="0.5s">
							<?php get_template_part( 'amp-parts_sns' ); ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
			<?php setPostViews( get_the_ID() ); //記事のPV情報を取得 ?>
			<div class="popular-post">
				<h4 class="widgettitle">
					<span>よく読まれている記事</span>
				</h4>
				<ul class="clearfix">
					<?php hot_articles( get_the_ID(), 4 ); ?>
				</ul>
			</div>
			<div class="popular-post">
				<h4 class="widgettitle">
					<span>「<?php echo $cat->cat_name; ?>」でよく読まれている記事</span>
				</h4>
				<ul class="clearfix">
					<?php hot_articles( get_the_ID(), 4, $cat->cat_ID ); ?>
				</ul>
			</div>
		</main>
		<?php get_template_part( 'amp', 'sidebar' ); ?>
	</div>
</div>
<?php wp_reset_query(); ?>
