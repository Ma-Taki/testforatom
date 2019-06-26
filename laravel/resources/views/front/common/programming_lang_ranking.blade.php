<?php
    use App\Libraries\FrontUtility as FrntUtil;
    //人気ランキング
    $pop_ranking = FrntUtil::getProgrammingLangRanking(10);
    //案件数ランキング
    $item_ranking = FrntUtil::getItemRankingByProgrammingLanguage(10);
?>

<div id="ranking-container" class="ranking-flex-box ranking-flex-box-w-center">
<div class = "ranking-box">
<ul>
<h3>人気ランキング（{{ $pop_ranking['month'] }}現在）</h3>
@foreach($pop_ranking['ranking'] as $number => $language)
  <li>
    <span class="rank">{{$number+1}}位</span><span class="lang">{{$language}}</span>
  </li>
@endforeach
<p class="ranking-from">出典：<a href="https://www.tiobe.com/tiobe-index/" target="_blank">https://www.tiobe.com/tiobe-index/</a></p>
</ul>
</div>
<div class = "ranking-box">
<ul>
<h3>当サイト案件数ランキング</h3>
@foreach($item_ranking as $number => $ranking)
  <li>
    <span class="rank">{{$number+1}}位</span><span class="lang"><a href="/item/search?skills%5B%5D={{ $ranking['id'] }}">{{$ranking['lang']}}</a></span>
  </li>
@endforeach
<p class="ranking-from"></p>

</ul>
</div>
</div>
