<div class="share">
	<div class="sns">
		<ul class="clearfix">
			<!--ツイートボタン-->
			<li class="twitter"> 
				<amp-social-share type="twitter" data-param-text="<?php echo get_the_title(); ?>"></amp-social-share>
			</li>
			<!--Facebookボタン-->      
			<li class="facebook">
				<amp-social-share type="facebook" data-param-app_id="254325784911610"></amp-social-share>
			</li>
			<!--はてブボタン-->
			<li class="hatebu">
				<amp-social-share type="hatena_bookmark" layout="container" data-share-endpoint="http://b.hatena.ne.jp/add?mode=confirm&url=<?php the_permalink() ?>&title=<?php echo urlencode( the_title( "" , "" , 0 ) ) ?>">B!</amp-social-share>
			</li>
			<!--ポケットボタン-->
			<li class="pocket">
				<amp-social-share type="pocket" layout="container" data-share-endpoint="http://getpocket.com/edit?url=SOURCE_URL" data-param-text="<?php echo urlencode( the_title( "" , "" , 0 ) ) ?>"><i class="fa fa-get-pocket"></i></amp-social-share>
			</li>
			<!--LINEボタン-->
			<li class="line">
				<amp-social-share type="line" layout="container"></amp-social-share>
			</li>
			<!--feedlyボタン-->
			<li class="feedly">
				<amp-social-share type="feedly" layout="container" data-share-endpoint="https://feedly.com/i/subscription/feed/<?php echo get_home_url(); ?>/feed/"><i class="fa fa-rss"></i></amp-social-share>
			</li>
		</ul>
	</div>
</div>