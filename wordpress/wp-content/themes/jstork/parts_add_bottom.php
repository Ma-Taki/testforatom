<?php if ( is_front_page() ) : ?>
<?php if ( is_mobile() ) : ?>
<?php if ( is_active_sidebar( 'home-bottom_mobile' ) ) : ?>
<div class="home_widget bottom mobile cf">
<?php dynamic_sidebar( 'home-bottom_mobile' ); ?>
</div>
<?php endif; ?>
<?php else:?>
<?php if ( is_active_sidebar( 'home-bottom' ) ) : ?>
<div class="home_widget bottom cf">
<?php dynamic_sidebar( 'home-bottom' ); ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>