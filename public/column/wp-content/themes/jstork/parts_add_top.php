<?php if ( is_front_page() ) : ?>
<?php if ( is_mobile() ) : ?>
<?php if ( is_active_sidebar( 'home-top_mobile' ) ) : ?>
<div class="home_widget top mobile cf">
<?php dynamic_sidebar( 'home-top_mobile' ); ?>
</div>
<?php endif; ?>
<?php else:?>
<?php if ( is_active_sidebar( 'home-top' ) ) : ?>
<div class="home_widget top cf">
<?php dynamic_sidebar( 'home-top' ); ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>