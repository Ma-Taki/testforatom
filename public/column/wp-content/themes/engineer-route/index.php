<?php get_header(); ?>
<div id="content">
	<div id="inner-content" class="wrap cf">
		<main id="main" class="m-all t-all d-5of7 cf" role="main">
			<?php get_template_part( 'parts_archive_simple' ); ?>
			<?php pagination(); ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>