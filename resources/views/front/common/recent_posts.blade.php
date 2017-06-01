
<?php
use App\Libraries\FrontUtility as FrntUtil;
$posts=FrntUtil::getRecentPosts(5);
?>

<div class = "recent-post-box">
@foreach($posts as $post)
<a href="/column/{{$post->ID}}/" rel="bookmark" title="{{$post->post_title}}">

<article class="post-list">
<figure class="eyecatch">
  <div class="cat-container">
@foreach ($post->category as $cat)
  <span class="cat-box">{{$cat}}</span>
@endforeach
</div>

@if($post->thumb)
  <img src="{{$post->thumb}}" alt="" />
@else
  <img style="background:lightgray"; alt="" />
@endif
</figure>
@foreach ($post->category as $cat)
@endforeach
<section class="entry-content">
<h3 class='title'>{{$post->post_title}}</h3>
<p><span class="date">{{$post->post_date->format('Y.m.d')}}</span></p>
<div class="description">
  <?php $body = mb_substr(strip_tags($post->post_content),0,80)?>
  {{$body}} ...
</div>
</section>
</article>
</a>
@endforeach
</div>

<style>


</style>
