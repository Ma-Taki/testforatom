<?php
  $url_encode=urlencode(get_permalink());
  $title_encode=urlencode(get_the_title());
  $tw_url = get_the_author_meta( 'twitter' );
  $tw_domain = array("http://twitter.com/"=>"","https://twitter.com/"=>"","//twitter.com/"=>"");
  $tw_user = '&via=' . strtr($tw_url , $tw_domain);
  $share_url   = get_permalink();
  $share_title = get_the_title();
?>
<div class="share short">
	<div class="sns">
		<ul class="clearfix">
			<!--ツイートボタン-->
			<li class="twitter"> 
				<a target="blank" href="http://twitter.com/intent/tweet?url=<?php echo $url_encode ?>&text=<?php echo urlencode( the_title( "" , "" , 0 ) ) ?>
					<?php if(get_the_author_meta('twitter')) : ?>
						<?php echo $tw_user ;?>
					<?php endif ;?>
					&tw_p=tweetbutton" onclick="window.open(this.href, 'tweetwindow', 'width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;" title="Twitterでシェア">
					<i class="fa fa-twitter"></i>
				</a>
			</li>
			<!--Facebookボタン-->      
			<li class="facebook">
				<a href="http://www.facebook.com/sharer.php?src=bm&u=<?php echo $url_encode;?>&t=<?php echo $title_encode;?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" title="Facebookでシェア">
				</a>
			</li>
			<!--はてブボタン-->  
			<li class="hatebu">       
				<a href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php the_permalink() ?>&title=<?php echo urlencode( the_title( "" , "" , 0 ) ) ?>" onclick="window.open(this.href, 'HBwindow', 'width=600, height=400, menubar=no, toolbar=no, scrollbars=yes'); return false;" target="_blank" title="はてなブックマークに登録">B!
				</a>
			</li>
			<!--LINEボタン-->
			<li class="line">
				<a href="//line.me/R/msg/title=<?php the_title(); ?>&url=<?php the_permalink() ?>" target="_blank" title="LINEに送る"></a>
			</li>
			<!--ポケットボタン-->      
			<li class="pocket">
				<a href="http://getpocket.com/edit?url=<?php the_permalink() ?>&title=<?php the_title(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=550, height=350, menubar=no, toolbar=no, scrollbars=yes'); return false;" title="Pocketに保存する">
					<i class="fa fa-get-pocket"></i>
				</a>
			</li>
		</ul>
	</div> 
</div>