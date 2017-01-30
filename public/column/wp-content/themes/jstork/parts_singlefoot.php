<div class="np-post">
<div class="navigation">
<div class="prev np-post-list">
<?php $nextpost = get_adjacent_post(false, '', false); if ($nextpost) : ?>
<a href="<?php echo get_permalink($nextpost->ID); ?>" class="cf">
<figure class="eyecatch"><?php echo get_the_post_thumbnail($nextpost->ID,'thumbnail'); ?></figure>
<span class="ttl"><?php echo esc_attr($nextpost->post_title); ?></span>
</a>
<?php else:?>
<div class="home_link">
<a href="<?php echo home_url(); ?>"><figure class="eyecatch"><i class="fa fa-home"></i></figure><span class="ttl">トップページへ</span></a>
</div>
<?php endif; ?>
</div>

<div class="next np-post-list">
<?php $prevpost = get_adjacent_post(false, '', true); if ($prevpost) : ?>
<a href="<?php echo get_permalink($prevpost->ID); ?>" class="cf">
<span class="ttl"><?php echo esc_attr($prevpost->post_title); ?></span>
<figure class="eyecatch"><?php echo get_the_post_thumbnail($prevpost->ID,'thumbnail'); ?></figure>
</a>
<?php else:?>
<div class="home_link">
<a href="<?php echo home_url(); ?>"><span class="ttl">トップページへ</span><figure class="eyecatch"><i class="fa fa-home"></i></figure></a>
</div>
<?php endif; ?>
</div>
</div>
</div>

<?php //プラグインYARPPを使っている場合はプラグインの設定画面から「カスタム > yarpp-template-relative.php」を選ぶとプラグインのレコメンド機能を実装できます。 ;?>
<?php if(function_exists('related_posts')): ?>
<?php related_posts(); ?>
<?php else :?>
<?php include( TEMPLATEPATH . '/related-entries.php' ); ?>
<?php endif;?>

<div class="authorbox wow animated fadeIn" data-wow-delay="0.5s">
<?php if(get_the_author_meta('description')) : ?>
<div class="inbox">
<div class="profile cf">
<h2 class="h_ttl"><span class="gf">ABOUT</span>この記事をかいた人</h2>
<?php echo get_avatar(get_the_author_meta('ID'), 150); ?>
<p class="name author"><?php the_author_posts_link(); ?></p>
<div class="profile_description">
<?php the_author_meta("description" ); ?>
</div>
<div class="author_sns gf">
<ul>
<?php if(get_the_author_meta('user_url')) : ?>
<li><a href="<?php the_author_meta( 'user_url' ); ?>" target="_blank"><i class="fa fa-globe"></i>WebSite</a></li>
<?php endif ;?>
<?php if(get_the_author_meta('twitter')) : ?>
<li><a href="<?php the_author_meta( 'twitter' ); ?>" rel="nofollow" target="_blank"><i class="fa fa-twitter fa-lg"></i>Twitter</a></li>
<?php endif ;?>
<?php if(get_the_author_meta('facebook')) : ?>
<li><a href="<?php the_author_meta( 'facebook' ); ?>" rel="nofollow" target="_blank"><i class="fa fa-facebook fa-lg"></i>Facebook</a></li>
<?php endif ;?>
<?php if(get_the_author_meta('googleplus')) : ?>
<li><a href="<?php the_author_meta( 'googleplus' ); ?>" rel="nofollow" target="_blank"><i class="fa fa-google-plus fa-lg"></i>Google+</a></li>
<?php endif ;?>
</ul>
</div>
</div>

<div class="author-newpost cf">
<h2 class="h_ttl"><span class="gf">NEW POST</span>このライターの最新記事</h2>
<ul>
<?php
  $author_id = get_the_author_meta( 'ID' );
  $args = array(
  'post_type' => 'post',
  'author' => $author_id,
  'showposts' => 4, /* 表示個数 */
); ?>
<?php $loop = new WP_Query( $args ); ?>
<?php while ($loop->have_posts()) : $loop->the_post(); ?>
<li>
<a href="<?php the_permalink() ?>">
<figure class="eyecatch">
<?php if(has_post_thumbnail()) { ?>
<?php the_post_thumbnail('post-thum'); ?>
<?php } else { ?>
<img src="<?php echo get_template_directory_uri(); ?>/library/images/noimg.png" />
<?php } ?>
<span class="cat-name"><?php $cat = get_the_category(); ?><?php $cat = $cat[0]; ?><?php echo get_cat_name($cat->term_id); ?></span>
</figure>
<time class="date gf"><?php the_time( 'Y.n.j' ); ?></time>
<h3 class="ttl">
	<?php if(mb_strlen($post->post_title)>38) { $title= mb_substr($post->post_title,0,38) ; echo $title. "…" ;
	} else {echo $post->post_title;}?>
</h3>
</a>
</li>
<?php endwhile; ?>
<?php wp_reset_query();?>
</ul>
</div>
</div>
<?php endif ;?>
</div>