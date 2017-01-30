<?php if ( get_option( 'opencage_toppage_headeregtext' ) || get_option( 'opencage_toppage_headerjptext' ) ) : ?>
<?php if ( is_front_page() && !is_paged() ) : ?>

<div id="custom_header" class="<?php echo esc_html(get_option('opencage_toppage_textlayout')); ?>" style="color:<?php echo get_theme_mod('opencage_toppage_textcolor');?>; background-image: url(<?php if ( get_theme_mod('opencage_toppage_headerbgsp') && is_mobile()):?><?php echo get_theme_mod('opencage_toppage_headerbgsp');?><?php else:?><?php echo get_theme_mod('opencage_toppage_headerbg');?><?php endif;?>); background-position: center center; background-repeat:<?php echo get_option('opencage_toppage_headerbgrepeat');?>; background-size:<?php echo get_option('opencage_toppage_headerbgsize');?>;">
<script type="text/javascript">
jQuery(function( $ ) {
	$(window).load(function(){
	    $("#custom_header .wrap").css("opacity", "100");
	});
});
</script>

	<div class="wrap cf" style="opacity: 0;">
		<div class="header-text">
			<?php if ( get_option( 'opencage_toppage_headeregtext' )) : ?>
			<h2 class="en gf wow animated fadeInDown" data-wow-delay="0.5s"><?php echo get_option( 'opencage_toppage_headeregtext' ); ?></h2>
			<?php endif; ?>
			<?php if ( get_option( 'opencage_toppage_headerjptext' )) : ?>
			<p class="ja wow animated fadeInUp" data-wow-delay="0.8s"><?php echo get_option( 'opencage_toppage_headerjptext' ); ?></p>
			<?php endif; ?>
			<?php if ( get_option( 'opencage_toppage_headerlink' )) : ?>
			<p class="btn-wrap simple maru wow animated fadeInUp" data-wow-delay="1s"><a style="color:<?php echo get_theme_mod( 'opencage_toppage_btncolor' ); ?>;background:<?php echo get_theme_mod( 'opencage_toppage_btnbgcolor' ); ?>;" href="<?php echo get_option( 'opencage_toppage_headerlink' ); ?>"><?php if ( get_option( 'opencage_toppage_headerlinktext' )) : ?><?php echo get_option( 'opencage_toppage_headerlinktext' ); ?><?php else:?>詳しくはこちら<?php endif;?></a></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ( is_front_page() || is_home() ) : ?>
<?php		  
$args = array(
    'posts_per_page' => 16,
    'offset' => 0,
    'tag' => 'pickup',
    'orderby' => 'post_date',
    'order' => 'DESC',
    'post_type' => array('post','page'),
    'post_status' => 'publish',
    'suppress_filters' => true,
    'ignore_sticky_posts' => true,
    'no_found_rows' => true
);
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
	?>

<script type="text/javascript">
jQuery(function( $ ) {
	$('.slickcar').slick({
		centerMode: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000,
		speed: 260,
		centerPadding: '90px',
		slidesToShow: 4,
		responsive: [
		{
			breakpoint: 1160,
			settings: {
			arrows: false,
			centerMode: true,
			centerPadding: '40px',
			slidesToShow: 3
		}
		},
		{
			breakpoint: 768,
			settings: {
			arrows: false,
			centerMode: true,
			centerPadding: '40px',
			slidesToShow: 2
		}
		},
		{
			breakpoint: 480,
			settings: {
			arrows: false,
			centerMode: true,
			centerPadding: '25px',
			slidesToShow: 1
		}
		}]
	});
	$(window).load(function(){
	    $(".slickcar").css("opacity", "1.0");
	});
});
</script>

<div id="top_carousel" class="carouselwrap wrap cf">
<ul class="slider slickcar" style="opacity: 0;">

<?php while ( $the_query->have_posts() ) {
$the_query->the_post();
?>
<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
<?php if ( has_post_thumbnail()) : ?>
<figure class="eyecatch">
<?php
$cat = get_the_category();
$cat = $cat[0];
?>
<?php the_post_thumbnail('home-thum'); ?>
<span class="osusume-label cat-name cat-id-<?php echo $cat->cat_ID;?>"><?php echo $cat->name; ?></span>
</figure>
<?php else: ?>
<figure class="eyecatch noimg">
<img src="<?php echo get_template_directory_uri(); ?>/library/images/noimg.png">
<span class="osusume-label cat-name cat-id-<?php echo $cat->cat_ID;?>"><?php echo $cat->name; ?></span>
</figure>
<?php endif; ?>
<h2 class="h2 entry-title"><?php the_title(); ?></h2>
</a></li>
<?php } ; ?>
</ul>
</div>
<?php }
wp_reset_postdata();
?>
<?php endif; ?>

