<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Tr_programming_lang_ranking;
use DB;
use Carbon\Carbon;

class ProgrammingLangRankingController extends AdminController
{

    /**
     * 人気プログラミング言語ランキング　トップページ
     * @param GET 日付(Ym:201709)
     */
    public function index(Request $request){

      //リクエストされた月のデータを取得
      if($request->month){
        $month = $request->month;
      //リクエストがなかったら今月（最新）のデータを取得
      }else{
        $month = Carbon::now()->format('Ym');
      }

      //データーベースから、その月のランキングを取得
      $ranking_info = self::getProgrammingLangRankingFromDatabase($month);

      //今月の（最新）データを取得したいけど、まだデータベースにない時は大元のウェブサイトを解析して取得（※毎週月曜日のクーロンがまだ来ていない場合）
      if( (!$request->month && !$ranking_info['ranking']) || (Carbon::now()->format('Ym') == $month && !$ranking_info['ranking']) ){
          $ranking_info = self::getProgrammingLangRankingFromWebsite();
      }

      //データを比較できるように前月と次月の日付（GET用）を作成
      $m = substr($month, -2);
      $y = substr($month,0,-2);
      $dt = new Carbon($y."-".$m);
      $prev_month = $dt->subMonth()->format("Ym");
      $next_month = $dt->addMonth(2);

      //最新の月だったら来月は無いのでNULL
      $this_month = Carbon::now();
      if($next_month->lt($this_month)){
        $next_month = $next_month->format("Ym");
      }else{
        $next_month = null;
      }

      return view('admin.programming_lang_ranking', [
        'entry_list' => $ranking_info['ranking'], //ランキングデータ
        'month'      => $ranking_info['month'],   //データを取得した月
        'next_month' => $next_month,              //来月
        'prev_month' => $prev_month,              //先月
        'this_month' => $this_month,              //今月
      ]);

    }

    /**
     * ランキング取得（ウェブサイトから解析）
     * >>> https://www.tiobe.com/tiobe-index/
     */
    public function getProgrammingLangRankingFromWebsite(){

      //プログラミング言語、人気ランキングサイト解析
      $html = file_get_contents('https://www.tiobe.com/tiobe-index/');
      $domDocument = new \DOMDocument();
      libxml_use_internal_errors( true );
      $domDocument->loadHTML($html);
      libxml_clear_errors();
      $xmlString = $domDocument->saveXML();
      $xmlObject = simplexml_load_string($xmlString);
      $array = json_decode(json_encode($xmlObject), true);

      //戻り値のトップ２０配列
      $top20 = [];
      foreach($array['body']['section']['section']['section']['article']['table'][0]['tbody']['tr'] as $data){
        $top20[] = $data['td'][3];
      }

      //今月の日付（Ym）
      $month = Carbon::now()->format('Ym');

      //今月のランキングがすでに存在していたらUPDATE
      if(Tr_programming_lang_ranking::where('month', $month)->exists()){
        for($i=0; $i<count($top20); $i ++){
          Tr_programming_lang_ranking::where('month', $month)->where('language',$top20[$i])->update(['ranking' => $i+1 ]);
        }
      //存在していなかったらINSERT
      }else{
        for($i=0; $i<count($top20); $i ++){
          $rank = new Tr_programming_lang_ranking;
          $rank->month = $month;
          $rank->ranking = $i + 1;
          $rank->language = $top20[$i];
          $rank->save();
        }
      }

      return array( "ranking" => $top20, "month" => $month);

    }


    /**
     * ランキング取得（データベースから解析）
     * @param $month 日付(Ym:201709)
     */
    public function getProgrammingLangRankingFromDatabase($month){

      //戻り値のトップ２０配列
      $top20 = [];

      //データベースからリクエスト月のランキングを取得
      $ranking = Tr_programming_lang_ranking::where('month',$month)->orderBy('ranking', 'asc')->get();

      //言語名だけを入れる
      foreach($ranking as $ranker){
        $top20[] = $ranker['language'];
      }

      return array( "ranking" => $top20, "month" => $month);

    }


    /**
     * ランキング操作
     * @param POST from ajax
     */
    public function editProgrammingLangRanking(Request $request){

      $top20 = $request->top20;
      $month = $request->month;

      //データベースのランキングを変更
      for($i=0; $i<count($top20); $i ++){
        Tr_programming_lang_ranking::where('month', $month)->where('language',$top20[$i])->update(['ranking' => $i+1 ]);
      }

    }


}
