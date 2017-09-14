@extends('admin.common.layout')
@section('title', '人気プログラミング言語ランキング')
@section('content')
<?php
    use App\Libraries\OrderUtility as OdrUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<style>

  #ranking-table tr{
    cursor:move;
  }

  #ranking-table tr th:first-child,#ranking-table tr td:first-child{
    width:100px;
  }

  #ranking-message{
    width:100%;
    color:red;
    text-align:right;
    padding-right:10px;
    height:20px;
    line-height:20px;
  }

  #ranking-message > span{
    display:none;
  }

  #reset-ranking-btn{
    margin-bottom:10px;
  }

  .nodata{
    border-top:1px solid lightgray;
    padding-top:10px;
    color:gray;
  }

  #month-link-box{
    overflow:hidden;
  }

  #month-link-box>a#prev_month{
    float:left;
  }

  #month-link-box>a#next_month{
    float:right;
  }


</style>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <p id="ranking-message"><span></span></p>
      <div>
  <div id="month-link-box">
@if($entry_list)
        <a id="prev_month" class="btn" href="/admin/programming-lang-ranking?month={{$prev_month}}">◀︎ 前月のランキング</a>
@endif
@if($next_month != null)
        <a id="next_month" class="btn" href="/admin/programming-lang-ranking?month={{$next_month}}">次月のランキング ▶︎</a>
@endif
  </div>
      </div>
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">人気プログラミング言語ランキング（{{ $month }}）</div>
    </div>
  		<div class="panel-body">
        @if($this_month->format("Ym") == $month)
                <button id="reset-ranking-btn" class="btn btn-primary btn-md">ランキングをリセットする</button>
        @endif

@if($entry_list)
        <input id="month" type="hidden" name="month" value="{{ $month }}" />
        <table id="ranking-table" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ランク</th>
              <th>言語</th>
            </tr>
          </thead>
          <tbody>
@foreach($entry_list as $key => $value)
            <tr>
              <td class="rank">{{ $key + 1 }}</td>
              <td class="lang">{{ $entry_list[$key] }}</td>
            </tr>
@endforeach
          </tbody>
        </table>
        <p>出典：<a href="https://www.tiobe.com/tiobe-index/" target="_blank">https://www.tiobe.com/tiobe-index/</a></p>
@else
<p class="nodata">ランキングデータが存在しません</p>
@endif
      </div>
    </div>
  </div>
</div>

@if($this_month->format("Ym") == $month)
<script type="text/javascript" src="{{ url('/admin/js/jquery-ui.js') }}"></script>
<script type="text/javascript">
$(function() {

  var BtnFlg = true;

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //順位並び替え
  $('#ranking-table tbody').sortable({

    helper: fitCellWidth,

    update: function(event,ui){

      var top20 = [];

      for(var i=0; i<$(".rank").length; i++){
        $(".rank")[i].innerHTML = i+1;
        top20.push($(".lang")[i].innerHTML);
      }

      $.ajax(
      {
        type:"POST",
        url: "/admin/edit-programming-lang-ranking",
        data: {
          "month":$("#month").val(),
          "top20":top20
        },
        success: function(hoge)
        {
          console.log("データベースが更新に成功しました");
        },
        error: function(XMLHttpRequest,textStatus,errorThrown)
        {
          alert('データベースの更新に失敗しました');
        }
      });

    }

  });

  //並び替え中にドラッグしているセルのwidth調整
  function fitCellWidth(e, tr) {

    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
      $(this).width($originals.eq(index).outerWidth());
    });
    return $helper;

  }

  //ランキングのリセット
  $("#reset-ranking-btn").on("click",function(){

    if(!BtnFlg){
      console.log("ボタン無効");
      return false;
    }else{
      console.log("ボタン有効");
      BtnFlg = false;
    }

    $.ajax(
    {
      type:"GET",
      url: "/admin/reset-programming-lang-ranking",
      success: function(data)
      {

        for(var i=0; i<data['ranking'].length; i++){
          $(".rank")[i].innerHTML = i+1;
          $(".lang")[i].innerHTML = data['ranking'][i];
        }

        showMessage("最新ランキングに変更されました")
      },
      error: function(XMLHttpRequest,textStatus,errorThrown)
      {
        showMessage("最新ランキングに変更できませんでした")
      }
    });

  });

  //メッセージ表示
  function showMessage(str){
    var message = $("#ranking-message > span");
    message.html(str);
    message.fadeIn("slow",function(){
      setTimeout(function(){
        message.fadeOut("slow",function(){
          message.html();
          BtnFlg = true;
        });
      },3000);
    });
  }

});
</script>
@endif
@endsection
