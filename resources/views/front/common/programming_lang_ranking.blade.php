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


<style>

.ranking-flex-box{
  display:flex;
  display:-webkit-flex;
  flex-flow:row;
  -webkit-flex-flow:row;
  justify-content:start;
  -webkit-justify-content:start;
  -webkit-flex-wrap: wrap;
  flex-wrap:wrap;
}

.ranking-flex-box-w-center{
  justify-content:center;
  -webkit-justify-content:center;
}

.ranking-box{
  width:calc(50% - 10px);
  padding:30px 10px;
}

.ranking-box>ul{
  background-color:white;
  padding:10px 0px;
}

.ranking-box:first-child>ul{
  border:1px solid #5e8796;
}

.ranking-box:last-child>ul{
  border:1px solid #D46363;
}


.ranking-box:first-child{
  /*background-color:#E7EFF3;*/
}

.ranking-box:last-child{
  /*background-color:#F8F3F0;*/
}

.ranking-box h3{
  font-size:1.5rem;
  padding:15px 0;
  text-align:center;
}

.ranking-box li{
  padding:3px 0;
}

/*.ranking-box>li:nth-child(odd){
  background-color:whitesmoke;
}*/

.ranking-box .rank{
  display:inline-block;
  margin-right:10px;
  width:40px;
  letter-spacing:2px;
  /*background-color:#5e8796;*/
  color:white;
  text-align:center;
  font-weight:bold;
  margin-left:10px;
}

.ranking-box:last-child .lang>a{
  border-bottom:1px dashed #D46363;
}

.ranking-box:last-child .lang>a:hover{
  color:#D46363;
}

.ranking-box:first-child .rank{
  color:#5e8796;
}

.ranking-box:first-child li{
  border-bottom:1px solid #E7EFF3;
}

.ranking-box:last-child .rank{
  color:#D46363;
}

.ranking-box:last-child li{
  border-bottom:1px solid #F8F3F0;
}

.ranking-box li:last-child{
  border-bottom:none;
}

.ranking-box .lang{
  display:inline-block;
  line-height:24px;
  letter-spacing:2px;
}

.ranking-from{
  font-size:10px;
  margin-top:10px;
  color:gray;
  text-align:right;
  height:20px;
  line-height:20px;
  padding-right:10px;
}

.ranking-from>a{
  color:gray;
}

</style>
